<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\ClientOrderType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientOrderController extends AbstractController
{
    #[Route('/commande/creer', name: 'front_client_order')]
    public function createOrder(Request $request, EntityManagerInterface $entityManager, CartService $cartService): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            // Rediriger vers la page de connexion si non authentifié
            return $this->redirectToRoute('app_login');
        }

        $cartContent = $cartService->getCartContent();
        
        if (empty($cartContent['dataCart'])) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('front_cart_index');
        }

        $order = new Order();
        $order->setUser($this->getUser());
        $form = $this->createForm(ClientOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Remplir l'ordre avec les données du panier
            foreach ($cartContent['dataCart'] as $item) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($item['product']);
                $orderItem->setQuantity($item['quantity']);
                $orderItem->setPrice($item['product']->getPrice());
                $order->addItem($orderItem);
            }

            $order->setStatus('pending');
            $order->calculateTotalAmount();

            $entityManager->persist($order);
            $entityManager->flush();

            // Vider le panier
            $cartService->emptyCart();

            // Rediriger vers le processus de paiement avec l'ID de la commande
            return $this->redirectToRoute('front_payment_process', ['id' => $order->getId()]);
        }

        return $this->render('front/client_order/create.html.twig', [
            'form' => $form->createView(),
            'cartContent' => $cartContent,
        ]);
    }
}