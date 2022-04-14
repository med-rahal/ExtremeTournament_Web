<?php

namespace App\Entity;

use App\Repository\PouleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PouleRepository::class)
 */
class Poule
{

    /**
     * @ORM\Id
     * @Assert\NotBlank(message=" Name Can't be Empty")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Enter Valid Name (min 5 caracteres) "
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    public $nom_poule ;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="poules")
     * @ORM\JoinColumn(name="idT",referencedColumnName="id_t",nullable=false)
     */
    private $id_t;

    /**
     * @ORM\OneToMany(targetEntity=Matchs::class, mappedBy="poules", orphanRemoval=true)
     */
    private $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPoule(): ?string
    {
        return $this->nom_poule;
    }

    public function setNomPoule(string $nom_poule): self
    {
        $this->nom_poule = $nom_poule;

        return $this;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(string $nom_equipe): self
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getIdT(): ?tournoi
    {
        return $this->id_t;
    }

    public function setIdT(?tournoi $id_t): self
    {
        $this->id_t = $id_t;

        return $this;
    }

    /**
     * @return Collection<int, Matchs>
     */
    public function getMatchs(): Collection
    {
        return $this->matchs;
    }

    public function addMatch(Matchs $match): self
    {
        if (!$this->matchs->contains($match)) {
            $this->matchs[] = $match;
            $match->setPoules($this);
        }

        return $this;
    }

    public function removeMatch(Matchs $match): self
    {
        if ($this->matchs->removeElement($match)) {
            // set the owning side to null (unless already changed)
            if ($match->getPoules() === $this) {
                $match->setPoules(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->id_t;
    }
}
