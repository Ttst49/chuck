<?php

namespace App\Controller;

use App\Entity\Joke;
use App\Repository\JokeRepository;
use App\Service\ChuckNorrisJoke;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route("/")]
class HomeController extends AbstractController
{
    #[Route("/", name: "app_home_httprequest")]
    public function httpRequest(ChuckNorrisJoke $chuckNorrisJoke){

        $response = [];

        for ($i=0; $i<10;$i++){

            $test = $chuckNorrisJoke->fetchChuckInformation();

            $response[] = $test;
        }



        return $this->render("home/index.html.twig",[
            "jokes"=>$response
        ]);
    }


    #[Route("/saveJoke/{value}",name: "app_home_savejoke")]
    public function saveJoke($value = null, EntityManagerInterface $manager, JokeRepository $repository){

        $joke = new Joke();
        $joke->setContent($value);
        $joke->setOfUser($this->getUser());

        $manager->persist($joke);
        $manager->flush();

        return $this->redirectToRoute("app_home_httprequest");
    }

    #[Route("/indexFavorites",name: "app_home_showfavorites")]
    public function showFavorites(JokeRepository $repository){


        return $this->render("home/indexFavorites.html.twig",[
            "jokes"=>$this->getUser()->getJokes()
        ]);
    }
}
