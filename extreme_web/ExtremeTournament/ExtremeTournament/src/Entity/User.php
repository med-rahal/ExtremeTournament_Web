<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Annotation\Groups;




/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, \Serializable
{

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id_user;


    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="This field cannot contain a number"
     * )
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="This field cannot contain a number"
     * )
     *
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="This field cannot contain a number"
     * )
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $username;


    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     * @Assert\NotNull(message="This value can not be null")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     *
     */
    private $sexe;

    /**
     * @ORM\Column(type="json")
     * @Groups("post:read")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     * )
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $passw;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Regex(
     *     pattern     = "/^[0-9]+$/i",
     *     htmlPattern = "^[0-9]+$"
     * )
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="This value can not be null")
     * @Groups("post:read")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("post:read")
     */
    private $banned = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $github_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;


    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $reclamations;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="id_user")
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="id_user", orphanRemoval=true)
     */
    private $paniers;


    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->publications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->paniers = new ArrayCollection();
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }


    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }


    public function getEmail():string
    {
        return (string)$this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassw(): ?string
    {
        return (string)$this->passw;
    }

    public function setPassw(string $passw): self
    {
        $this->passw = $passw;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setIdUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getIdUser() === $this) {
                $reclamation->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setIdUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getIdUser() === $this) {
                $publication->setIdUser(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Commentaire>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Commentaire $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdUser($this);
        }

        return $this;
    }

    public function removeComment(Commentaire $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdUser() === $this) {
                $comment->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setIdUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getIdUser() === $this) {
                $panier->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername():?string
    {
        return $this->username;
    }


    public function getPassword():?string
    {
        return $this->passw;
    }

    public function getSalt()
    {

    }

    public function getUser()
    {
        return User::class;

    }

    public function eraseCredentials()
    {

    }


    public function serialize()
    {
        return serialize([
            $this->id_user,
            $this->username,
            $this->email,
            $this->passw
        ]);

    }

    public function unserialize($data)
    {
        list(
            $this->id_user,
            $this->username,
            $this->email,
            $this->passw
            ) = unserialize($data,['allowed_classes'=> false]);
    }

    public function isAdmin():bool
    {
        return in_array(self::ROLE_ADMIN,$this->getRoles());
    }

    public function isUser():bool
    {
        return in_array(self::ROLE_USER,$this->getRoles());
    }

    public function __toString(): string
    {
        return (string)$this->getUsername();

    }

    public function getGithubId(): ?string
    {
        return $this->github_id;
    }

    public function setGithubId(?string $github_id): self
    {
        $this->github_id = $github_id;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getPicture() :string
    {
        return (string) $this->image;
    }
}


