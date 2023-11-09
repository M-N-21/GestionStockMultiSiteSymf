<?php

namespace App\Controller;

use App\Entity\Gestionnaire;
use App\Entity\User;
use App\Form\GestionnaireType;
use App\Repository\GestionnaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestionnaire')]
class GestionnaireController extends AbstractController
{
    #[Route('/', name: 'app_gestionnaire_index', methods: ['GET'])]
    public function index(GestionnaireRepository $gestionnaireRepository): Response
    {
        return $this->render('gestionnaire/index.html.twig', [
            'gestionnaires' => $gestionnaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gestionnaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $gestionnaire = new Gestionnaire();
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $roles[] = "ROLE_GESTIONNAIRE";
            $user->setEmail($gestionnaire->getEmail());
            $user->setRoles($roles);
            $gestionnaire->setPassword($user->getPassword());
            try {
                $entityManager->persist($gestionnaire);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success","Gestionnaire ajouté avec succès!");
                return $this->redirectToRoute('app_gestionnaire_index', [], Response::HTTP_SEE_OTHER);

            } catch (\Throwable $th) {
                //throw $th;
                $this->addFlash("error","Email existe deja!");
            }
                    }

        return $this->render('gestionnaire/new.html.twig', [
            'gestionnaire' => $gestionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestionnaire_show', methods: ['GET'])]
    public function show(Gestionnaire $gestionnaire): Response
    {
        return $this->render('gestionnaire/show.html.twig', [
            'gestionnaire' => $gestionnaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestionnaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gestionnaire $gestionnaire, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);
        
        $user = $userRepository->findOneby(["email" => $gestionnaire->getEmail()]);
        if ($form->isSubmitted() && $form->isValid()) {
            // $user = new User();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            // $roles[] = "ROLE_GESTIONNAIRE";
            $user->setEmail($gestionnaire->getEmail());
            $gestionnaire->setPassword($user->getPassword());
            // $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_gestionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestionnaire/edit.html.twig', [
            'gestionnaire' => $gestionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestionnaire_delete', methods: ['POST'])]
    public function delete(Request $request, Gestionnaire $gestionnaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gestionnaire->getId(), $request->request->get('_token'))) {
            
            try {
                $entityManager->remove($gestionnaire);
                $entityManager->flush();
                $this->addFlash("success","Gestionnaire supprimé succès!");
                return $this->redirectToRoute('app_gestionnaire_index', [], Response::HTTP_SEE_OTHER);

            } catch (\Throwable $th) {
                $this->addFlash("error","Impossible de supprimé ce Gestionnaire!");
            }
        }

    }
}