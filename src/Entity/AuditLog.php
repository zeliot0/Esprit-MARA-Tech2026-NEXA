<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'audit_logs')]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column(length: 120)]
    private string $action;

    #[ORM\Column(length: 80)]
    private string $entityType;

    #[ORM\Column(nullable: true)]
    private ?int $entityId = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $metaJson = null;

    #[ORM\Column]
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getAction(): string
    {
        return $this->action;
    }
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }
    public function getEntityType(): string
    {
        return $this->entityType;
    }
    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;
        return $this;
    }
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }
    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }
    public function getMetaJson(): ?array
    {
        return $this->metaJson;
    }
    public function setMetaJson(?array $metaJson): self
    {
        $this->metaJson = $metaJson;
        return $this;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
