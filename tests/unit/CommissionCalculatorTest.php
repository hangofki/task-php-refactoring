<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\CommissionCalculator;
use App\Dto\TransactionDto;
use App\Providers\Bin\BinProviderOne;
use App\Providers\Currency\CurrencyProviderOne;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @see CommissionCalculator
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class CommissionCalculatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array<array>   $providersResponseData
     * @param TransactionDto $transactionDto
     * @param float          $expectedCommission
     *
     * @see CommissionCalculator::getCalculatedCommissionForTransaction()
     */
    public function testGetCalculatedCommissionForTransaction(
        array $providersResponseData,
        TransactionDto $transactionDto,
        float $expectedCommission
    ): void {
        $commissionCalculator = new CommissionCalculator(
            new BinProviderOne($this->getHttpClientMock($providersResponseData['binResponseData'])),
            new CurrencyProviderOne($this->getHttpClientMock($providersResponseData['currencyResponseData']))
        );

        $commission = $commissionCalculator->getCalculatedCommissionForTransaction($transactionDto);
        $this->assertEquals($expectedCommission, $commission);
    }

    /**
     * @return array<array>
     */
    public function dataProvider(): array
    {
        return [
            'EU country' => [
                [
                    'binResponseData' => [
                        'country' => [
                            'numeric' => 208,
                            'alpha2' => 'DK',
                            'name' => 'Denmark',
                            'emoji' => '🇩🇰',
                            'currency' => 'DKK',
                            'latitude' => '56',
                            'longitude' => '10',
                        ],
                    ],
                    'currencyResponseData' => $this->getCurrencyProviderResponseData()
                ],
                new TransactionDto(111222333, 100.00, 'DKK'),
                0.13
            ],
            'Not EU country' => [
                [
                    'binResponseData' => [
                        'country' => [
                            'numeric' => 999,
                            'alpha2' => 'RK',
                            'name' => 'Another country',
                            'emoji' => 'flag',
                            'currency' => 'RKK',
                            'latitude' => '99',
                            'longitude' => '99',
                        ],
                    ],
                    'currencyResponseData' => $this->getCurrencyProviderResponseData()
                ],
                new TransactionDto(111222333, 100.00, 'RKK'),
                2
            ]
        ];
    }

    /**
     * @param array<array> $clientResponseData
     *
     * @return HttpClientInterface
     */
    private function getHttpClientMock(array $clientResponseData): HttpClientInterface
    {
        $httpResponseMock = $this->createMock(ResponseInterface::class);
        $httpResponseMock
            ->method('toArray')
            ->willReturn($clientResponseData);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock
            ->method('request')
            ->willReturn($httpResponseMock);

        return $httpClientMock;
    }

    /**
     * @return array<array>
     */
    private function getCurrencyProviderResponseData(): array
    {
        return [
            'rates' => [
                'CAD' => 1.5578,
                'HKD' => 8.9978,
                'ISK' => 157.8,
                'PHP' => 57.316,
                'DKK' => 7.4438,
                'HUF' => 346.98,
                'CZK' => 26.268,
                'AUD' => 1.6376,
                'RON' => 4.8325,
                'SEK' => 10.269,
                'IDR' => 16982,
                'INR' => 86.866,
                'BRL' => 6.0777,
                'RUB' => 83.3938,
                'HRK' => 7.517,
                'JPY' => 123.36,
                'THB' => 36.821,
                'CHF' => 1.073,
                'SGD' => 1.6083,
                'PLN' => 4.4046,
                'BGN' => 1.9558,
                'TRY' => 7.9496,
                'CNY' => 8.1453,
                'NOK' => 10.6953,
                'NZD' => 1.7506,
                'ZAR' => 19.435,
                'USD' => 1.1608,
                'MXN' => 26.0804,
                'ILS' => 3.9642,
                'GBP' => 0.90985,
                'KRW' => 1396.83,
                'MYR' => 4.9502,
            ],
        ];
    }
}
