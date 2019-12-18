<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="recepteur", columns={"recepteur"}), @ORM\Index(name="emetteur", columns={"emetteur"})})
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="numTransac", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numtransac;

    /**
     * @var string
     *
     * @ORM\Column(name="typeTransac", type="string", length=255, nullable=false)
     */
    private $typetransac;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \Compte
     *
     * @ORM\ManyToOne(targetEntity="Compte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emetteur", referencedColumnName="idCompte")
     * })
     */
    private $emetteur;

    /**
     * @var \Compte
     *
     * @ORM\ManyToOne(targetEntity="Compte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recepteur", referencedColumnName="idCompte")
     * })
     */
    private $recepteur;

    public function getNumtransac(): ?int
    {
        return $this->numtransac;
    }

    public function getTypetransac(): ?string
    {
        return $this->typetransac;
    }

    public function setTypetransac(string $typetransac): self
    {
        $this->typetransac = $typetransac;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEmetteur(): ?Compte
    {
        return $this->emetteur;
    }

    public function setEmetteur(?Compte $emetteur): self
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    public function getRecepteur(): ?Compte
    {
        return $this->recepteur;
    }

    public function setRecepteur(?Compte $recepteur): self
    {
        $this->recepteur = $recepteur;

        return $this;
    }


}
