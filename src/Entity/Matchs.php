<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matchs
 *
 * @ORM\Table(name="matchs", indexes={@ORM\Index(name="IDX_6B1E604150E777D5", columns={"nom_poule"})})
 * @ORM\Entity
 */
class Matchs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_match", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe_a", type="string", length=50, nullable=false)
     */
    private $nomEquipeA;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_equipe_b", type="string", length=50, nullable=false)
     */
    private $nomEquipeB;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_match", type="date", nullable=false)
     */
    private $dateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="emplacement", type="string", length=50, nullable=false)
     */
    private $emplacement;

    /**
     * @var \Poule
     *
     * @ORM\ManyToOne(targetEntity="Poule")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nom_poule", referencedColumnName="nom_poule")
     * })
     */
    private $nomPoule;

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function getNomEquipeA(): ?string
    {
        return $this->nomEquipeA;
    }

    public function setNomEquipeA(string $nomEquipeA): self
    {
        $this->nomEquipeA = $nomEquipeA;

        return $this;
    }

    public function getNomEquipeB(): ?string
    {
        return $this->nomEquipeB;
    }

    public function setNomEquipeB(string $nomEquipeB): self
    {
        $this->nomEquipeB = $nomEquipeB;

        return $this;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(\DateTimeInterface $dateMatch): self
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getNomPoule(): ?Poule
    {
        return $this->nomPoule;
    }

    public function setNomPoule(?Poule $nomPoule): self
    {
        $this->nomPoule = $nomPoule;

        return $this;
    }


}
