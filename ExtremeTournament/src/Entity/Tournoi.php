<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 * @UniqueEntity("nomT")
 */
class Tournoi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public  $id_t;

    /**
     *
     *
     * @Assert\NotBlank(message="Name is required")
     * @ORM\Column(type="string", length=255 , unique=true)
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+",
     *      message = "Name should Contains only letters ")
     * )
     */
    private $nomT;
    /**
     * @Assert\NotBlank(message=" Name of Location is required")
     *  @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "[a-zA-Z]+",
     *      message = "Location  should Contains only letters ")
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $emplacementT;

    /**
     *
     * @Assert\NotBlank(message="Date Can't be null")
     *  @var string A "Y-m-d" formatted value
     * @ORM\Column(type="date")
     */
    private $dateT;

    /**@Assert\NotBlank(message="Number  Can't be null")
     * @Assert\Positive
     * @ORM\Column(type="integer")
     */
    public $id_user;

    /**
     * @ORM\OneToMany(targetEntity=Poule::class, mappedBy="id_t", orphanRemoval=true)
     */
    private $poules;

    public function __construct()
    {
        $this->poules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdT(): ?int
    {
        return $this->idT;
    }

    public function setIdT(int $idT): self
    {
        $this->idT = $idT;

        return $this;
    }

    public function getNomT(): ?string
    {
        return $this->nomT;
    }

    public function setNomT(string $nomT): self
    {
        $this->nomT = $nomT;

        return $this;
    }

    public function getEmplacementT(): ?string
    {
        return $this->emplacementT;
    }

    public function setEmplacementT(string $emplacementT): self
    {
        $this->emplacementT = $emplacementT;

        return $this;
    }

    public function getDateT(): ?\DateTimeInterface
    {
        return $this->dateT;
    }

    public function setDateT(\DateTimeInterface $dateT): self
    {
        $this->dateT = $dateT;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return Collection<int, Poule>
     */
    public function getPoules(): Collection
    {
        return $this->poules;
    }

    public function addPoule(Poule $poule): self
    {
        if (!$this->poules->contains($poule)) {
            $this->poules[] = $poule;
            $poule->setIdT($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): self
    {
        if ($this->poules->removeElement($poule)) {
            // set the owning side to null (unless already changed)
            if ($poule->getIdT() === $this) {
                $poule->setIdT(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id_t;
    }
}
