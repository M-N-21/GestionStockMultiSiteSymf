<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, MagasinierRepository $magasinierRepository): Response
    {
        $user = $this->getUser();
        $magasinier = $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findBy(["magasinier" => $magasinier]),
            'voir' => 'oui',
        ]);
    }
    #[Route('/gestionnaire', name: 'app_commande_gestionnaire_index', methods: ['GET'])]
    public function commandegestionnaire(CommandeRepository $commandeRepository, MagasinierRepository $magasinierRepository, GestionnaireRepository $gestionnaireRepository): Response
    {
        $user = $this->getUser();
        $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        return $this->render('commande/indexgestionnaire.html.twig', [
            'commandes' => $commandeRepository->findBy(["magasinier" => $magasinierRepository->findBy(["gestionnaire" => $gestionnaire])]),
            'voir' => 'oui',
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MagasinierRepository $magasinierRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $magasinier = $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
            $commande->setDate(new \DateTime('now', new \DateTimeZone('UTC')));
            $commande->setEtat(false);
            $commande->setMagasinier($magasinier);
            $entityManager->persist($commande);
            
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/valider', name: 'app_commande_valider', methods: ['GET'])]
    public function valider(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($commande != null) {
            $commande->setEtat(true);
            $entityManager->persist($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_gestionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/annuler', name: 'app_commande_annuler', methods: ['GET'])]
    public function annuler(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($commande != null) {
            $commande->setEtat(false);
            $entityManager->persist($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_gestionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
}