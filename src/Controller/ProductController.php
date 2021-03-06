<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/products")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->getPaginated($paginator, $request),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($product, $form);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($product, $form);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_show', [ 'id' => $product->getId() ]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /** todo: move this code to service */
    /**
     * @param Product $product
     * @param \Symfony\Component\Form\FormInterface $form
     */
    public function uploadPhoto(Product $product, \Symfony\Component\Form\FormInterface $form): void
    {
        /** @var UploadedFile $previewFile */
        $previewFile = $form['photoFilename']->getData();

        if ($previewFile !== null) {
            $photoFilename = $product->getPhotoFilename();
            $directory = $this->getParameter('previews_directory');
            $absoluteFilePath = $directory . '/' . $photoFilename;

            if (is_file($absoluteFilePath)) {
                unlink($absoluteFilePath);
            }

            $originalName = pathinfo($previewFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = sha1($originalName) . '-' . uniqid() . '.' . $previewFile->guessExtension();

            $previewFile->move($directory, $newFilename);

            $product->setPhotoFilename($newFilename);
        }
    }
}
