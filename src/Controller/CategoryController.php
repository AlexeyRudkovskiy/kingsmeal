<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($category, $form);

            $entityManager = $this->getDoctrine()->getManager();

            $products = $form['products']->getData();

            /** @var Product $product */
            foreach ($products as $product) {
                $category->addProduct($product);
                $product->addCategory($category);
                $entityManager->persist($product);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($category, $form);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->removeImageIfExists($category);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /** todo: move this code to service */
    /**
     * @param Category $category
     * @param \Symfony\Component\Form\FormInterface $form
     */
    public function uploadPhoto(Category $category, \Symfony\Component\Form\FormInterface $form): void
    {
        /** @var UploadedFile $previewFile */
        $previewFile = $form['imageFilename']->getData();

        if ($previewFile !== null) {
            $directory = $this->getParameter('previews_directory');
            $this->removeImageIfExists($category);
            $originalName = pathinfo($previewFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = sha1($originalName) . '-' . uniqid() . '.' . $previewFile->guessExtension();

            $previewFile->move($directory, $newFilename);

            $category->setImageFilename($newFilename);
        }
    }

    /**
     * @param Category $category
     */
    private function removeImageIfExists(Category $category) {
        $photoFilename = $category->getImageFilename();
        $directory = $this->getParameter('previews_directory');
        $absoluteFilePath = $directory . '/' . $photoFilename;

        if (is_file($absoluteFilePath)) {
            unlink($absoluteFilePath);
        }
    }

}
