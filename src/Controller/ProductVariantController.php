<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Form\ProductVariantType;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/products/{product_id}/variant")
 */
class ProductVariantController extends AbstractController
{

    /**
     * @Route("/", name="product_variant_index", methods={"GET"})
     */
    public function index(Request $request, ProductVariantRepository $productVariantRepository, ProductRepository $productRepository): Response
    {
        $productId = $request->get('product_id');
        if (!is_numeric($productId)) {
            throw new \Exception("Bad argument");
        }
        $product = $productRepository->find($productId);

        return $this->render('product_variant/index.html.twig', [
            'product_variants' => $product->getVariants(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/new", name="product_variant_new", methods={"GET","POST"})
     */
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $productId = $request->get('product_id');
        if (!is_numeric($productId)) {
            throw new \Exception("Bad argument");
        }
        $product = $productRepository->find($productId);

        $productVariant = new ProductVariant();
        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productVariant->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productVariant);
            $entityManager->flush();

            return $this->redirectToRoute('product_variant_index', [ 'product_id' => $product->getId() ]);
        }

        return $this->render('product_variant/new.html.twig', [
            'product_variant' => $productVariant,
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/{id}", name="product_variant_delete", methods={"GET"})
     */
    public function delete(Request $request, ProductVariant $productVariant, ProductRepository $productRepository): Response
    {
        $productId = $request->get('product_id');
        if (!is_numeric($productId)) {
            throw new \Exception("Bad argument");
        }
        $product = $productRepository->find($productId);

        $product->removeVariant($productVariant);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('product_variant_index', [ 'product_id' => $product->getId() ]);
    }

    /**
     * @Route("/{id}/edit", name="product_variant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductVariant $productVariant, ProductRepository $productRepository): Response
    {
        $productId = $request->get('product_id');
        if (!is_numeric($productId)) {
            throw new \Exception("Bad argument");
        }
        $product = $productRepository->find($productId);

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_variant_index', [ 'product_id' => $product->getId() ]);
        }

        return $this->render('product_variant/edit.html.twig', [
            'product_variant' => $productVariant,
            'form' => $form->createView(),
            'product' => $product
        ]);
    }

}
