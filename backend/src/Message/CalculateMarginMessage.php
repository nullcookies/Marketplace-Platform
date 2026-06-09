<?php
namespace App\Message;

class CalculateMarginMessage
{
    public function __construct(
        private int $productId,
    ) {}

    public function getProductId(): int { return $this->productId; }
}
