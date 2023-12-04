<?php

namespace App\Entity;

use App\Repository\EntreeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreeRepository::class)]
class Entree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_be = null;

    #[ORM\Column]
    private ?int $qteEntree = null;

    #[ORM\Column]
    private ?bool $transfert = null;

    #[ORM\Column(nullable: true)]
    private ?int $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'entrees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $fournisseur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumBe(): ?int
    {
        return $this->num_be;
    }

    public function setNumBe(int $num_be): static
    {
        $this->num_be = $num_be;

        return $this;
    }

    public function getqteEntree(): ?int
    {
        return $this->qteEntree;
    }

    public function setqteEntree(int $qteEntree): static
    {
        $this->qteEntree = $qteEntree;

        return $this;
    }

    public function isTransfert(): ?bool
    {
        return $this->transfert;
    }

    public function setTransfert(bool $transfert): static
    {
        $this->transfert = $transfert;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): static
    {
        $this->prix = $prix;

        return $this;
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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?string $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
    
}