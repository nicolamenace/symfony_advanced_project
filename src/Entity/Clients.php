<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ClientsRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'First name is required.')]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/", message: 'First name should only contain letters.')]
    private string $firstname;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Last name is required.')]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/", message: 'Last name should only contain letters.')]
    private string $lastname;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Invalid email format.')]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/",
        message: 'Email format must be xxx@xx.xx'
    )]
    private string $email;

    #[ORM\Column(nullable: true)]
    private ?int $phonenumber = null;

    #[ORM\Column]
    private string $adress;

    #[ORM\Column]
    private string $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
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

    public function getPhonenumber(): ?int
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?int $phonenumber): self
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }

    public function getAdress(): string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;
        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
