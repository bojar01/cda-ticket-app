<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-compte')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findBy([
            'id' => $this->getUser()->getId()
        ]);

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user[0]
        ]);
    }

    #[Route('/tatata', name: 'app_tatata')]
    public function tatata(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findBy([
            'id' => $this->getUser()->getId()
        ]);

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user[0]
        ]);
    }

    #[IsGranted('ROLE_STUDENT')]
    #[Route('/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository ,EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findBy([
            'id' => $this->getUser()->getId()
        ]);
        
        if($this->getUser()->getId() !== $user[0]->getId()){
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(AccountType::class, $user[0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
