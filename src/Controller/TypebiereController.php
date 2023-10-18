<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypebiereController extends AbstractController
{
    #[Route('/typebiere', name: 'app_typebiere')]
    public function index(): Response
    {
        return $this->render('typebiere/index.html.twig', [
            'controller_name' => 'TypebiereController',
        ]);
    }
}
