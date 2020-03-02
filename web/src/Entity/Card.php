<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
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
     * @ORM\Column(name="uid", type="string", length=255, nullable=false)
     */
    private $uid;

    /**
     * @var int
     * @ORM\Column(name="account_id", type="integer", nullable=false)
     */
    private $account_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAccountId(): ?int
    {
        return $this->account_id;
    }

    /**
     * @param int $account_id
     */
    public function setAccountId(int $account_id): void
    {
        $this->account_id = $account_id;
    }

    /**
     * @return string
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }
}
