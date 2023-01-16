<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Series;
use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Genre;
use App\Entity\Season;
use App\Entity\PropertySearch;
use App\Form\PropertySeachType;
use App\Form\SerieAddFormType;
use App\Form\SeriesType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\VarDumper\Cloner\Data;

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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $genreFromForm=$propertySearch->getGenre();
            $anneeDepartFromForm=$propertySearch->getAnneeDepart();
            $anneeFinFromForm=$propertySearch->getAnneeFin();
            $nameFromForm = $propertySearch->getNom();
            $avisFromForm = $propertySearch->getAvis();

            // Toutes les séries
            $series = $entityManager->getRepository(Series::class)->findAll();
            $toutesLesSeries = array();
            foreach ($series as $serie) {
                array_push($toutesLesSeries, $serie);
            }

            // Genre
            $arrayGenre=$propertySearch->triGenre($entityManager, $genreFromForm, $toutesLesSeries);

            // Name
            $arrayName=$propertySearch->triName($nameFromForm, $toutesLesSeries);

            // Date début
            $arrayAnneeDebut=$propertySearch->triAnneeDepart($entityManager, $anneeDepartFromForm, $toutesLesSeries);

            // Date fin
            $arrayAnneeFin=$propertySearch->triAnneeFin($entityManager, $anneeFinFromForm, $toutesLesSeries);

            // Avis
            $arrayAvis=$propertySearch->triAvis($entityManager, $avisFromForm, $toutesLesSeries);

            // Intersect du tout
            $arrayIntersect = array_intersect($arrayGenre, $arrayName, $arrayAvis, $arrayAnneeDebut, $arrayAnneeFin);
            $arrayIntersect = $propertySearch->triCroissantDecroissant($entityManager, $avisFromForm, $arrayIntersect);
            
            $arrayIntersect = $paginator->paginate($arrayIntersect, $request
            ->query->getInt('page', 1, 10));

            return $this->render('series/index.html.twig', [
                'series' => $arrayIntersect,
                'form' => $form->createView(),
                'pagination' => TRUE,
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
        $series = $paginator->paginate($series, $request->query->getInt('page', 1, 10));
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
        
        $ratings = $entityManager->getRepository(Rating::class)->findBy(['series' => $series]);
        $numPage = Request::createFromGlobals()->query->get('numPage');
        $sum = 0;
        foreach ($ratings as $rating){
            $sum += $rating->getValue();
        }
        if(count($ratings) != 0){
            $avg = $sum / count($ratings);
        } else {
            $avg = 0;
        }
                
        var_dump($avg);
        if($numPage == NULL){
            $numPage = 1;
        }

        foreach($users as $user) {
            if( $user == $this->getUser()) {
                $value = 1;
            }
        }


        return $this->render('series/show.html.twig', [
            'series' => $series,
            'valeur' => $value,
            'numPage' => $numPage,
            'rating' => $ratings,
            'avg' => $avg,
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


    #[Route('/series/add/', name: 'app_admin_user_series_new', methods: ['GET', 'POST'])]
    public function addSeries(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SerieAddFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // Ask the api http://www.omdbapi.com/ for the serie with the title apiKey = 42404c61
            $url = "http://www.omdbapi.com/?apikey=42404c61&s=" . $data['title'] ."&type=series&r=json";
            $obj = json_decode(file_get_contents($url));
            $series = [];

            foreach($obj->Search as $serie){
                $series[$serie->Title]= $serie->imdbID;
            }

            if($series == []){
                $this->addFlash('error', 'No series found with this title');
                return $this->redirectToRoute('admin', ['error' => "No series found with this title"], Response::HTTP_SEE_OTHER);
            }
            $form2 = $this->createFormBuilder();
            $form2->add('title', ChoiceType::class, [
                'choices' => $series,
                'label' => 'Select the serie you want to add',
            ]);
            $form2 = $form2->getForm();

            return $this->render('series/add.html.twig', [
                'form2' => $form2->createView(),
            ]);

        }
        
        return $this->render('series/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/series/add_series/{imdb}', name: 'app_series_add', methods: ['GET', 'POST'])]
    public function newSeries(Request $request, EntityManagerInterface $entityManager, $imdb): Response
    {
        // Check if a serie in the database already exist it it does then update it

        $url = "http://www.omdbapi.com/?apikey=42404c61&i=" . $imdb ."&type=series&r=json";
        $obj = json_decode(file_get_contents($url));
        $serie = $entityManager->getRepository(Series::class)->findOneBy(['imdb' => $imdb]);
        if ($serie != null) {
            $serie->setTitle($obj->Title);
            $yearStart = substr($obj->Year, 0, 4);
            $yearEnd= substr($obj->Year, 5, 9);
            if ($yearEnd == "-") {
                $yearEnd = null;
            } else {
                $serie->setYearEnd((int)$yearEnd);
            }
            // Convert the years into int
            $yearEnd = (int)$yearEnd;
            $yearStart = (int)$yearStart;
            $serie->setYearStart($yearStart);
            if ($obj->totalSeasons == "N/A") {
                $obj->totalSeasons = null;
            } else {
                $obj->totalSeasons = (int)$obj->totalSeasons;
                $i=0;
                while($i < $obj->totalSeasons && $entityManager->getRepository(Season::class)->findOneBy(['number' => $i+1, 'series' => $serie]) == null){
                    $season = new Season();
                    $season->setNumber($i+1);
                    $season->setSeries($serie);
                    $entityManager->persist($season);
                    $i++;
                }
            }
            $poster = file_get_contents($obj->Poster);
            $serie->setPoster($poster);
            $serie->setPlot($obj->Plot);
            $serie->setImdb($imdb);
            $genre = explode(",", $obj->Genre);
            foreach ($genre as $g) {
                $genreCheck = $entityManager->getRepository(Genre::class)->findOneBy(['name' => $g]);
                if ($genreCheck != null) {
                    $serie->addGenre($genreCheck);
                }
            }
            $entityManager->flush();
            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }else {
            $serie = new Series();
            $serie->setTitle($obj->Title);
            $yearStart = substr($obj->Year, 0, 4);
            $yearEnd= substr($obj->Year, 5, 9);
            if ($yearEnd == "-") {
                $yearEnd = null;
            } else {
                $serie->setYearEnd((int)$yearEnd);
            }
            // Convert the years into int
            $yearEnd = (int)$yearEnd;
            $yearStart = (int)$yearStart;
            $serie->setYearStart($yearStart);
            if ($obj->totalSeasons == "N/A") {
                $obj->totalSeasons = null;
            } else {
                $obj->totalSeasons = (int)$obj->totalSeasons;
                //$season_check = $entityManager->getRepository(Season::class)->findOneBy(['number' => 1, 'series' => $serie]);
                //if ($season_check == null) {
                    for ($i=0; $i<$obj->totalSeasons; $i++) {
                        $season = new Season();
                        $season->setNumber($i+1);
                        $season->setSeries($serie);
                        $entityManager->persist($season);
                    }
                    
                //}
            }
            $poster = file_get_contents($obj->Poster);
            $serie->setPoster($poster);
            $serie->setPlot($obj->Plot);
            $serie->setImdb($imdb);
            $genre = explode(",", $obj->Genre);
            foreach ($genre as $g) {
                $genreCheck = $entityManager->getRepository(Genre::class)->findOneBy(['name' => $g]);
                if ($genreCheck != null) {
                    $serie->addGenre($genreCheck);
                }
            }
            $entityManager->persist($serie);
            $entityManager->flush();
            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        
        }
      
    }
}