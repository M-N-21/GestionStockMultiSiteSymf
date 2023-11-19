<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\MagasinierRepository;
use App\Repository\ProduitRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository, MagasinierRepository $magasinierRepository,ProduitRepository $produitRepository): Response
    {
        $sorties = [];
        $produits = $produitRepository->findBy(["magasin" => $magasinierRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()])->getMagasin()]);
        $listesorties = $sortieRepository->findAll();
        foreach ($listesorties as $s) {
            foreach ($produits as $p) {
                if ($s->getProduit()->getId() == $p->getId()) {
                    $sorties[] = $s;
                }
            }
        }
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'voir' => 'oui',
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ProduitRepository $produitRepository, MailerInterface $mailer): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setTransfert(false);
            $sortie->setDate(new DateTime());
            try {
                $produit = $produitRepository->find($sortie->getProduit()->getId());
                $produit->setQte($produit->getQte()-$sortie->getQteSortie());
                if ($produit->getQte()>=0) {
                    $entityManager->persist($produit);
                    $entityManager->persist($sortie);
                    
                    $entityManager->flush();
                    $this->addFlash("success","Sortie effectuée avec succès!");
                    $this->addFlash("success","Le stock du produit ".$produit." a été mis à jour!");
                    
                    // Envoi d'un e-mail
                    // $email = (new Email())
                    // ->from('modyndiaye416@gmail.com')
                    // ->to('endiayeisidk@groupeisi.com')
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    // ->subject('Alert!')
                    // ->text('Seuil du produit!')
                    // ->html('<p>See Twig integration for better HTML integration!</p>');
                    // try {
                    //     $mailer->send($email);
                    // } catch (\Throwable $th) {
                    //     dd("errer");
                    // }
                    if ($produit->getQte() <= $produit->getSeuil()) {
                        $this->addFlash("warning","Le seuil du produit".$produit." a été atteint ou dépassé!\nVeuillez faire une commande!");
                    }
                    return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);    
                }else{
                    $this->addFlash("error","Le Stock disponible pour ce produit est inférieur à la quantite de la sortie!");
                }
            } catch (\Throwable $th) {
                $this->addFlash("error","Impossible d'effectuer la Sortie!");
            }
            
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $lastqte = $sortie->getQteSortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $produitRepository->find($sortie->getProduit()->getId());
            if ($lastqte>$sortie->getQteSortie()) {
                $produit->setQte($produit->getQte()+($lastqte-$sortie->getQteSortie()));
            }else{
                $produit->setQte($produit->getQte()-($sortie->getQteSortie()-$lastqte));
            }
            if ($produit->getQte()>=0) {
                $entityManager->persist($produit);
                $entityManager->flush();

                return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash("error","Le Stock disponible pour ce produit est inférieur à la quantite de la sortie que vous voulez modifier!");
                
            }
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'voir' => 'oui',
        ]);
    }

    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash("success","Sortie supprimée avec succès!");
            
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }
}