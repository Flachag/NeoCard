<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tpe
 *
 * @ORM\Table(name="tpe", indexes={@ORM\Index(name="idCompte", columns={"idCompte"})})
 * @ORM\Entity(repositoryClass="App\Repository\TpeRepository")
 */
class Tpe
{
    /**
     * @var int
     *
     * @ORM\Column(name="numTPE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numtpe;

    /**
     * @var \Compte
     *
     * @ORM\ManyToOne(targetEntity="Compte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCompte", referencedColumnName="idCompte")
     * })
     */
    private $idcompte;

    public function getNumtpe(): ?int
    {
        return $this->numtpe;
    }

    public function getIdcompte(): ?Compte
    {
        return $this->idcompte;
    }

    public function setIdcompte(?Compte $idcompte): self
    {
        $this->idcompte = $idcompte;

        return $this;
    }


}
