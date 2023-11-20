<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExceptionController extends AbstractController
{
    #[Route('/error404', name: 'error_404')]
    public function show404(): Response
    {
        return $this->render('exception/error404.html.twig', [], new Response('', 404));
    }
}