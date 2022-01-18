<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    public function index(CountryRepository $countryRepository,EventRepository $eventRepository, Request $request): Response
    {

        $countries = $countryRepository->findAll();

        $form = $this->createFormBuilder()
            ->add('city', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Please write a city']),
                'attr' => [
                    'placeholder'=>'City (Ex : Salvador)',
                    'class' => 'form-control__city'
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'All categories'
            ])
            ->add('date', DateType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        // we get the search input content
        $query = $form->get('city')->getData();

        // we get the select content 
        $category = $form->get('category')->getData();

        // we get the select content 
        $date = $form->get('date')->getData();
        // dd($date);
        

        // if category is empty, we send a enpty string to the method
        if (empty($category)) {
            $category='';
        }

        // 2) we get all the matching results
        $results = $eventRepository->searchEventByCity($query, $category, $date);
        // dump($results);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('home/index.html.twig', [
                'countries' => $countries,
                'form' => $form->createView(),
                'query' => $query,
                'results' => $results
            ]);
        }

        return $this->render('home/index.html.twig', [
            'countries' => $countries,
            'form' => $form->createView()
        ]);
    }
}