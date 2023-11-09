<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagasinRepository::class)]
class Magasin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'magasinOrigine', targetEntity: TransfertStock::class)]
    private Collection $transfertStocks;

    #[ORM\OneToOne(inversedBy: 'magasin', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Magasinier $magasinier = null;

    #[ORM\OneToMany(mappedBy: 'magasin', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->transfertStocks = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, TransfertStock>
     */
    public function getTransfertStocks(): Collection
    {
        return $this->transfertStocks;
    }

    public function addTransfertStock(TransfertStock $transfertStock): static
    {
        if (!$this->transfertStocks->contains($transfertStock)) {
            $this->transfertStocks->add($transfertStock);
            $transfertStock->setMagasinOrigine($this);
        }

        return $this;
    }

    public function removeTransfertStock(TransfertStock $transfertStock): static
    {
        if ($this->transfertStocks->removeElement($transfertStock)) {
            // set the owning side to null (unless already changed)
            if ($transfertStock->getMagasinOrigine() === $this) {
                $transfertStock->setMagasinOrigine(null);
            }
        }

        return $this;
    }

    public function getMagasinier(): ?Magasinier
    {
        return $this->magasinier;
    }

    public function setMagasinier(Magasinier $magasinier): static
    {
        $this->magasinier = $magasinier;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setMagasin($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getMagasin() === $this) {
                $produit->setMagasin(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom." - ". $this->adresse;
    }
}