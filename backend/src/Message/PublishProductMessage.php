<?php
namespace App\Message;

class PublishProductMessage
{
    public function __construct(
        private int $queueJobId,
        private int $productId,
        private int $shopId,
    ) {}

    public function getQueueJobId(): int { return $this->queueJobId; }
    public function getProductId(): int { return $this->productId; }
    public function getShopId(): int { return $this->shopId; }
}
