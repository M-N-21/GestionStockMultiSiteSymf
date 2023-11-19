<?php

namespace App\Form;

use App\Entity\Gestionnaire;
use App\Entity\Magasin;
use App\Repository\GestionnaireRepository;
use App\Repository\MagasinierRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MagasinType extends AbstractType
{
    private $security;
    private $magasinierRepository;
    private $gestionnaireRepository;
    public function __construct(Security $security, GestionnaireRepository $gestionnaireRepository, MagasinierRepository $magasinierRepository)
    {
        $this->security = $security;
        $this->gestionnaireRepository = $gestionnaireRepository;
        $this->magasinierRepository = $magasinierRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $gestionnaire = $this->gestionnaireRepository->findOneBy(["email" => $user->getUserIdentifier()]);

        $builder
            ->add('nom')
            ->add('adresse')
            ->add('magasinier', EntityType::class, [
                'class' => 'App\Entity\Magasinier',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($gestionnaire) {
                    return $er->createQueryBuilder('m')
                        ->where('m.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaire);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Magasin::class,
        ]);
    }
}