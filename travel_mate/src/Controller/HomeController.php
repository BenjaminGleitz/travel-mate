<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
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
    public function index(CountryRepository $countryRepository, Request $request): Response
    {

        $countries = $countryRepository->findAll();

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder'=>'Event\'s Name'
                ],
                'constraints' => new NotBlank(['message' => 'nope'])
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'placeholder' => 'Country',
                'query_builder' => function(CountryRepository $countryRepository) {
                    return $countryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'disabled' => true,
                'placeholder' => 'City',
                'query_builder' => function(CityRepository $cityRepository) {
                    return $cityRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
            ->add('category', TextType::class, [
                'attr' => [
                    'placeholder'=>'Category'
                ],
                'constraints' => new NotBlank(['message' => 'nope'])
            ])
            ->add('date', DateType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('home/index.html.twig', [
            'countries' => $countries,
            'form' => $form->createView()
        ]);
    }
}
