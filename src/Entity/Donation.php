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
}
