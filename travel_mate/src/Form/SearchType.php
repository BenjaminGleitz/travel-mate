<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'placeholder'=>'Event\'s Name'
            ],
            'constraints' => new NotBlank(['message' => 'nope'])
        ])
        ->add('country', EntityType::class, [
            'class' => Country::class,
            'placeholder' => 'Country',
            'required' => false,
            'query_builder' => function(CountryRepository $countryRepository) {
                return $countryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
            }
        ])
        ->add('city', EntityType::class, [
            'class' => City::class,
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
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
