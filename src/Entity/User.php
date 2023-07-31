<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Veuillez remplir ce champ.")]
    #[ORM\Column(length: 180, unique: false)]
    private ?string $lastname = null;

    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[ORM\Column(length: 180, unique: false)]
    private ?string $username = null;

    #[Assert\NotBlank(message: "Veuillez choisir un rôle.")]
    #[ORM\Column]
    private array $roles = ["ROLE_RH","ROLE_USER"];

    /**
     * @Assert\Choice(choices={"RH", "Informatique", "Comptabilité", "Direction"})
     */
    #[Assert\NotBlank(message: "Veuillez choisir un secteur.")]
    #[ORM\Column(length: 12, unique: false)]
    private ?string $secteur = null;

    /**
     * @Assert\Choice(choices={"CDI", "CDD", "Interim"})
     */
    #[Assert\NotBlank(message: "Veuillez choisir un type.")]
    #[ORM\Column(length: 7, unique: false)]
    private ?string $typecontrat = null;

    /**
     * @Assert\NotBlank(groups={"cdd_interim"})
     */
    #[Assert\Range(
        min: 'now',
        minMessage:"La date doit être supèrieur à la date d'aujourd'hui"
    )]
    #[Assert\DateTime(format: "d-m-Y")]
    #[ORM\Column(type:"date", nullable: true)]
    private ?\DateTimeInterface $datesortie = null;
    

    /**
     * @var string The hashed password
     */
    #[Assert\Length(min: "8", minMessage:"Le nom doit faire au moins {{ limit }} caractères.")]
    #[Assert\Regex(pattern: "/^(?=.*[A-Za-z])(?=.*\d)[\w\d]+$/",message:"Le mot de passe doit contenir au moins une lettre et un chiffre.")]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'string')]
    private string $picture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->lastname;
    }

    public function hasRole($role){
        $return = false;
        foreach ($this->roles as $roleParcours){
            if($role == $roleParcours){
            $return = true;
            }
        }
        return $return;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getTypecontrat(): ?string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(string $typecontrat): static
    {
        $this->typecontrat = $typecontrat;

        return $this;
    }

    public function getDatesortie()  
    {
        return $this->datesortie;
        // if ($this->datesortie instanceof \DateTimeInterface) {
        //     return $this->datesortie->format('d-m-y');
        // }

        // return null;
    }

    public function setDatesortie(?\DateTimeInterface $datesortie): static
    {
        $this->datesortie = $datesortie;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
