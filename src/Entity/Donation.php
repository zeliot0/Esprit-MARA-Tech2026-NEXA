<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'donations')]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private CaseEntity $case;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $donor = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(length: 10)]
    private string $currency = 'TND';

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $donorName = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $donorEmail = null;

    #[ORM\Column(length: 20)]
    private string $status = 'PLEDGED';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

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
    public function getCase(): CaseEntity
    {
        return $this->case;
    }
    public function setCase(CaseEntity $case): self
    {
        $this->case = $case;
        return $this;
    }
    public function getDonor(): ?User
    {
        return $this->donor;
    }
    public function setDonor(?User $donor): self
    {
        $this->donor = $donor;
        return $this;
    }
    public function getAmount(): string
    {
        return $this->amount;
    }
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
    public function getCurrency(): string
    {
        return $this->currency;
    }
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
    public function getDonorName(): ?string
    {
        return $this->donorName;
    }
    public function setDonorName(?string $donorName): self
    {
        $this->donorName = $donorName;
        return $this;
    }
    public function getDonorEmail(): ?string
    {
        return $this->donorEmail;
    }
    public function setDonorEmail(?string $donorEmail): self
    {
        $this->donorEmail = $donorEmail;
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
    public function getNote(): ?string
    {
        return $this->note;
    }
    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
