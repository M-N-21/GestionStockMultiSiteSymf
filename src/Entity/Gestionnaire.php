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

    public function __construct()
    {
        $this->transfertStocks = new ArrayCollection();
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
}