<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'case_images')]
class CaseImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private CaseEntity $case;

    #[ORM\Column(length: 500)]
    private string $imageUrl;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column]
    private int $sortOrder = 0;

    #[ORM\Column]
    private \DateTime $createdAt;

    // getters/setters classiques
}
