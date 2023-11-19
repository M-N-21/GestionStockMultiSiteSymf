<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Sortie;
use App\Entity\TransfertStock;
use App\Form\TransfertStockType;
use App\Repository\EntreeRepository;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Repository\SortieRepository;
use App\Repository\TransfertStockRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/transfert/stock')]
class TransfertStockController extends AbstractController
{
    #[Route('/', name: 'app_transfert_stock_index', methods: ['GET'])]
    public function index(TransfertStockRepository $transfertStockRepository, GestionnaireRepository $gestionnaireRepository): Response
    {
        $user = $this->getUser();
        $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        $transfertStocks = $transfertStockRepository->findBy(["gestionnaire" => $gestionnaire]);
        return $this->render('transfert_stock/index.html.twig', [
            'transfert_stocks' => $transfertStocks,
        ]);
    }

    #[Route('/new', name: 'app_transfert_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GestionnaireRepository $gestionnaireRepository, ProduitRepository $produitRepository, MagasinRepository $magasinRepository, EntreeRepository $entreeRepository, SortieRepository $sortieRepository): Response
    {
        $transfertStock = new TransfertStock();
        $form = $this->createForm(TransfertStockType::class, $transfertStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
            if ($transfertStock->getMagasinDestination()->getId() == $transfertStock->getMagasinOrigine()->getId()) {
                $this->addFlash("error","Le magasin d'origine et de destination ne peut etre identique!");
            }else{
                $produits = $produitRepository->findBy(["magasin" => $transfertStock->getMagasinOrigine()]);
                $produito = null;
                $produitd = null;
                foreach ($produits as $p) {
                    if($p->getCode() == $transfertStock->getProduit()->getCode()) {
                        $produito = $p;
                        break;
                    }
                }
                $produits = $produitRepository->findBy(["magasin" => $transfertStock->getMagasinDestination()]);
                foreach ($produits as $p) {
                    if($p->getCode() == $transfertStock->getProduit()->getCode()) {
                        $produitd = $p;
                        break;
                    }
                }
                // dd($produito,$produitd);
                if($produito != null && $produitd != null){
                    if ($produito->getQte() > $transfertStock->getQte()) {
                        $transfertStock->setGestionnaire($gestionnaire);
                        $transfertStock->setDate(new \DateTime('now', new \DateTimeZone('UTC')));
                        $entityManager->persist($transfertStock);
                        // $origine = $transfertStock->getMagasinOrigine();
                        // $destinatio = $transfertStock->getMagasinDestination();
                        $entree = new Entree();
                        $sortie = new Sortie();
                        $entree->setDate(new DateTime("now", new DateTimeZone("UTC")));
                        $entree->setqteEntree($transfertStock->getQte());
                        // $lastentree= null;
                        $lastentree= $entreeRepository->findOneBy([],['id' => 'DESC']);
                        // dd($lastentree);
                        $entree->setNumBe($lastentree->getNumBe()+1);
                        $entree->setProduit($produitd);
                        $entree->setTransfert(true);
                        $sortie->setDate(new DateTime("now", new DateTimeZone("UTC")));
                        $sortie->setQteSortie($transfertStock->getQte());
                        $lastsortie= $sortieRepository->findOneBy([],['id' => 'DESC']);
                        $sortie->setNumBs($lastsortie->getNumBs()+1);
                        $sortie->setProduit($produito);
                        $sortie->setTransfert(true);
                        $produitd->setQte($produitd->getQte()+$transfertStock->getQte());
                        $produito->setQte($produito->getQte()-$transfertStock->getQte());
                        $entityManager->persist($entree);
                        $entityManager->persist($sortie);
                        $entityManager->persist($produitd);
                        $entityManager->persist($produito);
                        
                        $entityManager->flush();
                        $this->addFlash("success","Transfert effectué avec succès!");
                        $this->addFlash("success","Le stock du produit ".$transfertStock->getProduit()." a été mis à jour dans les magasins ".$transfertStock->getMagasinOrigine()." et ".$transfertStock->getMagasinDestination());
                        return $this->redirectToRoute('app_transfert_stock_index', [], Response::HTTP_SEE_OTHER);
                    }else{
                        $this->addFlash("error","La quantité saisie n'est pas disponible dans le magasin d'origine pour le produit selectionné!");
                    }
                    

                }
            }
        }

        return $this->render('transfert_stock/new.html.twig', [
            'transfert_stock' => $transfertStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transfert_stock_show', methods: ['GET'])]
    public function show(TransfertStock $transfertStock): Response
    {
        return $this->render('transfert_stock/show.html.twig', [
            'transfert_stock' => $transfertStock,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transfert_stock_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TransfertStock $transfertStock, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransfertStockType::class, $transfertStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transfert_stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transfert_stock/edit.html.twig', [
            'transfert_stock' => $transfertStock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transfert_stock_delete', methods: ['POST'])]
    public function delete(Request $request, TransfertStock $transfertStock, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfertStock->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transfertStock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transfert_stock_index', [], Response::HTTP_SEE_OTHER);
    }
}