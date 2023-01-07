<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Series;
use App\Entity\PropertySearch;
use App\Form\PropertySeachType;
use App\Form\SeriesType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/series')]
class SeriesController extends AbstractController
{

    #[Route('/', name: 'app_series_index', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $entityManager, Request $request,
    PaginatorInterface $paginator
    ): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySeachType::class,$propertySearch);
        $form->handleRequest($request);

        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');
        if ($form->isSubmitted() && $form->isValid()) {
            $name = $propertySearch->getNom();
            if ($name) {
                // if name is contained in the title of a series
                $queryBuilder->where('s.title LIKE :name')
                    ->setParameter('name', '%'.$name.'%');
                $series = $queryBuilder->getQuery()->getResult();
            }
            else {
                $series = $entityManager
                ->getRepository(Series::class)
                ->findBy([], ['title' => 'ASC'], 10, 0); //limit et offset
                
            }
        }else {
            $series = $entityManager
            ->getRepository(Series::class)
            ->findBy([], ['title' => 'ASC']);


            $series = $paginator->paginate($series, $request
            ->query->getInt('page', 1, 10));

            $numPage = $request->query->getInt('page', 1, 10);
    
            return $this->render('series/index.html.twig', [
                'series' => $series,
                'form' => $form->createView(),
                'pagination' => TRUE,
                'numPage' => $numPage,
            ]);
            
        }

        $series = $paginator->paginate($series, $request
        ->query->getInt('page', 1, 10));
        
        return $this->render('series/index.html.twig', [
            'series' => $series,
            'form' => $form->createView(),
            'pagination' => FALSE,
        ]);

   
    }

    #[Route('/new', name: 'app_series_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $series = new Series();
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($series);
            $entityManager->flush();

            return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('series/new.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_series_show', methods: ['GET'])]
    public function show(Series $series): Response
    {
        $users = $series->getUser();
        $value = 0;

        $numPage = Request::createFromGlobals()->query->get('numPage');

        foreach($users as $user) {
            if($user == $this->getUser()){
                $value = 1;            
            } 
        }

        return $this->render('series/show.html.twig', [
            'series' => $series,
            'valeur' => $value,
            'numPage' => $numPage,
        ]);
    }

    #[Route('/{series}/{episode}/set_seen/{yesno}', name: 'app_series_show_seen_adds', methods: ['GET'])]
    public function addSeen(Episode $episode, $yesno, EntityManagerInterface $entityManager): Response
    {
        if ($yesno == "1"){
            $this->getUser()->addEpisode($episode);
            $entityManager->flush();
        }else{
            $this->getUser()->removeEpisode($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_series_show', ['id' => $episode->getSeason()->getSeries()->getId()], Response::HTTP_SEE_OTHER);
        /*
        return $this->render('series/show.html.twig', [
            'series' => $series
        ]);*/
    }

    #[Route('/{series}/set_following/{yesno}/{redirect}', name: 'app_series_show_adds', methods: ['GET'])]
    public function addSerie(Series $series, $yesno, $redirect, EntityManagerInterface $entityManager): Response
    {
        if ($yesno == "1"){
            $this->getUser()->addSeries($series);
            $entityManager->flush();
        }else{
            $this->getUser()->removeSeries($series);
            $entityManager->flush();
        }

        if ($redirect == "1"){
            return $this->redirectToRoute('app_series_show', ['id' => $series->getId()], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('app_user_favorite', [], Response::HTTP_SEE_OTHER);
        }

        /*
        return $this->render('series/show.html.twig', [
            'series' => $series
        ]);*/
    }

    #[Route('/{id}/edit', name: 'app_series_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('series/edit.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_series_delete', methods: ['POST'])]
    public function delete(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$series->getId(), $request->request->get('_token'))) {
            $entityManager->remove($series);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/poster/{id}', name: 'poster_series_show', methods: ['GET'])]
    public function showPoster(Series $series): Response
    {
    return new Response(stream_get_contents($series->getPoster()), 200, array ('Content-type' => 'image/jpeg'));
    }
}