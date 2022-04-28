<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="ref_prod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $refProd;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="nom_prod", type="string", length=50, nullable=false)
     */
    private $nomProd;

    /**
     * @var float
     *@Assert\Positive
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var int
     * @Assert\PositiveOrZero
     * @ORM\Column(name="total_en_stock", type="integer", nullable=false)
     */
    private $totalEnStock;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="descriptif", type="string", length=100, nullable=false)
     */
    private $descriptif;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="categorie_prod", type="string", length=50, nullable=false)
     */
    private $categorieProd;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1
     *     )
     * @ORM\Column(name="disponibilite", type="string", length=50, nullable=false)
     */
    private $disponibilite;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *     )
     * @ORM\Column(name="image", type="string", length=50, nullable=false)
     */
    private $image;

    public function getRefProd(): ?int
    {
        return $this->refProd;
    }

    public function getNomProd(): ?string
    {
        return $this->nomProd;
    }



    public function setNomProd(string $nomProd): self
    {
        $this->nomProd = $nomProd;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTotalEnStock(): ?int
    {
        return $this->totalEnStock;
    }

    public function setTotalEnStock(int $totalEnStock): self
    {
        $this->totalEnStock = $totalEnStock;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getCategorieProd(): ?string
    {
        return $this->categorieProd;
    }

    public function setCategorieProd(string $categorieProd): self
    {
        $this->categorieProd = $categorieProd;

        return $this;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


}
