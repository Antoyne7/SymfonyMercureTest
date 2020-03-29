<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sendBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="messagesSent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sendTo;

    /**
     * @ORM\Column(type="string", length=65555, nullable=true)
     */
    private $value;

    /**
     * Message constructor.
     * @param $sendBy
     * @param $sendTo
     * @param $value
     */
    public function __construct($sendBy, $sendTo, $value)
    {
        $this->sendBy = $sendBy;
        $this->sendTo = $sendTo;
        $this->value = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendBy(): ?Utilisateur
    {
        return $this->sendBy;
    }

    public function setSendBy(?Utilisateur $sendBy): self
    {
        $this->sendBy = $sendBy;

        return $this;
    }

    public function getSendTo(): ?Utilisateur
    {
        return $this->sendTo;
    }

    public function setSendTo(?Utilisateur $sendTo): self
    {
        $this->sendTo = $sendTo;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
