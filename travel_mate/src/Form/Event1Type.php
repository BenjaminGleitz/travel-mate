<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Event;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Event1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startAt', DateTimeType::class,[
                'label' => 'Commence le',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'empty_data' => '',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'All categories'
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => 'All cities'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
