<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $email;
    
    #[ORM\Column]
    private string $password;
    
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $firstname;
    
    #[ORM\Column]
    private string $lastname;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName() : string {
        return $this->firstname;
    }

    public function setFirstName(string $firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastName() : string {
        return $this->lastname;
    }

    public function setLastName(string $lastname) {
        $this->lastname = $lastname;

        return $this;
    }
 
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
