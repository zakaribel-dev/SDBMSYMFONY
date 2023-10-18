<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ColorsController extends AbstractController
{
    #[Route('/colors', name: 'app_colors')]
    public function index(): Response
    {
        return $this->render('colors/index.html.twig', [
            'controller_name' => 'ColorsController',
        ]);
    }
}
