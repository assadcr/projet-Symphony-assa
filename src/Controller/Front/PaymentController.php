<?php

namespace App\Controller\Front;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/process/{id}', name: 'front_payment_process', requirements: ['id' => '\d+'])]
    public function process(Order $order): Response
    {
        // Configurez la clé secrète de Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        set_time_limit(300);

        // Créer une session de paiement
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Commande #' . $order->getOrderNumber(),
                    ],
                    'unit_amount' => $order->getTotalAmount() * 100, // Montant en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('front_payment_success', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('front_payment_cancel', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($session->url, 303);
    }

    #[Route('/success/{id}', name: 'front_payment_success')]
    public function success(Order $order): Response
    {
        // Mettre à jour l'état de la commande
        $order->setStatus('paid');
        $this->entityManager->flush();

        return $this->render('front/payment/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/cancel/{id}', name: 'front_payment_cancel')]
    public function cancel(Order $order): Response
    {
        return $this->render('front/payment/cancel.html.twig', [
            'order' => $order,
        ]);
    }
}