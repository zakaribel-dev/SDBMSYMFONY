<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Vendre;
use App\Form\VendreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/vendres')]
class VendresController extends AbstractController
{
    #[Route('/vendres/{numeroTicket}', name: 'app_vendres_index', methods: ['GET'])]
    public function index(int $numeroTicket, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticketRepository = $entityManager->getRepository(Ticket::class);
        $annee = $request->query->get('annee');

        $ticket = $ticketRepository->findOneBy([
            'numeroTicket' => $numeroTicket,
            'annee' => $annee
        ]);

        $vendres = $ticket->getVendres();

        return $this->render('vendres/index.html.twig', [
            'vendres' => $vendres,
            'numeroTicket' => $numeroTicket,
            "annee" => $annee
        ]);
    }

    #[Route('/new/{numeroTicket}/{annee}', name: 'app_vendres_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        $numeroTicket,
        $annee,
        TranslatorInterface $translator
    ): Response {
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy(
            [
                'numeroTicket' => $numeroTicket,
                'annee' => $annee
            ]
        );

        $vendre = new Vendre();
        $vendre->setTicket($ticket);

        $form = $this->createForm(VendreType::class, $vendre, [
            'entityManager' => $entityManager,
        ]);

        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($vendre);
                $entityManager->flush();

                $this->addFlash('success', $translator->trans('Nouvelle ligne ajoutée !'));
                return $this->redirectToRoute('app_vendres_index', ["numeroTicket" => $numeroTicket, "annee" => $annee], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {

            $this->addFlash('error', $translator->trans('Désolé cet article est déjà présent dans le ticket.'));
        }

        return $this->render('vendres/new.html.twig', [
            'vendre' => $vendre,
            'form' => $form->createView(),
            'numeroTicket' => $numeroTicket,
        ]);
    }

    #[Route('{numeroTicket}/{prix}/{qte}/{idArticle}/{annee}/edit', name: 'app_vendres_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Vendre $vendre,
        EntityManagerInterface $entityManager,
        $prix,
        $qte,
        $annee,
        $numeroTicket,
        $idArticle,
        TranslatorInterface $translator
    ): Response {
        $vendre->setPrixVente($prix);
        $vendre->setQuantite($qte);

        $ticketRepository = $entityManager->getRepository(Ticket::class);

        $ticket = $ticketRepository->findOneBy(['numeroTicket' => $numeroTicket, 'annee' => $annee]);
        $vendres = $ticket->getVendres();

        $form = $this->createForm(VendreType::class, $vendre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('Ligne bien modifié !'));

            // pour redirect, dans l'index vendre il me demande un numéro ticket
            // parce que je l'ai demandé dans mon action Index alors je dois le refourguer encore ici..
            return $this->redirectToRoute('app_vendres_index', ['numeroTicket' => $numeroTicket, 'idArticle' => $idArticle, 'annee' => $annee], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vendres/edit.html.twig', [
            'vendre' => $vendre, // pour mon form
            'form' => $form,
            'numeroTicket' => $numeroTicket,
            'vendres' => $vendres,// pour recup le nom dlarticle
            'annee' => $annee 
        ]);
    }

    #[Route('/{idArticle}/{numeroTicket}', name: 'app_vendres_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Vendre $vendre,
        EntityManagerInterface $entityManager,
        $numeroTicket,
        TranslatorInterface $translator
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $vendre->getIdArticle(), $request->request->get('_token'))) {

            $entityManager->remove($vendre);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('Ligne bien supprimée !'));
        }

        $annee = $request->get('annee'); // je n'arrive pas à recuperer le parametre annee dans ma Route (cf ligne 123) alors j'utilise  request get pour recuperer l'année 
        // car je voudrais recuperer l'url de cette manière /numeroticket/annee mais je recupere /numeroticket/?annee=annee

        return $this->redirectToRoute('app_vendres_index', ["numeroTicket" => $numeroTicket, "annee" => $annee], Response::HTTP_SEE_OTHER);
    }
}
