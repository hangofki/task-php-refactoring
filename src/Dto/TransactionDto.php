<?php declare(strict_types=1);

namespace App\Dto;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 * @codeCoverageIgnore
 */
class TransactionDto
{
    private int $bin;
    private float $amount;
    private string $currency;

    /**
     * Transaction constructor.
     *
     * @param int    $bin
     * @param float  $amount
     * @param string $currency
     */
    public function __construct(
        int $bin,
        float $amount,
        string $currency
    ) {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getBin(): int
    {
        return $this->bin;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
