<?php

namespace App\Controller;

use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(GestionnaireRepository $gestionnaireRepository, MagasinierRepository $magasinierRepository): Response
    {
        $user = $this->getUser();
        $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        $magasinier = $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        // dd($gestionnaire, $magasinier);
        if ($gestionnaire != null){
            return $this->render('home/index.html.twig', [
                'voir' => 'oui',
            ]);
        }else{
            if ($magasinier != null) {
                // dd($magasinier->getMagasin());
                if ($magasinier->getMagasin() == null){
                    $this->addFlash('warning', 'Aucun magasin ne vous a été attribué veuillez contacter votre gestionnaire');
                    return $this->render('home/index.html.twig', [
                        'voir' => 'non',
                    ]);
                }else{
                    return $this->render('home/index.html.twig', [
                        'voir' => 'oui',
                    ]);
                }
            }else{
                return $this->render('home/index.html.twig', [
                    'voir' => 'non',
                ]);
            }
        }
        
    }
}