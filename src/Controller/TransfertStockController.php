<?php

namespace App\Controller;

use App\Entity\TransfertStock;
use App\Form\TransfertStockType;
use App\Repository\TransfertStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transfert/stock')]
class TransfertStockController extends AbstractController
{
    #[Route('/', name: 'app_transfert_stock_index', methods: ['GET'])]
    public function index(TransfertStockRepository $transfertStockRepository): Response
    {
        return $this->render('transfert_stock/index.html.twig', [
            'transfert_stocks' => $transfertStockRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transfert_stock_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transfertStock = new TransfertStock();
        $form = $this->createForm(TransfertStockType::class, $transfertStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transfertStock);
            $entityManager->flush();

            return $this->redirectToRoute('app_transfert_stock_index', [], Response::HTTP_SEE_OTHER);
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
