<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Series;
use App\Entity\User;
use App\Entity\Rating;
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
            $genre=$propertySearch->getGenre();
            $anneeDeSortie=$propertySearch->getAnneeDeSortie();
            $name = $propertySearch->getNom();
            $avis = $propertySearch->getAvis();
            if ($name) {
                switch ($avis) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $queryBuilder->where('s.title LIKE :name')
                            ->andWhere('s.rating BETWEEN :rating-1 AND :rating+1')
                            ->setParameter('name', '%'.$name.'%')
                            ->setParameter('rating', $avis);
                        break;
                    case 'ASC':
                        $queryBuilder->where('s.title LIKE :name')
                            ->orderBy('s.rating', 'ASC')
                            ->setParameter('name', '%'.$name.'%');
                        break;
                    case 'DESC':
                        $queryBuilder->where('s.title LIKE :name')
                            ->orderBy('s.rating', 'DESC')
                            ->setParameter('name', '%'.$name.'%');
                        break;
                    default:
                        $queryBuilder->where('s.title LIKE :name')
                            ->setParameter('name', '%'.$name.'%');
                        break;

                }
                $series = $queryBuilder->getQuery()->getResult();
            }
            else {
                switch($avis) {
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $queryBuilder->where('s.rating BETWEEN :rating-1 AND :rating+1')
                            ->setParameter('rating', $avis);
                        break;
                    case 'ASC':
                        $queryBuilder->orderBy('s.rating', 'ASC');
                        break;
                    case 'DESC':
                        $queryBuilder->orderBy('s.rating', 'DESC');
                        break;
                    case null:
                        $series = $entityManager
                        ->getRepository(Series::class)
                        ->findBy([], ['title' => 'ASC']);
                        break;
                }
                $series = $queryBuilder->getQuery()->getResult();
            }

            $series = $paginator->paginate($series, $request
            ->query->getInt('page', 1, 10));


            return $this->render('series/index.html.twig', [
                'series' => $series,
                'form' => $form->createView(),
                'pagination' => FALSE,
            ]);
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
    public function show(Series $series, EntityManagerInterface $entityManager): Response
    {
        $users = $series->getUser();
        $value = 0;
        
        $rating = $entityManager->getRepository(Rating::class)->findBy(['series' => $series]);
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if($numPage == NULL){
            $numPage = 1;
        }

        foreach($users as $user) {
            if($user == $this->getUser()){
                $value = 1;            
            } 
        }

        return $this->render('series/show.html.twig', [
            'series' => $series,
            'valeur' => $value,
            'numPage' => $numPage,
            'rating' => $rating,
        ]);
    }

    #[Route('/{series}/{episode}/set_seen/{yesno}', name: 'app_series_show_seen_adds', methods: ['GET'])]
    public function addSeen(Episode $episode, $yesno, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser() != null){

            if ($yesno == "1"){
                $this->getUser()->addEpisode($episode);
                $entityManager->flush();
            }else{
                $this->getUser()->removeEpisode($episode);
                $entityManager->flush();
            }

            $numPage = Request::createFromGlobals()->query->get('numPage');

            if($numPage == NULL){
                $numPage = 1;
            }

            return $this->redirectToRoute('app_series_show', ['id' => $episode->getSeason()->getSeries()->getId(), 'numPage' => $numPage], Response::HTTP_SEE_OTHER);
    } else {
        return $this->redirectToRoute('app_series_show', ['numPage' => $numPage], Response::HTTP_SEE_OTHER);

    }

        /*
        return $this->render('series/show.html.twig', [
            'series' => $series
        ]);*/
    }

    #[Route('/{series}/set_following/{yesno}/{redirect}', name: 'app_series_show_adds', methods: ['GET'])]
    public function addSerie(Series $series, $yesno, $redirect, EntityManagerInterface $entityManager): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if($numPage == NULL){
            $numPage = 1;
        }  

        if($this->getUser() != null){

            if ($yesno == "1"){
                $this->getUser()->addSeries($series);
                $entityManager->flush();
            }else{
                $this->getUser()->removeSeries($series);
                $entityManager->flush();
            }

            if ($redirect == "1"){
                return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->redirectToRoute('app_user_favorite', ['numPage' => $numPage], Response::HTTP_SEE_OTHER);
            }
    } else {
        return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage], Response::HTTP_SEE_OTHER);
         
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

    #[Route('/poster/{id}', name: 'poster_series_show', methods: ['GET', 'POST'])]
    public function showPoster(Series $series): Response
    {
    return new Response(stream_get_contents($series->getPoster()), 200, array ('Content-type' => 'image/jpeg'));
    }

    #[Route('/series/rating/{id}', name: 'rating_series_show', methods: ['GET', 'POST'])]
    public function showRating(Series $series,
    EntityManagerInterface $entityManager): Response
    {

        $numPage = Request::createFromGlobals()->query->get('numPage');

        if($numPage == NULL){
            $numPage = 1;
        }
        $request = Request::createFromGlobals();
        $content = $request->getContent();
        $data = json_decode($content, true);
        $rate = $data['value'];
        $comment = $data['text'];

        //Respond to the fetch for it to be a 200 
        $respond = new Response();
        $respond->setStatusCode(200);
        $respond->send();

        $rating = $entityManager->getRepository(Rating::class)->findOneBy(['user' => $this->getUser(), 'series' => $series]);
        if ($rating != null){
            $rating->setValue($rate);
            $rating->setComment($comment);
            $rating->setDate(new \DateTime());
            $entityManager->flush();
        }else {
            $ranting = new Rating();
            $ranting->setUser($this->getUser());
            $ranting->setSeries($series);
            $ranting->setValue($rate);
            $ranting->setComment($comment);
            $ranting->setDate(new \DateTime()); 
            $entityManager->persist($ranting);
            $entityManager->flush();
        }

        $ratings = $entityManager->getRepository(Rating::class)->findBy(['series' => $series]);
        $sum = 0;
        foreach ($ratings as $rating){
            $sum += $rating->getValue();
        }
        $series->setRating($sum / count($ratings));
        $entityManager->flush();



        return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage], Response::HTTP_SEE_OTHER);

    }

    #[Route('/series/rating/{id}/{user}/delete', name: 'rating_series_delete', methods: ['GET', 'POST'])]
    public function deleteRating(Series $series, EntityManagerInterface $entityManager, User $user){
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if($numPage == NULL){
            $numPage = 1;
        }

        $rating = $entityManager->getRepository(Rating::class)->findOneBy(['user' => $user, 'series' => $series]);
        if ($rating != null){
            $entityManager->remove($rating);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage], Response::HTTP_SEE_OTHER);
    }
}