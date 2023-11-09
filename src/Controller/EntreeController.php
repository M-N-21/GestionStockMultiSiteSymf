<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Form\EntreeType;
use App\Repository\EntreeRepository;
use App\Repository\MagasinierRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entree')]
class EntreeController extends AbstractController
{
    #[Route('/', name: 'app_entree_index', methods: ['GET'])]
    public function index(EntreeRepository $entreeRepository, MagasinierRepository $magasinierRepository,ProduitRepository $produitRepository): Response
    {
        // $user = $this->getUser();
        $entrees = [];
        $produits = $produitRepository->findBy(["magasin" => $magasinierRepository->findOneBy(["email" => $this->getUser()->getEmail()])->getMagasin()]);
        $listeentrees = $entreeRepository->findAll();
        foreach ($listeentrees as $e) {
            foreach ($produits as $p) {
                if ($e->getProduit()->getId() == $p->getId()) {
                    $entrees[] = $e;
                }
            }
        }
        return $this->render('entree/index.html.twig', [
            'entrees' => $entrees,

        ]);
    }

    #[Route('/new', name: 'app_entree_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entree->setTransfert(false);
            $entree->setDate(new DateTime());
            try {
                $entityManager->persist($entree);
                $produit = $produitRepository->find($entree->getProduit()->getId());
                $produit->setQte($produit->getQte()+$entree->getqteEntree());
                $entityManager->persist($produit);
                $entityManager->flush(); 
                $this->addFlash("success","Entree effectuée avec succès!");
                $this->addFlash("success","Le stock du produit ".$produit." a été mis à jour!");
                return $this->redirectToRoute('app_entree_index', [], Response::HTTP_SEE_OTHER);

            } catch (\Throwable $th) {
                $this->addFlash("error","Impossible d'effectuer l'Entree!");
                //throw $th;
            }
        }

        return $this->render('entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entree_show', methods: ['GET'])]
    public function show(Entree $entree): Response
    {
        return $this->render('entree/show.html.twig', [
            'entree' => $entree,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entree_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entree $entree, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $lastqte = $entree->getqteEntree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $produitRepository->find($entree->getProduit()->getId());
            if ($lastqte>$entree->getqteEntree()) {
                $produit->setQte($produit->getQte()-($lastqte-$entree->getqteEntree()));
            }else{
                $produit->setQte($produit->getQte()+($entree->getqteEntree()-$lastqte));
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_entree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entree/edit.html.twig', [
            'entree' => $entree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entree_delete', methods: ['POST'])]
    public function delete(Request $request, Entree $entree, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entree->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entree);
            $entityManager->flush();
            $this->addFlash("success","Sortie supprimée avec succès!");
        }

        return $this->redirectToRoute('app_entree_index', [], Response::HTTP_SEE_OTHER);
    }
}