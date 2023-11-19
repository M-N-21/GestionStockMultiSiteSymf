<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Form\MagasinType;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/magasin')]
class MagasinController extends AbstractController
{
    #[Route('/', name: 'app_magasin_index', methods: ['GET'])]
    public function index(MagasinRepository $magasinRepository, GestionnaireRepository $gestionnaireRepository): Response
    {
        $user = $this->getUser();
        $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasinRepository->findBy(["gestionnaire" => $gestionnaire]),
        ]);
    }

    #[Route('/new', name: 'app_magasin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GestionnaireRepository $gestionnaireRepository): Response
    {
        $magasin = new Magasin();
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->getUser();
                $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
                $entityManager->persist($magasin);
                $magasin->setGestionnaire($gestionnaire);
                $entityManager->flush();
                $this->addFlash("success", "Le magasin a bien été créé et assigné à ".$magasin->getMagasinier()->getPrenom(). " ". $magasin->getMagasinier()->getNom());
                return $this->redirectToRoute('app_magasin_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Throwable $th) {
                $this->addFlash("error", "Le magasinier ".$magasin->getMagasinier()->getPrenom(). " ". $magasin->getMagasinier()->getNom(). " a déjà été assigné à un magasin");
            }
            
        }

        return $this->render('magasin/new.html.twig', [
            'magasin' => $magasin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasin_show', methods: ['GET'])]
    public function show(Magasin $magasin): Response
    {
        return $this->render('magasin/show.html.twig', [
            'magasin' => $magasin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_magasin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Magasin $magasin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                return $this->redirectToRoute('app_magasin_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Throwable $th) {
                // $prenom = $magasin->getMagasinier()->getPrenom();
                // $nom = $magasin->getMagasinier()->getNom();
                $this->addFlash("error", "Le magasinier ".$magasin->getMagasinier()->getPrenom(). " ". $magasin->getMagasinier()->getNom(). " a déjà été assigné à un magasin");
            }
        }

        return $this->render('magasin/edit.html.twig', [
            'magasin' => $magasin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasin_delete', methods: ['POST'])]
    public function delete(Request $request, Magasin $magasin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$magasin->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($magasin);
                $entityManager->flush();
                $this->addFlash("error", "Magasin supprimé avec succès!");
                
            } catch (\Throwable $th) {
                $this->addFlash("error", "Impossible de supprimer ce magasin");
            }
        }

        return $this->redirectToRoute('app_magasin_index', [], Response::HTTP_SEE_OTHER);
    }
}