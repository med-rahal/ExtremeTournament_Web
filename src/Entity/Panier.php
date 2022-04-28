<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="IDX_24CC0DF26B3CA4B", columns={"id_user"}), @ORM\Index(name="IDX_24CC0DF29C1263CD", columns={"ref_prod"})})
 * @ORM\Entity
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanier;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="total_panier", type="float", precision=10, scale=0, nullable=false)
     */
    private $totalPanier;

    /**
     * @var int
     *
     * @ORM\Column(name="ref_prod", type="integer", nullable=false)
     */
    private $refProd;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTotalPanier(): ?float
    {
        return $this->totalPanier;
    }

    public function setTotalPanier(float $totalPanier): self
    {
        $this->totalPanier = $totalPanier;

        return $this;
    }

    public function getRefProd(): ?int
    {
        return $this->refProd;
    }

    public function setRefProd(int $refProd): self
    {
        $this->refProd = $refProd;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
