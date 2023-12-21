<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'app_api')]
class ApiController extends AbstractController
{
    #[Route('/discord/tickets')]
    public function index(TicketRepository $ticketRepository, SerializerInterface $serializer): JsonResponse
    {
        $tickets = $ticketRepository->findLowDataTickets();
        $ticketsCount = count($tickets);

        // dd($tickets);

        $jsonTickets = $serializer->serialize($tickets, 'json');

        $data = [
            'data' => json_decode($jsonTickets),
            'count' => $ticketsCount
        ];

        return new JsonResponse($data);
    }

    #[Route('/discord/newticket', methods: ['POST'])]
    public function newTicket(Request $request){
        $data = $request->toArray();
        // dd($data);

        // $category = $data['technology'];
        // $description = $data['subject'];
        $author = $data['author'];

        $response = [
            'data' => 'Bien reçu '. $author .' le ticket est créé',
        ];

        return new JsonResponse($response);
    }
}
