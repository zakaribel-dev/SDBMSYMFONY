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
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/tickets')]
class TicketsController extends AbstractController
{
    #[Route('/', name: 'app_tickets_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator,SerializerInterface $serializer): Response
    {
        $tickets = $entityManager
            ->getRepository(Ticket::class)
            ->findBy([], ['annee' => "DESC"]);

            $pagination = $paginator->paginate(
                $tickets,
                $request->query->getInt('page', 1), // numéro de la page par défaut ca va etre 1
                50 // nombre d'articles par page
            );
            $pagination->setCustomParameters(['addClass' => 'new']);

            $jsonTickets = $serializer->serialize($tickets, 'json', ['groups' => ['default']]);

        return $this->render('tickets/index.html.twig', [
            'pagination' => $pagination,
            'tickets' => $jsonTickets
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

}
