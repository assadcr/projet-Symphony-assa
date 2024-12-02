<?php

namespace App\Controller\Front;

use App\Entity\Favoris;
use App\Repository\ProductRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'front_home_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ProductRepository $repository, Request $request): Response
    {
        $pagination = $repository->paginateProductsOrderByUpdatedAt($request->query->getInt('page', 1));

        $lastVisit = $request->cookies->get('last_visit', 'Première visite');

        return $this->render('front/home/index.html.twig', [
            'products' => $pagination,
            'last_visit' => $lastVisit,
        ]);

        $this->handleCookie($request, $response, 'last_visit', date('Y-m-d H:i:s'));
        return $response;
    }

    #[Route('/detail/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, ProductRepository $repository, FavorisRepository $favorisRepository): Response
    {
        $product = $repository->findProductWithCategoryBySlug($slug);
        
        $isFavorite = false;
        if ($this->getUser()) {
            $isFavorite = $favorisRepository->findOneBy([
                'user' => $this->getUser(),
                'product' => $product
            ]) !== null;
        }

        return $this->render('front/home/show.html.twig', [
            'product' => $product,
            'isFavorite' => $isFavorite
        ]);
    }

    #[Route('/conditions-du-site', name: 'terms_conditions')]
    public function terms(): Response
    {
        return $this->render('front/home/term.html.twig');
    }

    #[Route('/conditions-de-vente', name: 'conditions_vente')]
    public function vente(): Response
    {
        return $this->render('front/home/terms.html.twig');
    }

    #[Route('/femmes', name: 'femmes', methods: ['GET'])]
    public function femmes(ProductRepository $repository, Request $request): Response
    {
        $pagination = $repository->paginateProductsByPublicCible('femme', $request->query->getInt('page', 1));

        return $this->render('front/home/femmes.html.twig', [
            'products' => $pagination,
        ]);
    }

    #[Route('/ajouter-aux-favoris/{id}', name: 'add_to_favorites', methods: ['POST'])]
    public function addToFavorites(int $id, EntityManagerInterface $entityManager, ProductRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $product = $repository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $user = $this->getUser();
        $favoris = new Favoris();
        $favoris->setUser($user);
        $favoris->setProduct($product);

        $entityManager->persist($favoris);
        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté aux favoris');

        return $this->redirectToRoute('front_home_show', ['slug' => $product->getSlug()]);
    }

    #[Route('/retirer-des-favoris/{id}', name: 'remove_from_favorites', methods: ['POST'])]
    public function removeFromFavorites(int $id, EntityManagerInterface $entityManager, FavorisRepository $favorisRepository, ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $favoris = $favorisRepository->findOneBy([
            'user' => $this->getUser(),
            'product' => $product
        ]);

        if ($favoris) {
            $entityManager->remove($favoris);
            $entityManager->flush();
            $this->addFlash('success', 'Produit retiré des favoris');
        }

        return $this->redirectToRoute('front_home_show', ['slug' => $product->getSlug()]);
    }

    private function handleCookie(Request $request, Response $response, string $name, string $value, int $expireTime = 2592000): void
    {
        if (!$request->cookies->has($name)) {
            $cookie = new Cookie($name, $value, time() + $expireTime);
            $response->headers->setCookie($cookie);
        }
    }

    #[Route('/cookie-info', name: 'cookie_info', methods: ['GET'])]
    public function cookieInfo(Request $request): Response
    {
        $lastVisit = $request->cookies->get('last_visit', 'Première visite');

        return $this->render('front/home/cookie_info.html.twig', [
            'last_visit' => $lastVisit,
        ]);
    }

    #[Route('/supprimer-cookie', name: 'delete_cookie', methods: ['GET'])]
    public function deleteCookie(): Response
    {
        $response = $this->redirectToRoute('front_home_index');
        $response->headers->clearCookie('last_visit');
        return $response;
    }
    #[Route('/informations-legales', name: 'legal_informations')]
    public function legal(): Response
    {
        return $this->render('front/legal/legal.html.twig', [
            'company_name' => 'belle-eclat',
            'company_address' => '123 Rue du Parfum, 75000 Paris',
            'company_email' => 'contact@votreparfumerie.com',
            'company_phone' => '+33 1 23 45 67 89',
            'company_rcs' => 'RCS Paris B 123 456 789',
            'company_capital' => '100 000 €',
            'website_url' => 'www.votreparfumerie.com',
            'host_name' => 'OVH',
            'host_address' => '2 rue Kellermann, 59100 Roubaix',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('front/contact/index.html.twig', [
            'company_name' => 'belle-eclat',
            'company_email' => 'contact@votreparfumerie.com',
            'company_phone' => '+33 1 23 45 67 89',
        ]);
    }

}