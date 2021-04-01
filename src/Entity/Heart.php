<?php

namespace App\Entity;

use App\Repository\HeartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HeartRepository::class)
 */
class Heart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"heart_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sentHearts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $initiatedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receivedHearts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sentTo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isReciprocal;

    /**
     * @Groups({"heart_read"})
     * @ORM\Column(type="datetime")
     */
    private $sentDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reactionDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitiatedBy(): ?User
    {
        return $this->initiatedBy;
    }

    public function setInitiatedBy(?User $initiatedBy): self
    {
        $this->initiatedBy = $initiatedBy;

        return $this;
    }

    public function getSentTo(): ?User
    {
        return $this->sentTo;
    }

    public function setSentTo(?User $sentTo): self
    {
        $this->sentTo = $sentTo;

        return $this;
    }

    public function getIsReciprocal(): ?bool
    {
        return $this->isReciprocal;
    }

    public function setIsReciprocal(?bool $isReciprocal): self
    {
        $this->isReciprocal = $isReciprocal;

        return $this;
    }

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sentDate;
    }

    public function setSentDate(\DateTimeInterface $sentDate): self
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    public function getReactionDate(): ?\DateTimeInterface
    {
        return $this->reactionDate;
    }

    public function setReactionDate(?\DateTimeInterface $reactionDate): self
    {
        $this->reactionDate = $reactionDate;

        return $this;
    }
}
