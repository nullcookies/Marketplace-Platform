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
    normalizationContext: ['groups' => ['import_task:read']],
    denormalizationContext: ['groups' => ['import_task:write']],
)]
class ImportTask
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['import_task:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['import_task:read', 'import_task:write'])]
    #[Assert\NotBlank]
    private ?string $filename = null;

    #[ORM\Column(length: 50, enumType: 'App\Enum\ImportStatus')]
    #[Groups(['import_task:read'])]
    private ?\App\Enum\ImportStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'importTasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['import_task:read', 'import_task:write'])]
    private ?Shop $shop = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['import_task:read'])]
    private ?int $totalRows = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['import_task:read'])]
    private ?int $processedRows = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['import_task:read'])]
    private ?int $errorRows = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['import_task:read'])]
    private ?string $errors = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['import_task:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['import_task:read'])]
    private ?\DateTimeImmutable $completedAt = null;

    public function __construct()
    {
        $this->status = \App\Enum\ImportStatus::Pending;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getFilename(): ?string { return $this->filename; }
    public function setFilename(string $filename): self { $this->filename = $filename; return $this; }
    public function getStatus(): ?\App\Enum\ImportStatus { return $this->status; }
    public function setStatus(\App\Enum\ImportStatus $status): self { $this->status = $status; return $this; }
    public function getShop(): ?Shop { return $this->shop; }
    public function setShop(?Shop $shop): self { $this->shop = $shop; return $this; }
    public function getTotalRows(): ?int { return $this->totalRows; }
    public function setTotalRows(?int $totalRows): self { $this->totalRows = $totalRows; return $this; }
    public function getProcessedRows(): ?int { return $this->processedRows; }
    public function setProcessedRows(?int $processedRows): self { $this->processedRows = $processedRows; return $this; }
    public function getErrorRows(): ?int { return $this->errorRows; }
    public function setErrorRows(?int $errorRows): self { $this->errorRows = $errorRows; return $this; }
    public function getErrors(): ?string { return $this->errors; }
    public function setErrors(?string $errors): self { $this->errors = $errors; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getCompletedAt(): ?\DateTimeImmutable { return $this->completedAt; }
    public function setCompletedAt(?\DateTimeImmutable $completedAt): self { $this->completedAt = $completedAt; return $this; }
}
