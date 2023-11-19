<?php

namespace App\Form;

use App\Entity\TransfertStock;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertStockType extends AbstractType
{
    private $security;
    private $gestionnaireRepository;
    private $magasinRepository;
    public function __construct(Security $security, GestionnaireRepository $gestionnaireRepository, MagasinRepository $magasinRepository)
    {
        $this->security = $security;
        $this->gestionnaireRepository = $gestionnaireRepository;
        $this->magasinRepository = $magasinRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $gestionnaire = $this->gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        $magasin = $this->magasinRepository->findOneBy(["gestionnaire" => $gestionnaire]);
        $builder
            // ->add('date')
            // ->add('gestionnaire')
            ->add('produit', EntityType::class, [
                'class' => 'App\Entity\Produit',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($magasin) {
                    return $er->createQueryBuilder('p')
                        ->where('p.magasin = :magasin')
                        ->setParameter('magasin', $magasin);
                },
            ])
            ->add('qte')
            ->add('magasinOrigine', EntityType::class, [
                'class' => 'App\Entity\Magasin',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($gestionnaire) {
                    return $er->createQueryBuilder('p')
                        ->where('p.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaire);
                },
                'placeholder' => "Choisir le magasin d'origine",
            ])
            ->add('magasinDestination', EntityType::class, [
                'class' => 'App\Entity\Magasin',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($gestionnaire) {
                    return $er->createQueryBuilder('p')
                        ->where('p.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaire);
                },
                'placeholder' => "Choisir le magasin de destination",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransfertStock::class,
        ]);
    }
}