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
use App\Form\UserCreateFormType;
use App\Form\UserSearchFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    #[Route('/user/admin/new', name: 'app_admin_user_new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(UserCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            for ($i = 0; $i < $data['number']; $i++) {
                $user = new User();
                $user->setName($data['name'] . $i);
                $email = $data['email'];
                $email = explode('@', $email);
                $user->setEmail($email[0] . $i . '@' . $email[1]);
                $user->setPassword($data['name'] . $i);
                $user->setRoles(['ROLE_USER']);
                $country = $entityManager->getRepository(Country::class)->findOneBy(['name' => "France" ]);
                $user->setCountry($country);
                $user->setIsBot(true);
                $entityManager->persist($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/user/admin/delete', name: 'app_admin_user_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request): Response
    {
        // delete all the users with isBot to true
        $user = $entityManager->getRepository(User::class)->findBy(['isBot' => true]);
        foreach ($user as $u) {
            $entityManager->remove($u);
        }
        $entityManager->flush();
        return $this->redirectToRoute('admin');
    }

    
}
