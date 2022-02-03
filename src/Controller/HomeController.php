<?php

namespace App\Controller;

use App\Entity\Friends;
use App\Repository\FriendsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        UserRepository  $userRepository,
        FriendsRepository $friendsRepository

    ): Response
    {


        $utilisateurs = $userRepository->findAll();
        unset($utilisateurs[array_search($this->getUser(), $utilisateurs)]);
        $nbUtilisateurs = count($utilisateurs);
        $nbFriends=0;

        if (TRUE === $this->isGranted('ROLE_USER')) {

            $currentUser = $this->getUser()->getUserIdentifier();
            $idUser = $userRepository->findOneBy(['username'=>$currentUser]);

            $friends =$idUser->getFriends();
            $array = array();
            foreach ($friends as $fr){

                if($idUser->isFriend($fr)){
                    $int = $fr->getId();
                    array_push($array,$int);
                }
            }

            $nbFriends=count($friends)-1;


        }

        return $this->render('home/index.html.twig',
        [
           'nbutilisateurs'=>$nbUtilisateurs,
            'utilisateurs'=>$utilisateurs,
            'nbFriends'=>$nbFriends,
            'array'=>$array

        ]

        );
    }

    #[Route('/filter/{language}', name: 'filter_language')]
    public function filter(
        $language,
        UserRepository $userRepository
    ){

        $utilisateurs = $userRepository->findBy([$language=>true]);
        unset($utilisateurs[array_search($this->getUser(), $utilisateurs)]);






        $currentUser = $this->getUser()->getUserIdentifier();
        $idUser = $userRepository->findOneBy(['username'=>$currentUser]);
        $friends =$idUser->getFriends();

        $array = array();
        foreach ($friends as $fr){

            if($idUser->isFriend($fr)){
                $int = $fr->getId();
                array_push($array,$int);
            }
        }





        $nbFriends=count($friends)-1;

        foreach ($utilisateurs as $e){

            switch ($language){

                case 'isAngular':
                    $e->setIsSpring(false);
                    $e->setIsSvelte(false);
                    $e->setIsVue(false);
                    break;

                case 'isSvelte':
                    $e->setIsSpring(false);
                    $e->setIsAngular(false);
                    $e->setIsVue(false);
                    break;

                case 'isSpring':
                    $e->setIsSvelte(false);
                    $e->setIsAngular(false);
                    $e->setIsVue(false);
                    break;

                case 'isVue':
                    $e->setIsSvelte(false);
                    $e->setIsAngular(false);
                    $e->setIsSpring(false);
                    break;


            }

        }
        $lesUtilisateur = $userRepository->findAll();
        $nbUtilisateurs = count($lesUtilisateur);


        return $this->render('home/index.html.twig',
            [
                'nbutilisateurs'=>$nbUtilisateurs,
                'utilisateurs'=>$utilisateurs,
                'nbFriends'=>$nbFriends,
                'array'=>$array
            ]
        );

    }
    #[Route('/add/{id}', name: 'add_friend')]
    public function addfirend(
        $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        FriendsRepository $friendsRepository
    ){

        $utilisateurs = $userRepository->findAll();
        $nbUtilisateurs=count($utilisateurs);
        unset($utilisateurs[array_search($this->getUser(), $utilisateurs)]);


        $currentUser = $this->getUser()->getUserIdentifier();
        $idUser = $userRepository->findOneBy(['username'=>$currentUser]);

        $friend=$friendsRepository->findOneBy(['id'=>$id]);



        $idUser->addFriend($friend);

        $entityManager->persist($friend);
        $entityManager->flush();

        $friends = $idUser->getFriends();
        $nbFriends=count($friends)-1;

        $lesUtilisateur = $userRepository->findAll();
        $nbUtilisateurs = count($utilisateurs);

        $friends = $idUser->getFriends();
        $array = array();
        foreach ($friends as $fr){

            if($idUser->isFriend($fr)){
                $int = $fr->getId();
                array_push($array,$int);
            }
        }


        return $this->render('home/index.html.twig',
            [
                'nbutilisateurs'=>$nbUtilisateurs,
                'utilisateurs'=>$utilisateurs,
                'nbFriends'=>$nbFriends,
                'array'=>$array
            ]
        );

    }


}
