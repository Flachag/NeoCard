<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Terminal
 *
 * @ORM\Table(name="terminal")
 * @ORM\Entity(repositoryClass="App\Repository\TerminalRepository")
 */
class Terminal
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
     * @var int
     *
     * @ORM\Column(name="idAccount", type="integer", nullable=false)
     */
    private $idaccount;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", nullable=false)
     * @Assert\Regex(pattern="/((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.|$)){4}/")
     */
    private $ip;

    public function getIdaccount(): ?int
    {
        return $this->idaccount;
    }

    public function setIdaccount(int $idaccount): self
    {
        $this->idaccount = $idaccount;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }


}
