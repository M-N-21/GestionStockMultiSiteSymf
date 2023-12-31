<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use App\Repository\MagasinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
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
        // dd($magasin,$magasinier);
        $builder
            ->add('nom')
            ->add('prix')
            ->add('code')
            ->add('qte')
            ->add('seuil')
            // ->add('date')
            // ->add('gestionnaire')
            // ->add('magasinier')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($magasin) {
                    return $er->createQueryBuilder('p')
                        ->where('p.Magasin = :magasin')
                        ->setParameter('magasin', $magasin);
                },
                'placeholder' => 'Choisir une categorie'
            ])
            // ->add('magasin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}