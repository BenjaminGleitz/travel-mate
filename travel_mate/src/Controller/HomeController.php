<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * home
     * @Route(name="index")
     * @return Response
     */
    public function index(CountryRepository $countryRepository): Response
    {

        $countries = $countryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'countries' => $countries,
        ]);
    }
}
