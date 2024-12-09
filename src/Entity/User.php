<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "El email no puede estar vacío.")]
    private $name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank(message: "El email no puede estar vacío.")]
    #[Assert\Email(message: "El formato del email no es válido.")]

    private $email;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "El email no puede estar vacío.")]
    private $age;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "La contraseña no puede estar vacía.")]
    private $password;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}