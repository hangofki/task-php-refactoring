<?php declare(strict_types=1);

namespace App\Providers\Currency;

use App\Dto\CurrencyProviderResponseDto;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
interface CurrencyProviderInterface
{
    /**
     * @param string $currency
     *
     * @return CurrencyProviderResponseDto
     */
    public function getCurrencyData(string $currency): CurrencyProviderResponseDto;
}
