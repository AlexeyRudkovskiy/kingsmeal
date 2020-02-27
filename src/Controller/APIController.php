<?php

namespace App\Controller;

use App\Entity\Advertising;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Repository\AdvertisingRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Services\AutoResponse;
use App\Services\ConverterService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * Class APIController
 * @package App\Controller
 *
 * @Route("/api")
 */
class APIController extends AbstractController
{
    /**
     * List the created categories
     *
     * @Route("/categories", name="api_categories", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @param ConverterService $converterService
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(
        CategoryRepository $categoryRepository,
        ConverterService $converterService
    )
    {

        $categories = $categoryRepository->findAll();
        return $this->json($converterService->get(Category::class)->convertArray($categories));
    }

    /**
     * @Route("/products/{id}")
     */
    public function getProducts(Category $category, ConverterService $converter)
    {
        $productConverter = $converter->get(Product::class);
        return $this->json($productConverter->convertArray($category->getProducts()->toArray()));
    }

    /**
     * @param AdvertisingRepository $advertisingRepository
     * @param ConverterService $converterService
     * @return JsonResponse
     * @throws \Exception
     *
     * @Route("/ads")
     */
    public function getAds(AdvertisingRepository $advertisingRepository, ConverterService $converterService)
    {
        $adsConverter = $converterService->get(Advertising::class);
        return $this->json($adsConverter->convertArray($advertisingRepository->findAll()));
    }

    /**
     * @Route("/settings")
     */
    public function getSettings(KernelInterface $kernel)
    {
        $path = $kernel->getProjectDir() . '/var/settings.json';
        $settings = json_decode(file_get_contents($path), true);
        $settings['cardInstructions'] = explode(PHP_EOL, str_replace("\r", '', $settings['cardInstructions']));
        $settings['phones'] = array_values($settings['phones']);

        return $this->json($settings);
    }

    /**
     * @Route("/place-an-order", methods={"POST"})
     */
    public function placeAnOrder(Request $request)
    {
        $fields = [
            'deliveryAddress',
            'houseNumber',
            'firstName',
            'lastName',
            'phoneNumber'
        ];

        $errors = [];
        $data = [];

        foreach ($fields as $field) {
            $value = $request->get($field, null);
            if ($value === null) {
                $errors[$field] = 'Поле не должно быть пустым';
                continue;
            }
            if (strlen($value) < 3 && $field !== 'houseNumber') {
                $errors[$field] = 'Значение должно быть более двух символов';
                continue;
            }

            $data[$field] = $value;
        }

        if (!empty($errors)) {
            return $this->json($errors, 400);
        }

        $products = $request->get('products');
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $apartment = $data['apartment'] ?? '';
        $houseNumber = $data['houseNumber'];
        $address = $data['deliveryAddress'];
        $phone = $data['phoneNumber'];
        $paymentMethod = $request->get('paymentMethod', '');

        $productsRepository = $this->getDoctrine()->getRepository(Product::class);
        $variantsRepository = $this->getDoctrine()->getRepository(ProductVariant::class);

        $entityManager = $this->getDoctrine()->getManager();

        $order = new Order();
        $order->setFirstName($firstName);
        $order->setLastName($lastName);
        $order->setAddress($address);
        $order->setApartment($apartment);
        $order->setHouseNumber($houseNumber);
        $order->setPaymentMethod($paymentMethod);
        $order->setPhoneNumber($phone);
        $order->setStatus('unprocessed');
        $entityManager->persist($order);

        $productsCache = [];

        foreach ($products as $item) {
            $orderedProduct = new OrderedProduct();
            $product = array_key_exists($item['id'], $productsCache) ? $productsCache[$item['id']] : $productsRepository->find($item['id']);
            $orderedProduct->setProduct($product);
            $orderedProduct->setQuantity($item['quantity']);
            if ($item['sizeId'] !== null) {
                $orderedProduct->setVariant($variantsRepository->find($item['sizeId']));
            } else {
                $orderedProduct->setVariant(null);
            }

            $orderedProduct->calculatePrice();
            $orderedProduct->setOrder($order);
//            $order->addProduct($orderedProduct);
            $entityManager->persist($orderedProduct);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Заказ успешно оформлен'
        ]);
    }

}
