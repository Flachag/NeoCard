<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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

    public function getIdaccount(): ?int
    {
        return $this->idaccount;
    }

    public function setIdaccount(int $idaccount): self
    {
        $this->idaccount = $idaccount;

        return $this;
    }


}
