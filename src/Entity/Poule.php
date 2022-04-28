<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poule
 *
 * @ORM\Table(name="poule", indexes={@ORM\Index(name="IDX_FA1FEB40D5D012F3", columns={"idT"})})
 * @ORM\Entity
 */
class Poule
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom_poule", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nomPoule;

    /**
     * @var int
     *
     * @ORM\Column(name="idT", type="integer", nullable=false)
     */
    private $idt;

    public function getNomPoule(): ?string
    {
        return $this->nomPoule;
    }

    public function getIdt(): ?int
    {
        return $this->idt;
    }

    public function setIdt(int $idt): self
    {
        $this->idt = $idt;

        return $this;
    }


}
