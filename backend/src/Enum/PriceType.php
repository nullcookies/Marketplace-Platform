<?php
namespace App\Enum;

enum PriceType: string
{
    case Purchase = 'purchase';
    case Wholesale = 'wholesale';
    case Retail = 'retail';
    case Marketplace = 'marketplace';
}
