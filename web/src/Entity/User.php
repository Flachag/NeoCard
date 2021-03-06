<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Pseudo déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Email déjà utilisée"
 * )
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Votre nom est indispensable")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Votre prénom est indispensable")
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     */
    public $confirm_password;

    /**
     * @var string
     */
    public $confirm_new_password;

    /**
     * @var string
     */
    public $new_password;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Votre Pseudonyme est indispensable")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Votre adresse email est indispensable")
     * @Assert\Email()
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles = [];

    /**
     * @var boolean
     * @ORM\Column(name="banned", type="boolean")
     */
    private $banned;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getFullName(){
        return $this->surname . ' ' . $this->name;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }


    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     */
    public function setBanned(bool $banned): void
    {
        $this->banned = $banned;
    }

    /**
     * @return string
     */
    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    /**
     * @param string $confirm_password
     */
    public function setConfirmPassword(string $confirm_password): void
    {
        $this->confirm_password = $confirm_password;
    }

    /**
     * @return string
     */
    public function getConfirmNewPassword(): ?string
    {
        return $this->confirm_new_password;
    }

    /**
     * @param string $confirm_new_password
     */
    public function setConfirmNewPassword(string $confirm_new_password): void
    {
        $this->confirm_new_password = $confirm_new_password;
    }

    /**
     * @return string
     */
    public function getNewPassword(): ?string
    {
        return $this->new_password;
    }

    /**
     * @param string $new_password
     */
    public function setNewPassword(string $new_password): void
    {
        $this->new_password = $new_password;
    }



    public function getSalt(){}

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
