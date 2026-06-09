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
    normalizationContext: ['groups' => ['shop:read']],
    denormalizationContext: ['groups' => ['shop:write']],
)]
class Shop
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['shop:read', 'product:read', 'import_task:read', 'queue_job:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['shop:read', 'shop:write'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shop:read', 'shop:write'])]
    #[Assert\NotNull]
    private ?Marketplace $marketplace = null;

    #[ORM\ManyToOne(inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shop:read', 'shop:write'])]
    #[Assert\NotNull]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['shop:read', 'shop:write'])]
    private ?string $apiToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['shop:read', 'shop:write'])]
    private ?string $apiClientId = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'shop')]
    private Collection $products;

    #[ORM\OneToMany(targetEntity: ImportTask::class, mappedBy: 'shop')]
    private Collection $importTasks;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->importTasks = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getMarketplace(): ?Marketplace { return $this->marketplace; }
    public function setMarketplace(?Marketplace $marketplace): self { $this->marketplace = $marketplace; return $this; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getApiToken(): ?string { return $this->apiToken; }
    public function setApiToken(?string $apiToken): self { $this->apiToken = $apiToken; return $this; }
    public function getApiClientId(): ?string { return $this->apiClientId; }
    public function setApiClientId(?string $apiClientId): self { $this->apiClientId = $apiClientId; return $this; }
    public function getProducts(): Collection { return $this->products; }
    public function getImportTasks(): Collection { return $this->importTasks; }
}
