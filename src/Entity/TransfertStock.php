<?php

namespace App\Entity;

use App\Repository\TransfertStockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransfertStockRepository::class)]
class TransfertStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'transfertStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gestionnaire $gestionnaire = null;

    #[ORM\ManyToOne(inversedBy: 'transfertStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'transfertStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Magasin $magasinOrigine = null;

    #[ORM\ManyToOne(inversedBy: 'transfertStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Magasin $magasinDestination = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): static
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getMagasinOrigine(): ?Magasin
    {
        return $this->magasinOrigine;
    }

    public function setMagasinOrigine(?Magasin $magasinOrigine): static
    {
        $this->magasinOrigine = $magasinOrigine;

        return $this;
    }

    public function getMagasinDestination(): ?Magasin
    {
        return $this->magasinDestination;
    }

    public function setMagasinDestination(?Magasin $magasinDestination): static
    {
        $this->magasinDestination = $magasinDestination;

        return $this;
    }
}
