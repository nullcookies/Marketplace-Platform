<?php
namespace App\Service;

use App\Entity\ImportTask;
use App\Entity\Product;
use App\Enum\ImportStatus;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ImportService
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
    ) {}

    public function processImport(ImportTask $task, array $rows): void
    {
        $task->setStatus(ImportStatus::Processing);
        $task->setTotalRows(count($rows));
        $this->em->flush();

        $errors = [];
        $processed = 0;

        foreach ($rows as $index => $row) {
            try {
                $this->processRow($task, $row);
                $processed++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$index}: {$e->getMessage()}";
            }

            if ($index % 100 === 0) {
                $task->setProcessedRows($processed);
                $task->setErrorRows(count($errors));
                $this->em->flush();
            }
        }

        $task->setStatus(ImportStatus::Completed);
        $task->setProcessedRows($processed);
        $task->setErrorRows(count($errors));
        $task->setErrors(implode("\n", $errors));
        $task->setCompletedAt(new \DateTimeImmutable());
        $this->em->flush();
    }

    private function processRow(ImportTask $task, array $row): void
    {
        $shop = $task->getShop();
        $sku = $row['sku'] ?? throw new \RuntimeException('SKU is required');

        $product = $this->em->getRepository(Product::class)
            ->findOneBy(['sku' => $sku, 'shop' => $shop]);

        if (!$product) {
            $product = new Product();
            $product->setSku($sku);
            $product->setShop($shop);
            $product->setName($row['name'] ?? $sku);
        }

        if (isset($row['name'])) $product->setName($row['name']);
        if (isset($row['barcode'])) $product->setBarcode($row['barcode']);
        if (isset($row['purchase_price'])) $product->setPurchasePrice((string) $row['purchase_price']);
        if (isset($row['wholesale_price'])) $product->setWholesalePrice((string) $row['wholesale_price']);
        if (isset($row['recommended_price'])) $product->setRecommendedPrice((string) $row['recommended_price']);
        if (isset($row['margin_percent'])) $product->setMarginPercent((string) $row['margin_percent']);

        $product->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($product);
    }
}
