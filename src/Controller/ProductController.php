<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/homme', name: 'product_homme')]
    public function homme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['category' => 'homme']);
        return $this->render('product/category.html.twig', [
            'category' => 'Homme',
            'products' => $products,
        ]);
    }

    #[Route('/femme', name: 'product_femme')]
    public function femme(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['category' => 'femme']);
        return $this->render('product/category.html.twig', [
            'category' => 'Femme',
            'products' => $products,
        ]);
    }

    #[Route('/enfants-bebes', name: 'product_enfants_bebes')]
    public function enfantsBebes(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['category' => ['enfant', 'bebe']]);
        return $this->render('product/category.html.twig', [
            'category' => 'Enfants et Bébés',
            'products' => $products,
        ]);
    }
}
