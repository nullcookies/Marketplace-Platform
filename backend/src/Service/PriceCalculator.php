<?php
namespace App\Service;

use App\Entity\PriceRule;
use App\Entity\Product;

class PriceCalculator
{
    public function calculate(Product $product, PriceRule $rule): ?string
    {
        $formula = $rule->getFormula();
        $purchasePrice = (float) ($product->getPurchasePrice() ?? 0);
        $wholesalePrice = (float) ($product->getWholesalePrice() ?? 0);

        $replacements = [
            '{purchase_price}' => $purchasePrice,
            '{wholesale_price}' => $wholesalePrice,
            '{margin}' => (float) ($product->getMarginPercent() ?? 0),
        ];

        $expression = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $formula
        );

        try {
            $result = $this->evaluateExpression($expression);
            return (string) round($result, 2);
        } catch (\Throwable) {
            return null;
        }
    }

    private function evaluateExpression(string $expression): float
    {
        $expression = str_replace(' ', '', $expression);
        $result = eval("return $expression;");
        return (float) $result;
    }
}
