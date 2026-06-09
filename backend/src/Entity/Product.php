<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
    ],
    normalizationContext: ['groups' => ['product:read']],
    denormalizationContext: ['groups' => ['product:write']],
)]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['product:read', 'price:read', 'queue_job:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:write', 'price:read', 'queue_job:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:write'])]
    #[Assert\NotBlank]
    private ?string $sku = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $barcode = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product:read', 'product:write'])]
    private ?Shop $shop = null;

    #[ORM\ManyToOne]
    #[Groups(['product:read', 'product:write'])]
    private ?Category $category = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $purchasePrice = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $wholesalePrice = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $recommendedPrice = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $marginPercent = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $externalId = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private bool $active = true;

    #[ORM\OneToMany(targetEntity: Price::class, mappedBy: 'product')]
    private Collection $prices;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['product:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['product:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getSku(): ?string { return $this->sku; }
    public function setSku(string $sku): self { $this->sku = $sku; return $this; }
    public function getBarcode(): ?string { return $this->barcode; }
    public function setBarcode(?string $barcode): self { $this->barcode = $barcode; return $this; }
    public function getShop(): ?Shop { return $this->shop; }
    public function setShop(?Shop $shop): self { $this->shop = $shop; return $this; }
    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
    public function getPurchasePrice(): ?string { return $this->purchasePrice; }
    public function setPurchasePrice(?string $purchasePrice): self { $this->purchasePrice = $purchasePrice; return $this; }
    public function getWholesalePrice(): ?string { return $this->wholesalePrice; }
    public function setWholesalePrice(?string $wholesalePrice): self { $this->wholesalePrice = $wholesalePrice; return $this; }
    public function getRecommendedPrice(): ?string { return $this->recommendedPrice; }
    public function setRecommendedPrice(?string $recommendedPrice): self { $this->recommendedPrice = $recommendedPrice; return $this; }
    public function getMarginPercent(): ?string { return $this->marginPercent; }
    public function setMarginPercent(?string $marginPercent): self { $this->marginPercent = $marginPercent; return $this; }
    public function getExternalId(): ?string { return $this->externalId; }
    public function setExternalId(?string $externalId): self { $this->externalId = $externalId; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): self { $this->active = $active; return $this; }
    public function getPrices(): Collection { return $this->prices; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}
