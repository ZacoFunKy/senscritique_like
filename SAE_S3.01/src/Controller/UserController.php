<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;

class UserController extends AbstractController
{
    #[Route('/user/favoris', name: 'app_user_favorite')]
    public function index(): Response
    {
        return $this->render('user/favorite.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/history', name: 'app_user_history')]
    public function history(EntityManagerInterface $entityManager, Request $request,
    PaginatorInterface $paginator): Response
    {
        $episodes = $this->getUser()->getEpisode();
        $episodes = $paginator->paginate($episodes, $request
        ->query->getInt('page', 1, 10));

        return $this->render('user/history.html.twig', [
            'episodes' => $episodes,
        ]);
    }
    
}
