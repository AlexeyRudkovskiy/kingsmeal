<?php

namespace App\Controller;

use App\Contracts\WithUpladableFile;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Traits\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/categories")
 */
class CategoryController extends AbstractController implements WithUpladableFile
{

    use UploadFile;

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

    public function getFileFieldName()
    {
        return 'imageFilename';
    }

    /**
     * @param Category $object
     * @return string
     */
    public function getCurrentLocation($object)
    {
        return $object->getImageFilename();
    }

    /**
     * @param string $path
     * @param Category $object
     */
    public function updateCurrentLocation(string $path, $object)
    {
        $object->setImageFilename($path);
    }

    public function getEntityTypeIdentifier(): string
    {
        return Category::class;
    }

}
