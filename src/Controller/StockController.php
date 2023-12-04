<?php

namespace App\Controller;

use App\Repository\GestionnaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/stock', name: 'app_stock')]
    public function index(MagasinRepository $magasinRepository, ProduitRepository $produitRepository, GestionnaireRepository $gestionnaireRepository): Response
    {
        $user = $this->getUser();
        $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        $magasins = $magasinRepository->findBy(["gestionnaire" => $gestionnaire]);
        $produits = $produitRepository->findBy(["magasin" => $magasins[0]]);
        $stock = [];
        // dd($produits);
        foreach ($magasins as $m) {
            foreach ($produits as $p){
                if ($p->getMagasin()->getId() == $m->getId()) {
                    $stock[] = [
                        "code" => $p->getCode(),
                        "qte" => $p->getQte(),
                        "magasinid" => $p->getMagasin()->getId(),
                    ];
                }else{
                    $produit = $produitRepository->findOneBy(["code" => $p->getCode()]);
                    $stock[] = [
                        "code" => $produit->getCode(),
                        "qte" => $produit->getQte(),
                        "magasinid" => $produit->getMagasin()->getId(),
                    ];
                }
            }
        }
        // dd($stock);
        return $this->render('stock/index.html.twig', [
            'magasins' => $magasins,
            "stock" => $stock,
        ]);
    }
}