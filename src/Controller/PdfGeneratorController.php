<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Sortie;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator/entree/{id}', name: 'app_pdf_generator')]
    public function index(Entree $entree=null): Response
    {
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);
        $data = [
            // 'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/img/profile.png'),
            'entree'         => $entree
        ];
        $html =  $this->renderView('pdf_generator/entree.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
    #[Route('/pdf/generator/sortie/{id}', name: 'app_pdf_generator_sortie')]
    public function sortie(Sortie $sortie=null): Response
    {
        // return $this->render('pdf_generator/index.html.twig', [
        //     'controller_name' => 'PdfGeneratorController',
        // ]);
        $data = [
            // 'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/img/profile.png'),
            'sortie'         => $sortie
        ];
        $html =  $this->renderView('pdf_generator/sortie.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
    #[Route('/pdf/generator/stock', name: 'app_pdf_generator_stock')]
    public function stock(MagasinRepository $magasinRepository, ProduitRepository $produitRepository, GestionnaireRepository $gestionnaireRepository): Response
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
        $data = [
            // 'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/img/profile.png'),
            'stock'         => $stock,
            'magasins' => $magasins,
        ];
        $html =  $this->renderView('pdf_generator/stock.html.twig', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
         
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}