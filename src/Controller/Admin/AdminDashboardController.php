<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $url = $this->adminUrlGenerator
            ->setController(ProductController::class)
            ->generateUrl();

        return $this->redirect($url);


    }

    #[Route('/admin/logs', name: 'admin_logs')]
    public function logs(): Response
    {
        
        $logs = [
            [
                'date' => new \DateTime('2024-11-11 10:00:00'),
                'level' => 'INFO',
                'message' => 'Utilisateur connecté: admin@example.com'
            ],
            [
                'date' => new \DateTime('2024-11-11 10:15:30'),
                'level' => 'WARNING',
                'message' => 'Tentative de connexion échouée: user123@example.com'
            ],
            [
                'date' => new \DateTime('2024-11-11 11:05:45'),
                'level' => 'ERROR',
                'message' => 'Erreur de paiement: Commande #1234'
            ],
            [
                'date' => new \DateTime('2024-11-11 12:30:00'),
                'level' => 'INFO',
                'message' => 'Nouvelle commande créée: #1235'
            ],
            [
                'date' => new \DateTime('2024-11-11 14:20:15'),
                'level' => 'DEBUG',
                'message' => 'Mise à jour du stock: Produit ID 56 - Nouveau stock: 25'
            ]
        ];

        return $this->render('admin/dashboard/logs.html.twig', [
            'logs' => $logs,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('belle éclat')
            ->setFaviconPath('favicon.svg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('E-commerce');
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

        yield MenuItem::section('Site Web');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-arrow-left', 'front_home_index');

        yield MenuItem::section('Paramètres');
        yield MenuItem::subMenu('Configuration', 'fas fa-cog')
            ->setSubItems([
                MenuItem::linkToRoute('Logs', 'fas fa-file-alt', 'admin_logs'),
              
            ]);
    }
} 