<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\MagasinierRepository;
use App\Repository\MagasinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    private $security;
    private $magasinierRepository;
    private $magasinRepository;
    public function __construct(Security $security, MagasinierRepository $magasinierRepository, MagasinRepository $magasinRepository)
    {
        $this->security = $security;
        $this->magasinierRepository = $magasinierRepository;
        $this->magasinRepository = $magasinRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $magasinier = $this->magasinierRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        $magasin = $this->magasinRepository->findOneBy(["magasinier" => $magasinier]);

        $builder
            // ->add('date')
            // ->add('etat')
            // ->add('magasinier')
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($magasin) {
                    return $er->createQueryBuilder('p')
                        ->where('p.magasin = :magasin')
                        ->setParameter('magasin', $magasin);
                },
                'placeholder' => 'Choisir un produit',
            ])
            ->add('qte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}