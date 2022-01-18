<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Event;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
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
            ->add('description')
            ->add('startAt')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('country', EntityType::class, [
                        'class' => Country::class,
                        'mapped' => false,
                        'required' => false,
                        'placeholder' => 'Country',
                        'query_builder' => function(CountryRepository $countryRepository) {
                            return $countryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                        }
                    ])
            // ->add('city')
            // ->add('category')
        ;
        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                dd($event->getForm());
                dd($event->getData());
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
