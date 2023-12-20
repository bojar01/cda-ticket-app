<?php

namespace App\Controller;

use App\Repository\StatusRepository;
use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[IsGranted('ROLE_STUDENT')]
    #[Route('/', name: 'home')]
    public function index(TicketRepository $ticketRepository, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        $status = $statusRepository->findAll();
        $statusId = $status[0]->getId();

        // Ici requete pour les autres ticket;

        $ticket = $ticketRepository->findTicketsWhereStatusAndExcludedUser($userId, $statusId);

        // dd($ticket);

        // ICI REQUETE POUR les userTickets

        $userTickets = $ticketRepository->findBy(
            ['owner' => $userId ]
        );

        // dd($userTickets);

        // $products = $repository->findBy(
        //     ['name' => 'Keyboard'],
        //     ['price' => 'ASC']
        // );

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController', 
            'tickets' => $ticket,
            'userTickets' => $userTickets
        ]);
    }
}
