<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\CaseUpdate;
use App\Entity\Comment;

#[ORM\Entity]
#[ORM\Table(name: 'cases')]
class CaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column(length: 180)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 20)]
    private string $urgency = 'MEDIUM';

    #[ORM\Column(length: 20)]
    private string $status = 'PUBLISHED';

    #[ORM\Column(length: 500)]
    private string $cha9a9aUrl;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2, nullable: true)]
    private ?string $targetAmount = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $currentAmount = '0.00';

    #[ORM\Column]
    private int $viewsCount = 0;

    #[ORM\Column]
    private bool $isFeatured = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $publishedAt = null;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $stripeUrl = null;

    #[ORM\OneToMany(mappedBy: 'case', targetEntity: CaseUpdate::class, orphanRemoval: true)]
    private $updates;

    #[ORM\OneToMany(mappedBy: 'case', targetEntity: Comment::class, orphanRemoval: true)]
    private $comments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // =========================
    // GETTERS / SETTERS
    // =========================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $user): self
    {
        $this->createdBy = $user;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getUrgency(): string
    {
        return $this->urgency;
    }

    public function setUrgency(string $urgency): self
    {
        $this->urgency = $urgency;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCha9a9aUrl(): string
    {
        return $this->cha9a9aUrl;
    }

    public function setCha9a9aUrl(string $cha9a9aUrl): self
    {
        $this->cha9a9aUrl = $cha9a9aUrl;
        return $this;
    }

    public function getTargetAmount(): ?string
    {
        return $this->targetAmount;
    }

    public function setTargetAmount(?string $targetAmount): self
    {
        $this->targetAmount = $targetAmount;
        return $this;
    }

    public function getCurrentAmount(): string
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount(string $currentAmount): self
    {
        $this->currentAmount = $currentAmount;
        return $this;
    }

    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    public function setViewsCount(int $viewsCount): self
    {
        $this->viewsCount = $viewsCount;
        return $this;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;
        return $this;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getStripeUrl(): ?string
    {
        return $this->stripeUrl;
    }

    public function setStripeUrl(?string $stripeUrl): static
    {
        $this->stripeUrl = $stripeUrl;

        return $this;
    }

    /** @return \Doctrine\Common\Collections\Collection<int, CaseUpdate> */
    public function getUpdates(): \Doctrine\Common\Collections\Collection
    {
        return $this->updates;
    }

    /** @return \Doctrine\Common\Collections\Collection<int, Comment> */
    public function getComments(): \Doctrine\Common\Collections\Collection
    {
        return $this->comments;
    }
}
