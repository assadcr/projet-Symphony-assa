<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/legal', name: 'app_legal')]
    public function index(): Response
    {
        return $this->render('legal/index.html.twig', [
            'controller_name' => 'LegalController',
        ]);
    }
       #[Route('/legal/terms', name: 'legal_terms')]
        public function terms(): Response
     {
    return $this->render('legal/terms.html.twig');
     } 

    #[Route('/legal/privacy', name: 'legal_privacy')]
    public function privacy(): Response
   {
    return $this->render('legal/privacy.html.twig');
    }

}
