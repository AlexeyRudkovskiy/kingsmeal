<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findWithStatus($paginator, $request),
        ]);
    }

    /**
     * @Route("/unprocessed", name="order_unprocessed", methods={"GET"})
     */
    public function newOrders(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findWithStatus($paginator, $request, 'unprocessed')
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     *
     * @Route("/{id}", name="order_update", methods={"POST"})
     * @param Order $order
     * @return Response
     */
    public function update(Order $order): Response
    {
        $order->setStatus('done');

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('order_show', [ 'id' => $order->getId() ]);
    }

}
