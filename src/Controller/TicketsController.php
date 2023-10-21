<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tickets')]
class TicketsController extends AbstractController
{
    #[Route('/', name: 'app_tickets_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {
        $tickets = $entityManager
            ->getRepository(Ticket::class)
            ->findBy([], []);

            $pagination = $paginator->paginate(
                $tickets,
                $request->query->getInt('page', 1), // numéro de la page par défaut ca va etre 1
                4  // nombre d'articles par page
            );
            $pagination->setCustomParameters(['addClass' => 'new']);

        return $this->render('tickets/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_tickets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $annee = $request->request->all(); // Je recupere l'année grâce à Request
        $annee = $annee['ticket']['annee'];

        $query = $entityManager->getRepository(Ticket::class)
        ->createQueryBuilder('t')
        ->select('MAX(t.numeroTicket)')
        ->where('t.annee = :annee')
        ->setParameter('annee', $annee)
        ->getQuery();

        $lastTicketNumber = $query->getSingleScalarResult();

        $newTicketNumber = $lastTicketNumber + 1; // j'ajoute +1 pour generer le nouveau ticket
        $ticket->setNumeroTicket($newTicketNumber); 

        if ($annee !== null) {
            $ticket->setAnnee($annee);
        }

        $this->addFlash(
            'success',
            'Ticket bien ajouté'
        );
    
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{annee}', name: 'app_tickets_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }


    #[Route('/edit/{annee}/{numeroTicket}', name: 'app_tickets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket); // jcreer le form
        $form->handleRequest($request); // jrecupere le form

        if ($form->isSubmitted() && $form->isValid()) {  
            //bon le soucis c'est que je modifie seulement dateVente mais pas annee. 
            //Je pourrais modifier annee également mais c'est une clé étrangère donc yaura un foreign key contraint.
            // Si je veux forcer la modification annee je dois aussi modifier ou supprimer la vente liée à cette annee
            // Je pense qu au final, dans la vraie vie on modifie pas vraiment la date d'un ticket edité 
            // c'est comme un historique ou une preuve donc ce serait dangeureux de le modifier..
            $entityManager->flush();
            $this->addFlash('success', 'Ticket bien modifié !');

            return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{annee}/{numeroTicket}', name: 'app_tickets_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getAnnee(), $request->request->get('_token'))) {
            // Je suupprime d'abord la vente liée au ticket (que l'on trouve grâce à l'année).
            // Cela évite d'avoir le problème comme quoi ce ticket est lié à une vente (foreign key constraint).
            $vendreRecords = $ticket->getVendres(); 
            foreach ($vendreRecords as $vendreRecord) {
                $entityManager->remove($vendreRecord);
            }
    
            // Une fois que c'est fait, on peut supprimer ce ticket.
            $entityManager->remove($ticket);
            $entityManager->flush();
    
            $this->addFlash(
                'success',
                'Ticket bien supprimé !'
            );
        }
    
        return $this->redirectToRoute('app_tickets_index', [], Response::HTTP_SEE_OTHER);
    }
}
