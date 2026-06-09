<?php
namespace App\Service;

use App\Entity\Shop;
use App\Enum\MarketplaceType;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MarketplaceApiService
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
    ) {}

    public function publishPrice(Shop $shop, string $sku, float $price): bool
    {
        try {
            $marketplace = $shop->getMarketplace();
            $endpoint = $this->getEndpoint($marketplace->getType(), 'price');

            $response = $this->client->request('POST', $endpoint, [
                'headers' => $this->getHeaders($shop),
                'json' => [
                    'skus' => [$sku],
                    'price' => $price,
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            $this->logger->error('Failed to publish price', [
                'shop' => $shop->getId(),
                'sku' => $sku,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function publishProductCard(Shop $shop, array $productData): bool
    {
        try {
            $marketplace = $shop->getMarketplace();
            $endpoint = $this->getEndpoint($marketplace->getType(), 'product');

            $response = $this->client->request('POST', $endpoint, [
                'headers' => $this->getHeaders($shop),
                'json' => $productData,
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            $this->logger->error('Failed to publish product', [
                'shop' => $shop->getId(),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function getHeaders(Shop $shop): array
    {
        return match ($shop->getMarketplace()->getType()) {
            MarketplaceType::Wildberries => [
                'Authorization' => 'Bearer ' . $shop->getApiToken(),
                'Content-Type' => 'application/json',
            ],
            MarketplaceType::Ozon => [
                'Client-Id' => $shop->getApiClientId(),
                'Api-Key' => $shop->getApiToken(),
                'Content-Type' => 'application/json',
            ],
            MarketplaceType::YandexMarket => [
                'Authorization' => 'Bearer ' . $shop->getApiToken(),
                'Content-Type' => 'application/json',
            ],
        };
    }

    private function getEndpoint(?MarketplaceType $type, string $action): string
    {
        return match ($type) {
            MarketplaceType::Wildberries => match ($action) {
                'price' => 'https://suppliers-api.wildberries.ru/api/v3/prices',
                'product' => 'https://suppliers-api.wildberries.ru/api/v3/products',
                default => throw new \InvalidArgumentException('Unknown action'),
            },
            MarketplaceType::Ozon => match ($action) {
                'price' => 'https://api-seller.ozon.ru/v3/product/prices',
                'product' => 'https://api-seller.ozon.ru/v2/product/import',
                default => throw new \InvalidArgumentException('Unknown action'),
            },
            MarketplaceType::YandexMarket => match ($action) {
                'price' => 'https://api.partner.market.yandex.ru/v2/campaigns/offers/prices',
                'product' => 'https://api.partner.market.yandex.ru/v2/campaigns/offers',
                default => throw new \InvalidArgumentException('Unknown action'),
            },
            default => throw new \InvalidArgumentException('Unsupported marketplace'),
        };
    }
}
