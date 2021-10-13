<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {

            $cart->remove();

            $order->setIsPaid(1);
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstname() . "<br />Merci pour votre commande.<br /><br />Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aperiam consequuntur cupiditate dignissimos dolorum error facilis harum ipsum libero magnam nostrum porro, quidem sed sequi similique sit soluta voluptatem! Expedita?";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande sur La Boutique Française est bien validée.', $content);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
