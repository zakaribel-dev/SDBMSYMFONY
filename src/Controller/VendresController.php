<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Ticket;
use App\Entity\Vendre;
use App\Form\VendreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vendres')]
class VendresController extends AbstractController
{
    #[Route('/vendres/{numeroTicket}', name: 'app_vendres_index', methods: ['GET'])]
    public function index(int $numeroTicket, EntityManagerInterface $entityManager): Response
    {
        $ticketRepository = $entityManager->getRepository(Ticket::class);
    
        $ticket = $ticketRepository->findOneBy(['numeroTicket' => $numeroTicket]);
    
        $vendres = $ticket->getVendres();
        
        return $this->render('vendres/index.html.twig', [
            'vendres' => $vendres,
            'numeroTicket' =>$numeroTicket
        ]);
    }
    


    #[Route('/new/{numeroTicket}', name: 'app_vendres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $numeroTicket): Response
    {

        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy(['numeroTicket' => $numeroTicket]);

        $vendre = new Vendre();
        $vendre->setTicket($ticket);

        $form = $this->createForm(VendreType::class, $vendre, [
            'entityManager' => $entityManager,
        ]);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vendre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_vendres_index', ["numeroTicket" =>$numeroTicket], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('vendres/new.html.twig', [
            'vendre' => $vendre,
            'form' => $form->createView(),
            'numeroTicket' => $numeroTicket,
        ]);
    }

    #[Route('{numeroTicket}/{prix}/{qte}/{idArticle}/edit', name: 'app_vendres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vendre $vendre, EntityManagerInterface $entityManager, $prix, $qte, $numeroTicket,$idArticle): Response
    {
        $vendre->setPrixVente($prix);
        $vendre->setQuantite($qte);

        $ticketRepository = $entityManager->getRepository(Ticket::class);
    
        $ticket = $ticketRepository->findOneBy(['numeroTicket' => $numeroTicket]);
        $vendres = $ticket->getVendres();

        $form = $this->createForm(VendreType::class, $vendre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
              // pour redirect, dans l'index vendre il me demande un numéro ticket
              // parce que je l'ai demandé dans mon action Index alors je dois le refourguer encore ici..
            return $this->redirectToRoute('app_vendres_index', ['numeroTicket' => $numeroTicket,'idArticle' =>$idArticle], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('vendres/edit.html.twig', [
            'vendre' => $vendre, // pour mon form
            'form' => $form,
            'numeroTicket' => $numeroTicket,
            'vendres' => $vendres // pour recup le nom dlarticle
        ]);
    }

    #[Route('/{idArticle}/{numeroTicket}', name: 'app_vendres_delete', methods: ['POST'])]
    public function delete(Request $request, Vendre $vendre, EntityManagerInterface $entityManager, $numeroTicket): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vendre->getIdArticle(), $request->request->get('_token'))) {
            $entityManager->remove($vendre);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_vendres_index', ["numeroTicket" => $numeroTicket], Response::HTTP_SEE_OTHER);
    }
}
