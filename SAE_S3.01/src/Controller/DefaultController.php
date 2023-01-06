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
        /* TODO: Générer 3 nombres randoms, récupérer les séries
        correspondantes dans $series et les envoyer dans le return. */

        $series = $entityManager
            ->getRepository(Series::class)
            ->findBy([], ['title' => 'ASC']);

        $a = rand(0,sizeof($series));
        $b = rand(0,sizeof($series));
        $c = rand(0,sizeof($series));

        $a = $series[$a];
        $b = $series[$b];
        $c = $series[$c];

        $series = [$a, $b, $c];

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'series' => $series,
        ]);
    }
}
