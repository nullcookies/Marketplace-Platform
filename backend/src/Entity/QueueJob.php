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
    normalizationContext: ['groups' => ['queue_job:read']],
    denormalizationContext: ['groups' => ['queue_job:write']],
)]
class QueueJob
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['queue_job:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, enumType: 'App\Enum\QueueJobStatus')]
    #[Groups(['queue_job:read'])]
    private ?\App\Enum\QueueJobStatus $status = null;

    #[ORM\Column(length: 100)]
    #[Groups(['queue_job:read', 'queue_job:write'])]
    #[Assert\NotBlank]
    private ?string $action = null;

    #[ORM\ManyToOne]
    #[Groups(['queue_job:read', 'queue_job:write'])]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[Groups(['queue_job:read', 'queue_job:write'])]
    private ?Shop $shop = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['queue_job:read', 'queue_job:write'])]
    private array $payload = [];

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['queue_job:read'])]
    private ?string $errorMessage = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['queue_job:read'])]
    private ?int $attempts = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['queue_job:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['queue_job:read'])]
    private ?\DateTimeImmutable $processedAt = null;

    public function __construct()
    {
        $this->status = \App\Enum\QueueJobStatus::Queued;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getStatus(): ?\App\Enum\QueueJobStatus { return $this->status; }
    public function setStatus(\App\Enum\QueueJobStatus $status): self { $this->status = $status; return $this; }
    public function getAction(): ?string { return $this->action; }
    public function setAction(string $action): self { $this->action = $action; return $this; }
    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }
    public function getShop(): ?Shop { return $this->shop; }
    public function setShop(?Shop $shop): self { $this->shop = $shop; return $this; }
    public function getPayload(): array { return $this->payload; }
    public function setPayload(?array $payload): self { $this->payload = $payload ?? []; return $this; }
    public function getErrorMessage(): ?string { return $this->errorMessage; }
    public function setErrorMessage(?string $errorMessage): self { $this->errorMessage = $errorMessage; return $this; }
    public function getAttempts(): ?int { return $this->attempts; }
    public function setAttempts(int $attempts): self { $this->attempts = $attempts; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getProcessedAt(): ?\DateTimeImmutable { return $this->processedAt; }
    public function setProcessedAt(?\DateTimeImmutable $processedAt): self { $this->processedAt = $processedAt; return $this; }
}
