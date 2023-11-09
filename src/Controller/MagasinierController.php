<?php

namespace App\Controller;

use App\Entity\Magasinier;
use App\Entity\User;
use App\Form\MagasinierType;
use App\Repository\MagasinierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/magasinier')]
class MagasinierController extends AbstractController
{
    #[Route('/', name: 'app_magasinier_index', methods: ['GET'])]
    public function index(MagasinierRepository $magasinierRepository,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        return $this->render('magasinier/index.html.twig', [
            'magasiniers' => $magasinierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_magasinier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $magasinier = new Magasinier();
        $form = $this->createForm(MagasinierType::class, $magasinier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $roles[] = "ROLE_MAGASINIER";
            $user->setEmail($magasinier->getEmail());
            $user->setRoles($roles);
            $magasinier->setPassword($user->getPassword());
            $entityManager->persist($magasinier);
            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success","Magasinier ajouté avec succès!");
                return $this->redirectToRoute('app_magasinier_index', [], Response::HTTP_SEE_OTHER);

            } catch (\Throwable $th) {
                $this->addFlash("error", "L'email existe deja");
            }
            

        }

        return $this->render('magasinier/new.html.twig', [
            'magasinier' => $magasinier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasinier_show', methods: ['GET'])]
    public function show(Magasinier $magasinier): Response
    {
        return $this->render('magasinier/show.html.twig', [
            'magasinier' => $magasinier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_magasinier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Magasinier $magasinier, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(MagasinierType::class, $magasinier);
        $form->handleRequest($request);

        $user = $userRepository->findOneby(["email" => $magasinier->getEmail()]);
        if ($form->isSubmitted() && $form->isValid()) {
            // $user = new User();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // $roles[] = "ROLE_GESTIONNAIRE";
            $user->setEmail($magasinier->getEmail());
            $user->setPassword($magasinier->getPassword());
            // $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_magasinier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('magasinier/edit.html.twig', [
            'magasinier' => $magasinier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasinier_delete', methods: ['POST'])]
    public function delete(Request $request, Magasinier $magasinier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$magasinier->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($magasinier);
                $entityManager->flush();
                $this->addFlash("success","Magasinier supprimé avec succès!");
                
            } catch (\Throwable $th) {
                $this->addFlash("error","Impossible de supprimer ce magasinier, un magasin lui est attribué!");
            }
        }

        return $this->redirectToRoute('app_magasinier_index', [], Response::HTTP_SEE_OTHER);
    }
}