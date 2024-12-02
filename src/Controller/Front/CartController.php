<?php

namespace App\Controller\Front;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/panier', name: 'front_cart_')]
class CartController extends AbstractController
{
    #[Route('/front/cart', name: 'index', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        $data = $cartService->getCartContent();

        return $this->render('front/cart/index.html.twig', [
            'dataCart' => $data['dataCart'],
            'total' => $data['total']
        ]);
    }

    #[Route('/ajouter/{id}', name: 'add', methods: ['GET','POST'], requirements: ['id' => Requirement::DIGITS])]
    public function add(int $id, CartService $cartService): Response
    {
        $cartService->incrementProductQuantity($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/diminuer/{id}', name: 'decrease', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function decrease(int $id, CartService $cartService): Response
    {
        $cartService->decreaseProductQuantity($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/supprimer/{id}', name: 'remove', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function remove($id, CartService $cartService): Response
    {
        $cartService->removeProductFromCart($id);

        return $this->redirectToRoute('front_cart_index');
    }

    #[Route('/vider', name: 'empty', methods: ['GET'])]
    public function empty(CartService $cartService): Response
    {
        $cartService->emptyCart();

        return $this->redirectToRoute('front_cart_index');
    }
    
}
