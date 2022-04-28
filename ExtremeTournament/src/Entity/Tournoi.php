<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournoi
 *
 * @ORM\Table(name="tournoi")
 * @ORM\Entity
 */
class Tournoi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_t", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idT;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_t", type="string", length=50, nullable=false)
     */
    private $nomT;

    /**
     * @var string
     *
     * @ORM\Column(name="emplacement_t", type="string", length=50, nullable=false)
     */
    private $emplacementT;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_t", type="date", nullable=false)
     */
    private $dateT;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    public function getIdT(): ?int
    {
        return $this->idT;
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
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
