<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SearchController extends AbstractController
{
    #[IsGranted('ROLE_STUDENT')]
    #[Route('/recherche', name: 'app_search')]
    public function index(Request $request,TicketRepository $ticketRepository, PaginatorInterface $paginator): Response
    {
        $query = htmlspecialchars($request->query->get('r'));

        if(!empty($query)){
            $tickets = $ticketRepository->findBySubjectLike(htmlspecialchars($query));
        } else {
            $tickets = $ticketRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $tickets,
            $request->query->getInt('page', 1),
            5
        );

        // dd($tickets);
        // dd($pagination);

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'pagination' => $pagination
        ]);
    }
}
