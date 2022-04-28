<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forum
 *
 * @ORM\Table(name="forum", indexes={@ORM\Index(name="IDX_852BBECDB72EAA8E", columns={"id_publication"})})
 * @ORM\Entity
 */
class Forum
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_forum", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idForum;

    /**
     * @var string
     *
     * @ORM\Column(name="description_f", type="string", length=150, nullable=false)
     */
    private $descriptionF;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="id_commentaire", type="integer", nullable=false)
     */
    private $idCommentaire;

    /**
     * @var \Publication
     *
     * @ORM\ManyToOne(targetEntity="Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_publication", referencedColumnName="id_publication")
     * })
     */
    private $idPublication;

    public function getIdForum(): ?int
    {
        return $this->idForum;
    }

    public function getDescriptionF(): ?string
    {
        return $this->descriptionF;
    }

    public function setDescriptionF(string $descriptionF): self
    {
        $this->descriptionF = $descriptionF;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }

    public function setIdCommentaire(int $idCommentaire): self
    {
        $this->idCommentaire = $idCommentaire;

        return $this;
    }

    public function getIdPublication(): ?Publication
    {
        return $this->idPublication;
    }

    public function setIdPublication(?Publication $idPublication): self
    {
        $this->idPublication = $idPublication;

        return $this;
    }


}
