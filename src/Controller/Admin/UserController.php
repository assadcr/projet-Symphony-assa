<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreateAdminType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateur', name: 'admin_user_')]
class UserController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(UserRepository $repository, Request $request)
    {
        $users =  $repository->paginateUsersWithRoleUser($request->query->getInt('page', 1));

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/detail/{id}', name: 'show', methods: ['GET'])]
    public function show(?User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/creer-admin', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);
            $user->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'bravo');
            return $this->redirectToRoute('admin_dashboard_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/modifier/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, UserPasswordHasherInterface $userPasswordhasher, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            if ($password) {
                $user->setPassword($userPasswordhasher->hashPassword($user, $password));
            }

            $em->flush();

            $this->addFlash('success', 'Vous avez mis à jour un utilisateur');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form
        ]);
    }
}
