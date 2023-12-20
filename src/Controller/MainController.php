<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[IsGranted('ROLE_STUDENT')]
    #[Route('/', name: 'home')]
    public function index(TicketRepository $ticketRepository): Response
    {
        $ticket = $ticketRepository->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController', 
            'tickets' => $ticket
        ]);
    }
}
