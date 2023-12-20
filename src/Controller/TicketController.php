<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Form\TicketEditType;
use App\Repository\StatusRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }
    #[IsGranted('ROLE_STUDENT')]
    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, StatusRepository $statusRepository): Response
    {
        $ticket = new Ticket();
        $status = $statusRepository->findall();

        // dd($status);
        $owner = $this->getUser();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setOwner($owner);
            $ticket->setStatus($status[0]);
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketEditType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/angel', name: 'app_ticket_angel', methods:['POST'])]
    public function angel(Request $request, Ticket $ticket, StatusRepository $status , EntityManagerInterface $entityManager):Response
    {
        if($this->isCsrfTokenValid('angel' . $ticket->getId(), $request->request->get('_token'))){
            $allStatus = $status->findAll();

            $user = $this->getUser();

            $ticket->setAngel($user);
            $ticket->setStatus($allStatus[1]);

            $entityManager->flush();
            // dd($ticket);
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unsetangel', name: 'app_ticket_unsetangel', methods:['POST'])]
    public function unresolved(Request $request, Ticket $ticket, StatusRepository $status , EntityManagerInterface $entityManager):Response
    {
        if($this->isCsrfTokenValid('unsetangel' . $ticket->getId(), $request->request->get('_token'))){
            $allStatus = $status->findAll();

            $user = $this->getUser();

            $ticket->setAngel(null);
            $ticket->setStatus($allStatus[0]);

            $entityManager->flush();
            // dd($ticket);
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
}
