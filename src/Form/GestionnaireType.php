<?php

namespace App\Form;

use App\Entity\Gestionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('date', BirthdayType::class, [
                'widget' => 'choice', // Utilisez le widget de type choix pour la date de naissance
                'format' => 'dd MMMM yyyy', // Le format dans lequel la date sera stockée
            ])
            ->add('email')
            ->add('password')
            ->add('telephone')
            // ->add('roles', ChoiceType::class, [
            //     "multiple" => false,
            //     "mapped" => false,
            //     "expanded" => true,
            //     "choices" => [
            //         "Magasinier" => "ROLE_MAGASINIER",
            //         "Gestionnaire" => "ROLE_GESTIONNAIRE",
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gestionnaire::class,
        ]);
    }
}