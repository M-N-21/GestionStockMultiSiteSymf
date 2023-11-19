<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use App\Repository\MagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository, MagasinierRepository $magasinierRepository): Response
    {
        $user = $this->getUser();
        $magasinier = $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findBy(["Magasin" => $magasinier->getMagasin()]),
            'voir' => 'oui',
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MagasinierRepository $magasinierRepository, GestionnaireRepository $gestionnaireRepository, MagasinRepository $magasinRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $gestionnaire = $gestionnaireRepository->findOneBy(["email" => $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()])->getGestionnaire()->getEmail() ]);
            $magasinier = $magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
            $magasins = $magasinRepository->findBy(["gestionnaire" => $gestionnaire]);
            $categorie->setMagasin($magasinier->getMagasin());
            $categories [] = $categorie;
            foreach ($magasins as $m){
                if($m->getId() != $categorie->getMagasin()->getId()){
                    $c = new Categorie();
                    $c->setMagasin($m);
                    $c->setNom($categorie->getNom());
                    $categories [] = $c;
                }
            }
            foreach($categories as $c){
                $entityManager->persist($c);
            }
            $entityManager->flush();
            $this->addFlash("success", "Categorie ajouté avec success!");
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}