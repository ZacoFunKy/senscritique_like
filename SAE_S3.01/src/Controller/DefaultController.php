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
        $a = rand(0,234);
        $b = rand(0,234);
        while ($b == $a) {
            $b = rand(0,234);
        }
        $c = rand(0,234);
        while ($c == $a || $c == $b) {
            $c = rand(0,234);
        }

        $series = $entityManager
            ->getRepository(Series::class)
            ->findBy(['id' => [$a, $b, $c]], ['title' => 'ASC']);

        while (sizeof($series) != 3) {
            $a = rand(0,234);
            $b = rand(0,234);
            while ($b == $a) {
                $b = rand(0,234);
            }
            $c = rand(0,234);
            while ($c == $a || $c == $b) {
                $c = rand(0,234);
            }

            $series = $entityManager
                ->getRepository(Series::class)
                ->findBy(['id' => [$a, $b, $c]], ['title' => 'ASC']);
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'series' => $series,
        ]);
    }
}
