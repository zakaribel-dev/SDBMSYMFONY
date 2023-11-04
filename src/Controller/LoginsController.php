<?php

namespace App\Controller;

use App\Entity\Logins;
use App\Form\LoginsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logins')]
class LoginsController extends AbstractController
{
    #[Route('/', name: 'app_logins_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $logins = $entityManager
            ->getRepository(Logins::class)
            ->findAll();

        return $this->render('logins/index.html.twig', [
            'logins' => $logins,
        ]);
    }

    #[Route('/new', name: 'app_logins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $login = new Logins();
        $form = $this->createForm(LoginsType::class, $login);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($login);
            $entityManager->flush();

            return $this->redirectToRoute('app_logins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logins/new.html.twig', [
            'login' => $login,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logins_show', methods: ['GET'])]
    public function show(Logins $login): Response
    {
        return $this->render('logins/show.html.twig', [
            'login' => $login,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_logins_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Logins $login, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LoginsType::class, $login);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_logins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logins/edit.html.twig', [
            'login' => $login,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logins_delete', methods: ['POST'])]
    public function delete(Request $request, Logins $login, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$login->getId(), $request->request->get('_token'))) {
            $entityManager->remove($login);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logins_index', [], Response::HTTP_SEE_OTHER);
    }
}
