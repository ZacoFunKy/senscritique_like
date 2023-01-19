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
use App\Entity\Series;
use App\Entity\Rating;
use App\Entity\UserSearch;
use App\Form\UpdateFormType;
use App\Form\UserCreateFormType;
use App\Form\UserSearchFormType;
use App\Form\CommentNewFormType;

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
    public function history(Request $request, PaginatorInterface $paginator): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_home');
        }
        $episodes = $this->getUser()->getEpisode();
        $episodes = $paginator->paginate($episodes, $request->query->getInt('page', 1, 10));

        $numPage = Request::createFromGlobals()->query->get('numPage');
        $numPage = $numPage ? $numPage : 1;

        return $this->render('user/history.html.twig', [
            'episodes' => $episodes,
            'numPage' => $numPage,
        ]);
    }

    #[Route('/user/profile/{id}', name: 'app_user_profile')]
    public function profile(
        $id,
        EntityManagerInterface $entityManager,
        Request $request,
        PaginatorInterface $paginator
     ): Response
    {
        $form = $this->createForm(UpdateFormType::class, $this->getUser());
        $form->handleRequest($request);

        // tous les country
        $countries = $entityManager->getRepository(Country::class)->findAll();
        // le user qui a pour id : $id
        $user = $entityManager->getRepository(User::class)->findBy(['id' => $id])[0];
        // tous les rating qui ont pour user : $user et qui sont vérifiés
        $ratings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user, 'verified' => true]);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
            if ($file) {
                $fileContent = file_get_contents($file);
                $this->getUser()->setPhoto($fileContent);
            }
            $password = $form->get('password')->getData();
            if($password != null){
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $this->getUser()->setPassword($hash);
            }
            $entityManager->persist($this->getUser());
            
            $entityManager->flush();
            return $this->redirectToRoute('app_user_profile', [
                'id' => $id
                ]);
        }

        // séries suivies de l'utilisateur
        $series = $user->getSeries();
        // épisodes vus de l'utilisateur
        $userEpisode = $user->getEpisode();

        //Permet de paginer les notes données par l'utilisateur
        $ratings = $paginator->paginate(
            $ratings,
            $request
                ->query
                ->getInt('page1', 1),
            3,
            array('pageParameterName' => 'page1',)
        );

        //Permet de paginer les séries vues par l'utilisateur
        $series = $paginator->paginate(
            $series,
            $request
                ->query
                ->getInt('page2', 1),
            4,
            array('pageParameterName' => 'page2',)
        );

        //Permet de paginer les épisodes vues par l'utilisateur
        $episodes = $paginator->paginate(
            $userEpisode,
            $request
                ->query
                ->getInt('page3', 1),
            4,
            array('pageParameterName' => 'page3',)
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
        return new Response(
            stream_get_contents($user->getPhoto()),
            200,
            array ('Content-type' => 'image/jpeg')
        );
    }

    #[Route('/user/all', name: 'app_user_show_all')]
    public function allUser(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
     ): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        


        $userSearch = new UserSearch();
        $form = $this->createForm(UserSearchFormType::class, $userSearch);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            //Permet d'obtenir les utilisateurs qui possède le string recherché dans leur nom
            $queryBuilder = $entityManager
                ->getRepository(User::class)
                ->createQueryBuilder('u');
            $queryBuilder
                ->where('u.name LIKE :name')
                ->setParameter('name', '%'.$userSearch->getNom().'%');
            $users = $queryBuilder->getQuery()->getResult();
        }
    
        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1, 10)
        );
        
        return $this->render('user/all.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
            'pagination' => false,
        ]);
    }

    #[Route('/user/suspended/{id}/{yesno}', name: 'app_user_suspended')]
    public function suspended($id, $yesno, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findBy(['id' => $id])[0];
        // si l'utilisateur est admin, il ne peut pas être suspendu
        if ($user->getisAdmin()) {
            return $this->redirectToRoute('admin');
        } else {
            $user->setSuspendu($yesno);
            $entityManager->persist($user);
            $entityManager->flush();
            // si on suspend
            if ($yesno == 1) {
                // toutes les critiques de l'utilisateur
                $ratings = $entityManager->getRepository(Rating::class)->findBy(['user' => $id]);
                // pour toutes les critiques
                foreach ($ratings as $rating) {
                    echo $rating->getUser()->getId();
                    // supprimer les critiques
                    $entityManager->remove($rating);
                }
                // valide la supression
                $entityManager->flush();
            }
            // toutes les critiques de l'utilisateur
            $ratings = $entityManager->getRepository(Rating::class)->findBy(['user' => 655]);
            // pour toutes les critiques
            foreach ($ratings as $rating) {
                // supprimer les critiques
                $entityManager->remove($rating);
            }
            return $this->redirectToRoute('admin');
        }
    }

    #[Route('/user/admin/new', name: 'app_admin_user_new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(UserCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // création des nouveaux bots
            for ($i = 0; $i < $data['number']; $i++) {
                $user = new User();
                $name = $data['name'] . $i;
                $user->setName($name);
                $email = $data['email'];
                $email = explode('@', $email);
                $newEmail = $email[0] . $i . '@' . $email[1];
                $newEmailCheck = $entityManager
                    ->getRepository(User::class)
                    ->findBy(['email' => $newEmail]);
                while ($newEmailCheck) {
                    $newEmail = $email[0] . rand(0, 1000) . '@' . $email[1];
                    $newEmailCheck = $entityManager
                        ->getRepository(User::class)
                        ->findBy(['email' => $newEmail]);
                }
                $user->setEmail($newEmail);
                $hash = password_hash($name, PASSWORD_BCRYPT);
                $user->setPassword($hash);
                $user->setRoles(['ROLE_USER']);
                $country = $entityManager
                    ->getRepository(Country::class)
                    ->find(rand(1, 19));
                if ($country) {
                    $user->setCountry($country);
                    $user->setIsBot(true);
                    $entityManager->persist($user);
                }else {
                    while (!$country) {
                        $country = $entityManager
                            ->getRepository(Country::class)
                            ->find(rand(1, 19));
                    }
                    $user->setCountry($country);
                    $user->setIsBot(true);
                    $entityManager->persist($user);
                }
            }
            $entityManager->flush();

            $bot = $entityManager->getRepository(User::class)->findBy(['isBot' => true]);
            echo "<script> alert('Utilisateurs créés, Il y a " . count($bot) . " faux comptes');
                window.location.href = 'http://127.0.0.1:8000/admin';
            </script>";
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/admin/delete', name: 'app_admin_user_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Supprime tous les utilisateurs avec isBot à vrai
        $user = $entityManager
            ->getRepository(User::class)
            ->findBy(['isBot' => true]);
        foreach ($user as $u) {
            $comment = $entityManager
                ->getRepository(Rating::class)
                ->findBy(['user' => $u]);
            foreach ($comment as $c) {
                $entityManager->remove($c);
            }
        }

        $entityManager->flush();
        $user = $entityManager
            ->getRepository(User::class)
            ->findBy(['isBot' => true]);
        foreach ($user as $u) {
            $entityManager->remove($u);
        }
        $entityManager->flush();

        echo "<script> alert('Utilisateurs supprimés');
        window.location.href = 'http://127.0.0.1:8000/admin';
        </script>";
    }

    #[Route('/user/comment/new', name: 'app_admin_user_comment_new')]
    public function new_comment(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CommentNewFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Création des nouvelles critiques
            $commentExemple = array();
            $commentExemple[0] = "C'est vraiment un bon film";
            $commentExemple[1] = "J'ai adoré ce film";
            $commentExemple[2] = "Je n'ai pas aimé ce film";
            $commentExemple[3] = "Je n'ai pas compris ce film";
            $data = $form->getData();
            $user = $entityManager
                ->getRepository(User::class)
                ->findBy(['isBot' => true]);
            if ($user==null) {
                echo "<script> alert('Pas de bot user');
                window.location.href = 'http://127.0.0.1:8000/admin/';
                </script>";
            }
            if (count($user)<$data['number']) {
                echo "<script> alert('Pas assez d'utilisateurs');
                window.location.href = 'http://127.0.0.1:8000/admin/';
                </script>";
            }
            // récupère l'id de la série depuis l'url
            $serie = $request->query->get('id');
            $serie = $entityManager
                ->getRepository(Series::class)
                ->findOneBy(['id' => $serie]);
            for ($i = 0; $i < $data['number']; $i++) {
                $rating = $entityManager
                    ->getRepository(Rating::class)
                    ->findOneBy(['user' => $user[$i], 'series' => $serie]);
                if ($rating==null) {
                    $comment = new Rating();
                    $comment->setUser($user[$i]);
                    $comment->setSeries($serie);
                    $comment->setValue(rand(0, 5));
                    $comment->setDate(new \DateTime());
                    $comment->setComment($commentExemple[rand(0, 3)]);
                    $entityManager->persist($comment);
                }
            }
            $entityManager->flush();

            echo
            "<script>
                alert('Commentaires ajoutés');
                window.location.href = 'http://127.0.0.1:8000/admin/';
            </script>";
        }

        return $this->render('user/commentaire_new.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }

    #[Route('/user/comment/delete', name: 'app_admin_user_comment_delete')]
    public function delete_comment(EntityManagerInterface $entityManager, Request $request): Response
    {
        // supprime tous les commentaires des bots

        // tous les bots
        $user = $entityManager->getRepository(User::class)->findBy(['isBot' => true]);
        // pour tous les bots
        foreach ($user as $u) {
            // tous les commentaires du bot
            $comment = $entityManager->getRepository(Rating::class)->findBy(['user' => $u]);
            // pour tous les commentaires du bot
            foreach ($comment as $c) {
                // supprime le commentaire
                $entityManager->remove($c);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('admin', ['error' => 'Commentaires supprimés']);

    }

    #[Route('/user/count/fake_account', name: 'app_admin_user_count_fake_accounts')]
    public function count_fake_account(EntityManagerInterface $entityManager, Request $request): Response
    {
        // tous les bots
        $user = $entityManager->getRepository(User::class)->findBy(['isBot' => true]);
        echo "<script>
        alert('Il y a " . count($user) . " faux comptes');
        window.location.href='admin';
        </script>";
    }
}
