<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Magasinier;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, MagasinierRepository $magasinierRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findBy(["magasin" => $magasinierRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()])->getMagasin()]),
            'voir' => 'oui',
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MagasinierRepository $magasinierRepository, MagasinRepository $magasinRepository, GestionnaireRepository $gestionnaireRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setDate(new DateTime());
            $produit->setUser($this->getUser());
            $mail = $this->getUser();
            $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $magasinierRepository->findOneBy(["email" => $mail->getUserIdentifier()])->getGestionnaire()->getEmail() ]);
            // dd($magasinierRepository->findOneBy(["email" => $mail])->getMagasin());
            
            $produit->setMagasin($magasinierRepository->findOneBy(["email" => $mail->getUserIdentifier()])->getMagasin());
            $magasins = $magasinRepository->findBy(["gestionnaire" => $gestionnaire]);
            $produits[] = $produit;
            // dd($magasins);
            foreach ($magasins as $m){
                if($m->getId() != $produit->getMagasin()->getId()){
                    $p = new Produit();
                    $p->setNom($produit->getNom());
                    $p->setPrix($produit->getPrix());
                    $p->setCode($produit->getCode());
                    $p->setCategorie($produit->getCategorie());
                    $p->setSeuil($produit->getSeuil());
                    $p->setDate($produit->getDate());
                    $p->setQte(0);
                    $p->setMagasin($m);
                    $p->setUser($produit->getUser());
                    $produits[] = $p; 
                }
            }
            // dd($produits);
            foreach($produits as $p){
                $entityManager->persist($p);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}