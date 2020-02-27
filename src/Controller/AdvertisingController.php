<?php

namespace App\Controller;

use App\Contracts\WithUpladableFile;
use App\Entity\Advertising;
use App\Form\AdvertisingType;
use App\Repository\AdvertisingRepository;
use App\Traits\UploadFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/advertising")
 */
class AdvertisingController extends AbstractController implements WithUpladableFile
{

    use UploadFile;

    /**
     * @Route("/", name="advertising_index", methods={"GET"})
     */
    public function index(AdvertisingRepository $advertisingRepository): Response
    {
        return $this->render('advertising/index.html.twig', [
            'advertisings' => $advertisingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="advertising_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $advertising = new Advertising();
        $form = $this->createForm(AdvertisingType::class, $advertising);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($advertising, $form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($advertising);
            $entityManager->flush();

            return $this->redirectToRoute('advertising_index');
        }

        return $this->render('advertising/new.html.twig', [
            'advertising' => $advertising,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advertising_show", methods={"GET"})
     */
    public function show(Advertising $advertising): Response
    {
        return $this->render('advertising/show.html.twig', [
            'advertising' => $advertising,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="advertising_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Advertising $advertising): Response
    {
        $form = $this->createForm(AdvertisingType::class, $advertising);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadPhoto($advertising, $form);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advertising_index');
        }

        return $this->render('advertising/edit.html.twig', [
            'advertising' => $advertising,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advertising_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Advertising $advertising): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advertising->getId(), $request->request->get('_token'))) {
            $this->removeImageIfExists($advertising);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($advertising);
            $entityManager->flush();
        }

        return $this->redirectToRoute('advertising_index');
    }

    public function getFileFieldName()
    {
        return 'fileName';
    }

    /**
     * @param Advertising $object
     * @return string
     */
    public function getCurrentLocation($object)
    {
        return $object->getFileName();
    }

    /**
     * @param string $path
     * @param Advertising $object
     */
    public function updateCurrentLocation(string $path, $object)
    {
        $object->setFileName($path);
    }

    public function getEntityTypeIdentifier(): string
    {
        return Advertising::class;
    }

}
