<?php declare(strict_types=1);

namespace App\Tests\Unit\Providers\Currency;

use App\Dto\CurrencyProviderResponseDto;
use App\Providers\Currency\CurrencyProviderOne;
use App\Providers\Exception\InvalidResponseDataFormatException;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @see CurrencyProviderOne
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class CurrencyProviderOneTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @see CurrencyProviderOne::getCurrencyData()
     *
     * @param string                         $currency
     * @param array<array>                   $responseData
     * @param CurrencyProviderResponseDto    $expectedResponseDto
     * @psalm-param class-string<\Throwable> $expectException
     */
    public function testGetCurrencyData(
        string $currency,
        array $responseData,
        CurrencyProviderResponseDto $expectedResponseDto,
        string $expectException = null
    ): void {
        $httpResponseMock = $this->createMock(ResponseInterface::class);
        $httpResponseMock
            ->method('toArray')
            ->willReturn($responseData);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock
            ->method('request')
            ->willReturn($httpResponseMock);

        if ($expectException) {
            $this->expectException($expectException);
        }

        $this->assertEquals($expectedResponseDto, (new CurrencyProviderOne($httpClientMock))->getCurrencyData($currency));
    }

    /**
     * @return array<array>
     */
    public function dataProvider(): array
    {
        return [
            'Successfully got currency rate' => [
                'PLN',
                [
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
                ],
                new CurrencyProviderResponseDto(4.4046)
            ],
            'Successfully got currencies data, but requested currency not exist' => [
                'AAA',
                [
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
                ],
                new CurrencyProviderResponseDto(0),
            ],
            'Invalid currencies data format' => [
                'PLN',
                [
                    'invalidKey' => [
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
                ],
                new CurrencyProviderResponseDto(4.4046),
                InvalidResponseDataFormatException::class
            ],
        ];
    }
}
