<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Form\TicketEditType;
use App\Repository\StatusRepository;
use App\Repository\TicketRepository;
use App\Services\discordWebHook;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function new(Request $request, EntityManagerInterface $entityManager, StatusRepository $statusRepository, SluggerInterface $slugger): Response
    {
        $ticket = new Ticket();
        $status = $statusRepository->findall();

        // dd($status);
        $owner = $this->getUser();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) 
            {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $ticket->setImage($newFilename);
            }

                $ticket->setOwner($owner);
                $ticket->setStatus($status[0]);
                $entityManager->persist($ticket);
                $entityManager->flush();

                
                if($owner->getSession() != null) {
                    $discordChannelLink = $owner->getSession()->getDiscordChannelLink();

                    if($discordChannelLink != null) {
                        $ownerName = $owner->getFirstname() . ' ' . $owner->getLastname();
                        $technology = $ticket->getTechnology()->getName();
                        $discordMessage = new discordWebHook();
                        $discordMessage->sender($discordChannelLink, $ownerName, $technology);
                    }

                }
                return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
            

        }
            return $this->render('ticket/new.html.twig', [
                'ticket' => $ticket,
                'form' => $form->createView(),
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
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TicketEditType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) 
            {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $ticket->setImage($newFilename);
            }
            // $ticketsRepository->save($ticket, true);
                $ticket->setUpdatedAt(new \DateTimeImmutable());
                $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);

        }
            return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
            ]);
    }

    #[Route('/{id}/angel', name: 'app_ticket_angel', methods:['POST'])]
    public function angel(Request $request, Ticket $ticket, StatusRepository $status , EntityManagerInterface $entityManager):Response
    {
        if($this->isCsrfTokenValid('angel' . $ticket->getId(), $request->request->get('_token')))
        {
            $allStatus = $status->findAll();

            $user = $this->getUser();

            $ticket->setAngel($user);
            $ticket->setStatus($allStatus[1]);
            $ticket->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();
            // dd($ticket);
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/unsetangel', name: 'app_ticket_unsetangel', methods:['POST'])]
    public function unresolved(Request $request, Ticket $ticket, StatusRepository $status , EntityManagerInterface $entityManager):Response
    {
        if($this->isCsrfTokenValid('unsetangel' . $ticket->getId(), $request->request->get('_token')))
        {
            $allStatus = $status->findAll();

            $user = $this->getUser();

            $ticket->setAngel(null);
            $ticket->setStatus($allStatus[0]);
            $ticket->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();
            // dd($ticket);
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/resolveTicket', 'app_ticket_resolve', methods:['post'])]
    public function resolve(Request $request, Ticket $ticket, StatusRepository $status, EntityManagerInterface $entityManager):Response
    {
        if($this->isCsrfTokenValid('resolve' . $ticket->getId(), $request->request->get('_token'))){
            $allStatus = $status->findAll();

            $ticket->setStatus($allStatus[2]);
            $ticket->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) 
        {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
}
