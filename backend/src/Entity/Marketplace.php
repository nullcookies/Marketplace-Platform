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
    normalizationContext: ['groups' => ['marketplace:read']],
    denormalizationContext: ['groups' => ['marketplace:write']],
)]
class Marketplace
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['marketplace:read', 'shop:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, enumType: 'App\Enum\MarketplaceType')]
    #[Groups(['marketplace:read', 'marketplace:write', 'shop:read'])]
    #[Assert\NotBlank]
    private ?\App\Enum\MarketplaceType $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['marketplace:read', 'marketplace:write', 'shop:read'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Shop::class, mappedBy: 'marketplace')]
    private Collection $shops;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getType(): ?\App\Enum\MarketplaceType { return $this->type; }
    public function setType(\App\Enum\MarketplaceType $type): self { $this->type = $type; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getShops(): Collection { return $this->shops; }
}
