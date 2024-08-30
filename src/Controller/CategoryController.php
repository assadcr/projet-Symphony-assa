<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/hommes', name: 'category_hommes')]
    public function hommes(): Response
    {
    return $this->render('category/hommes.html.twig');
    }

    #[Route('/category/femmes', name: 'category_femmes')]
    public function femmes(): Response
    {
    return $this->render('category/femmes.html.twig');
    }

    #[Route('/category/enfants-bebes', name: 'category_enfants_bebes')]
    public function enfantsBebes(): Response
    {
        return $this->render('category/enfants_bebes.html.twig');
    }
   
}
// Ajoutez des méthodes similaires pour Enfants, Bébés, et Coffrets
