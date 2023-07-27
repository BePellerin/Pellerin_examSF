<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
// use App\Entity\Assert\Length;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Veuillez remplir ce champ.")]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $lastname = null;

    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    /**
     * @Assert\Choice(choices={"ROLE_USER", "ROLE_USER"})
     */
    #[Assert\NotBlank(message: "Veuillez choisir un rôle.")]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @Assert\Choice(choices={"RH", "Informatique", "Comptabilité", "Direction"})
     */
    #[Assert\NotBlank(message: "Veuillez choisir un secteur.")]
    #[ORM\Column(length: 12, unique: true)]
    private ?string $secteur = null;

    /**
     * @Assert\Choice(choices={"CDI", "CDD", "Interim"})
     */
    #[Assert\NotBlank(message: "Veuillez choisir un type.")]
    #[ORM\Column(length: 7, unique: true)]
    private ?string $typecontrat = null;

    /**
     * @Assert\NotBlank(groups={"cdd_interim"})
     * */
    #[Assert\Range(
        min: 'now',
        max: '+50 years',
    )]
    #[Assert\Date]
    #[ORM\Column(type:"date", nullable: true)]
    private ?\DateTimeInterface $datesortie = null;

    /**
     * @Assert\Length(min: "8", minMessage = "Le nom doit faire au moins {{ limit }} caractères.")
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]$/",
     *     message="Le mot de passe doit contenir au moins une lettre et un chiffre."
     * )
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[ORM\Column]
    private ?string $password = null;

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

    public function getDatesortie(): ?\DateTimeInterface
    {
        return $this->datesortie;
    }

    public function setDatesortie(?\DateTimeInterface $datesortie): static
    {
        $this->datesortie = $datesortie;

        return $this;
    }
}
