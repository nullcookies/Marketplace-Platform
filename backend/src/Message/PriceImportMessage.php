<?php
namespace App\Message;

class PriceImportMessage
{
    public function __construct(
        private int $importTaskId,
        private int $shopId,
    ) {}

    public function getImportTaskId(): int { return $this->importTaskId; }
    public function getShopId(): int { return $this->shopId; }
}
