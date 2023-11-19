<?php

namespace App\Entity;

use App\Repository\GestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
class Gestionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $telephone = null;

        #[ORM\OneToMany(mappedBy: 'gestionnaire', targetEntity: TransfertStock::class)]
    private Collection $transfertStocks;

        #[ORM\OneToMany(mappedBy: 'gestionnaire', targetEntity: Magasinier::class)]
        private Collection $magasiniers;

        #[ORM\OneToMany(mappedBy: 'gestionnaire', targetEntity: Magasin::class)]
        private Collection $magasins;

    public function __construct()
    {
        $this->transfertStocks = new ArrayCollection();
        $this->magasiniers = new ArrayCollection();
        $this->magasins = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

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
            $transfertStock->setGestionnaire($this);
        }

        return $this;
    }

    public function removeTransfertStock(TransfertStock $transfertStock): static
    {
        if ($this->transfertStocks->removeElement($transfertStock)) {
            // set the owning side to null (unless already changed)
            if ($transfertStock->getGestionnaire() === $this) {
                $transfertStock->setGestionnaire(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->prenom." ".$this->nom." - ".$this->email;
    }

    /**
     * @return Collection<int, Magasinier>
     */
    public function getMagasiniers(): Collection
    {
        return $this->magasiniers;
    }

    public function addMagasinier(Magasinier $magasinier): static
    {
        if (!$this->magasiniers->contains($magasinier)) {
            $this->magasiniers->add($magasinier);
            $magasinier->setGestionnaire($this);
        }

        return $this;
    }

    public function removeMagasinier(Magasinier $magasinier): static
    {
        if ($this->magasiniers->removeElement($magasinier)) {
            // set the owning side to null (unless already changed)
            if ($magasinier->getGestionnaire() === $this) {
                $magasinier->setGestionnaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins->add($magasin);
            $magasin->setGestionnaire($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        if ($this->magasins->removeElement($magasin)) {
            // set the owning side to null (unless already changed)
            if ($magasin->getGestionnaire() === $this) {
                $magasin->setGestionnaire(null);
            }
        }

        return $this;
    }
}