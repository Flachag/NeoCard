<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=false)
     * @Assert\Positive(message="Vous ne pouvez pas faire de virement négatif ou nul")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="idIssuer", type="integer", nullable=false)
     * @Assert\Positive()
     */
    private $idissuer;

    /**
     * @var int
     *
     * @ORM\Column(name="idReceiver", type="integer", nullable=false)
     * @Assert\Positive()
     */
    private $idreceiver;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", nullable=false)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIdissuer(): ?int
    {
        return $this->idissuer;
    }

    public function setIdissuer(int $idissuer): self
    {
        $this->idissuer = $idissuer;

        return $this;
    }

    public function getIdreceiver(): ?int
    {
        return $this->idreceiver;
    }

    public function setIdreceiver(int $idreceiver): self
    {
        $this->idreceiver = $idreceiver;

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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
