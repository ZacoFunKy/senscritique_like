<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\User;
use App\Entity\Country;
use App\Entity\Rating;
use App\Entity\UserSearch;
use App\Form\UpdateFormType;
use App\Form\UserSearchFormType;
class UserController extends AbstractController
{
    #[Route('/user/favoris', name: 'app_user_favorite')]
    public function index(): Response
    {
        $numPage = Request::createFromGlobals()->query->get('numPage');
        $numPage = $numPage ? $numPage : 1;

        return $this->render('user/favorite.html.twig', [
            'controller_name' => 'UserController',
            'numPage' => $numPage,
        ]);
    }

    #[Route('/user/history', name: 'app_user_history')]
    public function history(EntityManagerInterface $entityManager, Request $request,
    PaginatorInterface $paginator): Response
    {
        $episodes = $this->getUser()->getEpisode();
        $episodes = $paginator->paginate($episodes, $request
        ->query->getInt('page', 1, 10));

        $numPage = Request::createFromGlobals()->query->get('numPage');
        $numPage = $numPage ? $numPage : 1;


        return $this->render('user/history.html.twig', [
            'episodes' => $episodes,
            'numPage' => $numPage,
        ]);
    }

    #[Route('/user/profile/{id}', name: 'app_user_profile')]
    public function profile( $id, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(UpdateFormType::class, $this->getUser());
        $form->handleRequest($request);

        $countries = $entityManager->getRepository(Country::class)->findAll();
        $user = $entityManager->getRepository(User::class)->findBy(['id' => $id])[0];
        $ratings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
            if ($file) {
                $fileContent = file_get_contents($file);
                $this->getUser()->setPhoto($fileContent);
            }
            $entityManager->persist($this->getUser());
            
            $entityManager->flush();
            return $this->redirectToRoute('app_user_profile');
        }

        $series = $user->getSeries();
        $userEpisode = $user->getEpisode();

        $ratings = $paginator->paginate(
            $ratings, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );


        $series = $paginator->paginate(
            $series, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        $episodes = $paginator->paginate(
            $userEpisode, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'countries' => $countries,
            'ratings' => $ratings,
            'user' => $user,
            'series' => $series,
            'episodes' => $episodes,
        ]);
    }

    #[Route('/photo/{id}', name: 'photo_user', methods: ['GET'])]
    public function showPoster(User $user): Response
    {
    return new Response(stream_get_contents($user->getPhoto()), 200, array ('Content-type' => 'image/jpeg'));
    }


    #[Route('/user/all', name: 'app_user_show_all')]
    public function allUser(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        
        $users = $paginator->paginate($users, $request
        ->query->getInt('page', 1, 10));

        $userSearch = new UserSearch();
        $form = $this->createForm(UserSearchFormType::class, $userSearch);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // get the users that contain the search string in their name
            $queryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('u');
            $queryBuilder->where('u.name LIKE :name')
                ->setParameter('name', '%' . $userSearch->getNom() . '%');
            $users = $queryBuilder->getQuery()->getResult();
        }
    
        return $this->render('user/all.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'pagination' => false,
        ]);
    }

    
}
