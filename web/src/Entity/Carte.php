<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carte
 *
 * @ORM\Table(name="carte", indexes={@ORM\Index(name="idCompte", columns={"idCompte"})})
 * @ORM\Entity(repositoryClass="App\Repository\CarteRepository")
 */
class Carte
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCarte", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcarte;

    /**
     * @var \Compte
     *
     * @ORM\ManyToOne(targetEntity="Compte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCompte", referencedColumnName="idCompte")
     * })
     */
    private $idcompte;

    public function getIdcarte(): ?int
    {
        return $this->idcarte;
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
