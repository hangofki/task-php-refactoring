<?php declare(strict_types=1);

namespace App\Dto;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 * @codeCoverageIgnore
 */
class CurrencyProviderResponseDto
{
    private float $rate;

    /**
     * CurrencyDto constructor.
     *
     * @param float $rate
     */
    public function __construct(float $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
