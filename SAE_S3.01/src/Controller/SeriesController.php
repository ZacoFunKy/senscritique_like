<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Series;
use App\Entity\Season;
use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Genre;
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
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySeachType::class, $propertySearch);
        $form->handleRequest($request);

        $ratings = $entityManager->getRepository(Rating::class)->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $genreFromForm = $propertySearch->getGenre();
            $anneeDepartFromForm = $propertySearch->getAnneeDepart();
            $anneeFinFromForm = $propertySearch->getAnneeFin();
            $nameFromForm = $propertySearch->getNom();
            $avisFromForm = $propertySearch->getAvis();
            $suiviFromForm = $propertySearch->getSuivi();

            // Toutes les séries
            $series = $entityManager->getRepository(Series::class)->findAll();
            $toutesLesSeries = array();
            foreach ($series as $serie) {
                array_push($toutesLesSeries, $serie);
            }

            // Genre
            $arrayGenre=$propertySearch->triGenre(
                $entityManager,
                $genreFromForm,
                $toutesLesSeries
            );

            // Nom
            $arrayName=$propertySearch->triName($nameFromForm, $toutesLesSeries);

            // Date début
            $arrayAnneeDebut=$propertySearch->triAnneeDepart(
                $entityManager,
                $anneeDepartFromForm,
                $toutesLesSeries
            );

            // Date fin
            $arrayAnneeFin=$propertySearch->triAnneeFin(
                $entityManager,
                $anneeFinFromForm,
                $toutesLesSeries
            );

            // Avis
            $arrayAvis=$propertySearch->triAvis(
                $avisFromForm,
                $toutesLesSeries
            );

            // Suivi
            if ($this->getUser() != null) {
                $arraySuivi=$propertySearch->triSuivi(
                    $entityManager,
                    $suiviFromForm,
                    $toutesLesSeries,
                    $this->getUser()->getId()
                );
            } else {
                $arraySuivi = $toutesLesSeries;
            }
            
            // Intersect du tout
            $arrayIntersect = array_intersect(
                $arrayGenre,
                $arrayName,
                $arrayAvis,
                $arrayAnneeDebut,
                $arrayAnneeFin,
                $arraySuivi
            );
            $arrayIntersect = $propertySearch->triCroissantDecroissant($entityManager, $avisFromForm, $arrayIntersect);
            
            $arrayIntersect = $paginator->paginate(
                $arrayIntersect,
                $request->query->getInt('page', 1, 10));

            return $this->render('series/index.html.twig', [
                'series' => $arrayIntersect,
                'form' => $form->createView(),
                'pagination' => true,
                'ratings' => $ratings,
            ]);
        } else {
            $series = $entityManager
            ->getRepository(Series::class)
            ->findBy([], ['title' => 'ASC']);

            $series = $paginator->paginate($series, $request->query->getInt('page', 1, 10));

            $numPage = $request->query->getInt('page', 1, 10);
    
            return $this->render('series/index.html.twig', [
                'series' => $series,
                'form' => $form->createView(),
                'pagination' => true,
                'numPage' => $numPage,
                'ratings' => $ratings,
            ]);
        }
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
    public function show(Series $series, EntityManagerInterface $entityManager, Request $request,
    PaginatorInterface $paginator): Response
    {

        $users = $series->getUser();
        $value = 0;
        $ratings = $entityManager->getRepository(Rating::class)->findBy(['series' => $series]);
        $ranting_verified = $entityManager->getRepository(Rating::class)
        ->findBy(['series' => $series, 'verified' => 1]);
        $numPage = Request::createFromGlobals()->query->get('numPage');
        $sum = 0;
        foreach ($ranting_verified as $rating){
            $sum += $rating->getValue();
        }
        if (count($ranting_verified) != 0) {
            $avg = $sum / count($ranting_verified);
            $avg = round($avg, 2);
        } else {
            $avg = "Pas d'avis";
        }
                
        if ($numPage == null) {
            $numPage = 1;
        }

        foreach ($users as $user) {
            if ($user == $this->getUser()) {
                $value = 1;
            }
        }
        $userRating = null;
        foreach ($ratings as $rating) {
            if ($rating->getUser() == $this->getUser()) {
                $userRating = $rating;
            }
        }

        $ratings = array_reverse($ratings);
        $allRatings = $entityManager->getRepository(Rating::class)->findBy(['series' => $series, 'verified' => 1]);
        $ratings = $paginator->paginate($ratings, $request->query->getInt('page', 1, 10));


        return $this->render('series/show.html.twig', [
            'series' => $series,
            'valeur' => $value,
            'numPage' => $numPage,
            'rating' => $ratings,
            'allRatings' => $allRatings,
            'avg' => $avg,
            'nbNotes' => $sum,
            'userRating' => $userRating,
        ]);
    }


    #[Route('/{series}/{episode}/set_seen/{yesno}/', name: 'app_series_show_seen_adds', methods: ['GET'])]
    public function addSeen(Episode $episode, $yesno, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() != null) {
            $numPage = Request::createFromGlobals()->query->get('numPage');
            if ($numPage == null) {
                $numPage = 1;
            }

            if ($yesno == "1") {
                //Permet d'ajouter un épisode dans la liste des épisodes vus par l'utilisateur
                $this->getUser()->addEpisode($episode);
                $entityManager->flush();
                return $this->redirectToRoute(
                    'app_series_show_adds',
                    ['series' => $episode
                        ->getSeason()
                        ->getSeries()
                        ->getId(),
                    'numPage' => $numPage,
                    'yesno' => 1,
                    'redirect' => 1],
                    Response::HTTP_SEE_OTHER
                );
            } else {
                //Lorsque $yesno = 0 l'épisode est retiré de la liste des épisodes vus par l'utilisateur
                $this->getUser()->removeEpisode($episode);
                $entityManager->flush();
                return $this->redirectToRoute(
                    'app_series_show',
                    ['id' => $episode
                        ->getSeason()
                        ->getSeries()
                        ->getId(),
                    'numPage' => $numPage],
                );
            }
        } else {
            return $this->redirectToRoute(
                'app_series_show',
                ['numPage' => $numPage],
                Response::HTTP_SEE_OTHER
            );
        }
    }

    #[Route('/{series}/set_following/{yesno}/{redirect}', name: 'app_series_show_adds', methods: ['GET'])]
    public function addSerie(Series $series, $yesno, $redirect, EntityManagerInterface $entityManager): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if ($numPage == null) {
            $numPage = 1;
        }

        if ($this->getUser() != null) {

            if ($yesno == "1") {
                //Permet d'ajouter une série dans la liste des séries suivis par l'utilisateur
                $this->getUser()->addSeries($series);
                $entityManager->flush();
            } else {
                //Lorsque $yesno = 0 la série est retiré de la liste des séries suivis par l'utilisateur
                $this->getUser()->removeSeries($series);
                $entityManager->flush();
            }

            if ($redirect == "1") {
                return $this->redirectToRoute(
                    'app_series_show',
                    ['id' => $series->getId(),
                    'numPage' => $numPage],
                    Response::HTTP_SEE_OTHER
                );
            } else {
                return $this->redirectToRoute(
                    'app_user_favorite',
                    ['numPage' => $numPage],
                    Response::HTTP_SEE_OTHER
                );
            }
        } else {
            return $this->redirectToRoute(
                'app_series_show',
                ['id' => $series->getId(),
                'numPage' => $numPage],
                Response::HTTP_SEE_OTHER
            );
        }
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
    public function showRating(Series $series, EntityManagerInterface $entityManager): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if ($numPage == null) {
            $numPage = 1;
        }
        $request = Request::createFromGlobals();
        $content = $request->getContent();
        $data = json_decode($content, true);
        $rate = $data['value'];
        $comment = $data['text'];

        $respond = new Response();
        $respond->setStatusCode(200);
        $respond->send();

        $ratings = $entityManager
            ->getRepository(Rating::class)
            ->findOneBy(
                ['user' => $this->getUser(),
                'series' => $series]
            );

        if ($ratings == null) {
            $rating = new Rating();
            $rating->setUser($this->getUser());
            $rating->setSeries($series);
            $rating->setValue($rate);
            if ($this->getUser()->getisAdmin()) {
                $rating->setVerified(true);
            } else {
            $rating->setVerified(false);
            }
            $rating->setComment($comment);
            $rating->setDate(new \DateTime());
            $entityManager->persist($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_series_show',
            ['id' => $series->getId(),
            'numPage' => $numPage],
            Response::HTTP_SEE_OTHER
        );
    }

    #[Route('/series/rating/modify/{id}', name: 'modifify_rating_series_show', methods: ['GET', 'POST'])]
    public function showModifyRating(Series $series, EntityManagerInterface $entityManager): Response
    {

        $numPage = Request::createFromGlobals()->query->get('numPage');

        if ($numPage == null) {
            $numPage = 1;
        }
        $request = Request::createFromGlobals();
        $content = $request->getContent();
        $data = json_decode($content, true);
        $rate = $data['value'];
        $comment = $data['text'];

        $respond = new Response();
        $respond->setStatusCode(200);
        $respond->send();

        $ratings = $entityManager
            ->getRepository(Rating::class)
            ->findOneBy(
                ['user' => $this->getUser(),
                'series' => $series]
            );
            
        $ratings->setValue($rate);
        $ratings->setComment($comment);
        $ratings->setDate(new \DateTime());
        $entityManager->flush();


        return $this->redirectToRoute(
            'app_series_show',
            ['id' => $series->getId(),
            'numPage' => $numPage],
            Response::HTTP_SEE_OTHER
        );
    }


    #[Route('/series/rating/{id}/{user}/delete', name: 'rating_series_delete', methods: ['GET', 'POST'])]
    public function deleteRating(Series $series, EntityManagerInterface $entityManager, User $user){
        $numPage = Request::createFromGlobals()->query->get('numPage');

        if ($numPage == null) {
            $numPage = 1;
        }

        $rating = $entityManager->getRepository(Rating::class)->findOneBy(['user' => $user, 'series' => $series]);
        if ($rating != null){
            $entityManager->remove($rating);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_series_show',
            ['id' => $series->getId(),
            'numPage' => $numPage],
            Response::HTTP_SEE_OTHER
        );
    }

    #[Route('/{series}/{season}/set_seen_all/', name: 'app_series_show_seen_adds_all', methods: ['GET'])]
    public function addSeenAll(Series $series, Season $season, EntityManagerInterface $entityManager): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');
        if ($numPage == null) {
            $numPage = 1;
        }

        $serie = $entityManager->getRepository(Series::class)->findOneBy(['id' => $series->getId()]);
        $seasons = $entityManager->getRepository(Season::class)->findBy(['series' => $serie, 'number' => $season]);
        $episodes = $entityManager->getRepository(Episode::class)->findBy(['season' => $seasons]);
        $alreadyIn = true;
        foreach ($episodes as $episode){
            if (!$this->getUser()->getEpisode()->contains($episode)) {
                $alreadyIn = false;
            }
        }
        if ($alreadyIn){
            foreach ($episodes as $episode){
                $this->getUser()->removeEpisode($episode);
            }
        }else {
            foreach ($episodes as $episode){
                $this->getUser()->addEpisode($episode);
            }
        }
        $entityManager->flush();
        if(!$alreadyIn){
            return $this->redirectToRoute('app_series_show_adds', ['series' => $series->getId(), 'numPage' => $numPage, 'yesno' => 1, 'redirect'=> 1]);
        } else {
            return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage]);
        }
    }


    #[Route('/series/add/', name: 'app_admin_user_series_new', methods: ['GET', 'POST'])]
    public function addSeries(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SerieAddFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $title = $data['title'];
            $title = str_replace(" ", "&", $title);
            // Demande à l'api http://www.omdbapi.com/ pour les series avec le code apiKey = 42404c61
            $url = "http://www.omdbapi.com/?apikey=42404c61&s=" . $title ."&type=series&r=json";
            $obj = json_decode(file_get_contents($url));
            $series = [];
            $array_obj = (array) $obj;
            if ($array_obj['Response'] == "False") {
                echo "<script> alert('Il n'y a pas de série correspondant à cette recherche);</script>";
            } else {
                foreach ($obj->Search as $serie) {
                    $series[$serie->Title]= $serie->imdbID;
                }
            }
            if ($series == []) {
                $this->addFlash('error', "Il n'y a pas de série correspondant à cette recherche");
                return $this->redirectToRoute('admin', ['error' => "No series found with this title"],
                Response::HTTP_SEE_OTHER
                );
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
        // Vérifie si une serie est déjà dans la base de données, si c'est le cas alors elle est mise à jour

        $url = "http://www.omdbapi.com/?apikey=42404c61&i=" . $imdb ."&type=series&r=json";
        $obj = json_decode(file_get_contents($url));
        $serie = $entityManager->getRepository(Series::class)->findOneBy(['imdb' => $imdb]);
        if ($serie != null) {
            $serie->setTitle($obj->Title);
            if (str_contains($obj->Year, "–")) {
                $years=explode("–", $obj->Year);
                $yearStart = $years[0];
                $yearEnd= $years[1];
            } else {
                $yearStart = $obj->Year;
                $yearEnd= -10;
            }
            $serie->setAwards($obj->Awards);
            $serie->setYearEnd((int)$yearEnd);
            $yearStart = (int)$yearStart;
            $serie->setYearStart($yearStart);
            if ($obj->totalSeasons == "N/A") {
                $obj->totalSeasons = null;
            } else {
                $obj->totalSeasons = (int)$obj->totalSeasons;
                $i=0;
                while ($i < $obj->totalSeasons && $entityManager->getRepository(Season::class)->findOneBy(['number' => $i+1, 'series' => $serie]) == null){
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
            echo "<script> alert('La série " . $obj->Title . " a bien été modifiée !');
            window.location.href = 'http://127.0.0.1:8000/admin';
            </script>";
        }else {
            $serie = new Series();
            $serie->setTitle($obj->Title);
            if (str_contains($obj->Year, "–")) {
                $years=explode("–", $obj->Year);
                $yearStart = $years[0];
                $yearEnd= $years[1];
            } else {
                $yearStart = $obj->Year;
                $yearEnd= -10;
            }
            $serie->setAwards($obj->Awards);
            // Converti l'année en entier
            $yearEnd = (int)$yearEnd;
            $yearStart = (int)$yearStart;
            $serie->setYearStart($yearStart);
            if ($obj->totalSeasons == "N/A") {
                $obj->totalSeasons = null;
            } else {
                $obj->totalSeasons = (int)$obj->totalSeasons;
                for ($i=0; $i<$obj->totalSeasons; $i++) {
                    $season = new Season();
                    $season->setNumber($i+1);
                    $season->setSeries($serie);
                    $entityManager->persist($season);
                }
            }
            if ($obj->Poster != "N/A") {
                $poster = file_get_contents($obj->Poster);
                $serie->setPoster($poster);
            }
            
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
            echo "<script> alert('La série " . $obj->Title . " a bien été ajoutée !');
            window.location.href = 'http://127.0.0.1:8000/admin';
            </script>";
        }
      
    }
    #[Route('{series}/{userid}/delete/rating_user', name: 'app_series_delete_rating_user', methods: ['GET'])]
    public function deleteRatingUserAction(Series $series, $userid,  EntityManagerInterface $entityManager): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');
        if ($numPage == null) {
            $numPage = 1;
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $userid]);

        if ($this->getUser()->getisAdmin() == 0 ){
            return $this->redirectToRoute('app_series_show', ['id' => $series->getId(),
             'numPage' => $numPage], Response::HTTP_SEE_OTHER);
        }

        $rating = $entityManager->getRepository(Rating::class)->findOneBy(['user' => $user, 'series' => $series]);
        if ($rating != null){
            $entityManager->remove($rating);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_series_show', ['id' => $series->getId(),
         'numPage' => $numPage], Response::HTTP_SEE_OTHER);
    }

    #[Route('{series}/{userid}/approve/rating_user', name: 'app_series_approve_rating_user', methods: ['GET'])]
    public function approveRatingUser(Series $series, $userid, EntityManagerInterface $entityManager): Response
    {

        $numPage = Request::createFromGlobals()->query->get('numPage');
        if ($numPage == null) {
            $numPage = 1;
        }
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $userid]);

        if ($this->getUser()->getisAdmin() == 0 ){
            return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage],
             Response::HTTP_SEE_OTHER
            );
        }

        $rating = $entityManager->getRepository(Rating::class)->findOneBy(['user' => $user, 'series' => $series]);
        if ($rating != null){
            $rating->setVerified(1);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_series_show', ['id' => $series->getId(), 'numPage' => $numPage],
         Response::HTTP_SEE_OTHER);
    }
    
}