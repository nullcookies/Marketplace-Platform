<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
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
        new Delete(),
    ],
    normalizationContext: ['groups' => ['price_rule:read']],
    denormalizationContext: ['groups' => ['price_rule:write']],
)]
class PriceRule
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['price_rule:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    #[Assert\NotBlank]
    private ?string $formula = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    private array $conditions = [];

    #[ORM\Column(length: 50, nullable: true, enumType: 'App\Enum\MarketplaceType')]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    private ?\App\Enum\MarketplaceType $marketplace = null;

    #[ORM\Column]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    private int $priority = 0;

    #[ORM\Column]
    #[Groups(['price_rule:read', 'price_rule:write'])]
    private bool $active = true;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['price_rule:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getFormula(): ?string { return $this->formula; }
    public function setFormula(string $formula): self { $this->formula = $formula; return $this; }
    public function getConditions(): array { return $this->conditions; }
    public function setConditions(?array $conditions): self { $this->conditions = $conditions ?? []; return $this; }
    public function getMarketplace(): ?\App\Enum\MarketplaceType { return $this->marketplace; }
    public function setMarketplace(?\App\Enum\MarketplaceType $marketplace): self { $this->marketplace = $marketplace; return $this; }
    public function getPriority(): int { return $this->priority; }
    public function setPriority(int $priority): self { $this->priority = $priority; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): self { $this->active = $active; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
}
