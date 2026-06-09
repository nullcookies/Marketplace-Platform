<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
    ],
    normalizationContext: ['groups' => ['price:read']],
    denormalizationContext: ['groups' => ['price:write']],
)]
class Price
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['price:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['price:read', 'price:write'])]
    #[Assert\NotNull]
    private ?Product $product = null;

    #[ORM\Column(length: 50, enumType: 'App\Enum\PriceType')]
    #[Groups(['price:read', 'price:write'])]
    #[Assert\NotBlank]
    private ?\App\Enum\PriceType $type = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    #[Groups(['price:read', 'price:write'])]
    #[Assert\NotBlank]
    private ?string $value = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Groups(['price:read', 'price:write'])]
    private ?string $commissionPercent = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Groups(['price:read', 'price:write'])]
    private ?string $logisticsCost = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['price:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['price:read', 'price:write'])]
    private ?string $note = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }
    public function getType(): ?\App\Enum\PriceType { return $this->type; }
    public function setType(\App\Enum\PriceType $type): self { $this->type = $type; return $this; }
    public function getValue(): ?string { return $this->value; }
    public function setValue(string $value): self { $this->value = $value; return $this; }
    public function getCommissionPercent(): ?string { return $this->commissionPercent; }
    public function setCommissionPercent(?string $commissionPercent): self { $this->commissionPercent = $commissionPercent; return $this; }
    public function getLogisticsCost(): ?string { return $this->logisticsCost; }
    public function setLogisticsCost(?string $logisticsCost): self { $this->logisticsCost = $logisticsCost; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getNote(): ?string { return $this->note; }
    public function setNote(?string $note): self { $this->note = $note; return $this; }
}
