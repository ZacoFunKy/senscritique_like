<?php

namespace App\Controller;

use App\Entity\Series;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur est suspendu on affiche la page adéquate
        if ($this->getUser() && $this->getUser()->getSuspendu()) {
            return $this->render('default/suspended.html.twig');
        }

        // On récupère la valeur max des IDs des séries
        $series = $entityManager
            ->getRepository(Series::class)
            ->findAll();
        $seriesIds = array();
        foreach ($series as $serie) {
            array_push($seriesIds, $serie->getId());
        }
        $max = max($seriesIds);

        $series = array();

        // On récupère 3 posters aléatoires tant que les id récupérés existent dans la base
        while (sizeof($series) != 3) {
            $poster1 = rand(0, $max);
            $poster2 = rand(0, $max);
            while ($poster2 == $poster1) {
                $poster2 = rand(0, $max);
            }
            $poster3 = rand(0, $max);
            while ($poster3 == $poster1 || $poster3 == $poster2) {
                $poster3 = rand(0, $max);
            }

            $series = $entityManager
                ->getRepository(Series::class)
                ->findBy(['id' => [$poster1, $poster2, $poster3]], ['title' => 'ASC']);
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'series' => $series,
        ]);
    }
    
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('default/about.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    
    /**
     * @Route("/histogram.svg", name="histogram")
     */
    public function rating($ratings)
    {
        return $this->render(
            'series/histogram.svg.twig',
            [ 'rating' => $ratings ],
            new Response('', 200, ['Content-Type' => 'image/svg+xml'])
        );
    }
}
