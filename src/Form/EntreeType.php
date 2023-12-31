<?php

namespace App\Form;

use App\Entity\Entree;
use App\Repository\MagasinierRepository;
use App\Repository\MagasinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    private $security;
    private $magasinRepository;
    private $magasinierRepository;

    public function __construct(Security $security, MagasinRepository $magasinRepository, MagasinierRepository $magasinierRepository)
    {
        $this->security = $security;
        $this->magasinRepository = $magasinRepository;
        $this->magasinierRepository = $magasinierRepository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $magasin = $this->magasinRepository->findOneBy(["magasinier" => $this->magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()])]);
        // dd($magasin);
        $builder
            ->add('num_be')
            ->add('qteEntree')
            // ->add('transfert')
            ->add('prix')
            // ->add('date')
            ->add('produit', EntityType::class, [
                'class' => 'App\Entity\Produit', // Replace with the actual class of your Product entity
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($magasin) {
                    return $er->createQueryBuilder('p')
                        ->where('p.magasin = :magasin')
                        ->setParameter('magasin', $magasin);
                },
                'placeholder' => 'Choisir un produit',
            

                // 'choice_label' => 'name', // Replace with the property of your Product entity to display
            ])
            ->add('fournisseur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entree::class,
        ]);
    }
}