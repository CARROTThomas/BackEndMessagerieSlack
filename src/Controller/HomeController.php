<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function redirectToDoc(): Response
    {
        return $this->redirectToRoute('app_home_doc');
    }

    #[Route('/doc', name: 'app_home_doc')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }
}
