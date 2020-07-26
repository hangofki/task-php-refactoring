<?php declare(strict_types=1);

namespace App;

use App\Dto\BinProviderResponseDto;
use App\Dto\CurrencyProviderResponseDto;
use App\Dto\TransactionDto;
use App\Providers\Bin\BinProviderInterface;
use App\Providers\Currency\CurrencyProviderInterface;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class CommissionCalculator
{
    public const DEFAULT_COMMISSION_CURRENCY = 'EUR';

    public const EU_COUNTRIES_ALPHA2 = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK'
    ];

    private BinProviderInterface $binProvider;
    private CurrencyProviderInterface $currencyProvider;

    /**
     * CommissionCalculator constructor.
     *
     * @param BinProviderInterface      $binProvider
     * @param CurrencyProviderInterface $currencyProvider
     */
    public function __construct(
        BinProviderInterface $binProvider,
        CurrencyProviderInterface $currencyProvider
    ) {
        $this->binProvider = $binProvider;
        $this->currencyProvider = $currencyProvider;
    }

    /**
     * @param TransactionDto $transactionDto
     *
     * @return float
     */
    public function getCalculatedCommissionForTransaction(TransactionDto $transactionDto): float
    {
        $commission = $this->getFixedAmount($transactionDto, $this->currencyProvider->getCurrencyData($transactionDto->getCurrency()))
            * $this->getEuCommission($this->binProvider->getBinData($transactionDto->getBin()));

        return round($commission, 2);
    }

    /**
     * @param TransactionDto              $transactionDto
     * @param CurrencyProviderResponseDto $currencyDto
     *
     * @return float
     */
    protected function getFixedAmount(TransactionDto $transactionDto, CurrencyProviderResponseDto $currencyDto): float
    {
        return $transactionDto->getCurrency() === self::DEFAULT_COMMISSION_CURRENCY || $currencyDto->getRate() === (float)0
            ? $transactionDto->getAmount()
            : $transactionDto->getAmount() / $currencyDto->getRate();
    }

    /**
     * @param BinProviderResponseDto $binDto
     *
     * @return float
     */
    protected function getEuCommission(BinProviderResponseDto $binDto): float
    {
        return in_array($binDto->getAlpha2(), self::EU_COUNTRIES_ALPHA2, true)
            ? 0.01
            : 0.02;
    }
}
