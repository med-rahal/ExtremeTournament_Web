<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MembreRepository::class)
 */
class Membre
{

    /**
     * @Assert\NotBlank(message=" Name Name can't be null")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Enter a valid nome"
     *
     *     )
     * @ORM\Column(type="string", length=30)
     */
    private $Nom;

    /**
     * @Assert\NotBlank(message=" Prenom Name can't be null")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Enter a valid name "
     *
     *     )
     * @ORM\Column(type="string", length=30)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @Assert\NotBlank(message=" Username Name can't be null")
     * @Assert\Length(
     *      min = 6,
     *      minMessage=" Enter a Username with a minimum of 6 characters"
     *
     *     )
     * @ORM\Id
     * @ORM\Column(type="string", length=30)
     */
    private $Username;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="membres")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="nom_equipe")
     */
    private $equipes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

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

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getEquipes(): ?Equipe
    {
        return $this->equipes;
    }

    public function setEquipes(?Equipe $equipes): self
    {
        $this->equipes = $equipes;

        return $this;
    }
}
