<?php declare(strict_types=1);

namespace App\Tests\Unit\Providers\Bin;

use App\Dto\BinProviderResponseDto;
use App\Providers\Bin\BinProviderOne;
use App\Providers\Exception\InvalidResponseDataFormatException;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @see BinProviderOne
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class BinProviderOneTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @see BinProviderOne::getBinData()
     *
     * @param array<array>                   $responseData
     * @param BinProviderResponseDto         $expectedResponseDto
     * @psalm-param class-string<\Throwable> $expectException
     */
    public function testGetBinData(array $responseData, BinProviderResponseDto $expectedResponseDto, string $expectException = null): void
    {
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

        $this->assertEquals($expectedResponseDto, (new BinProviderOne($httpClientMock))->getBinData(123456789));
    }

    /**
     * @return array<array>
     */
    public function dataProvider(): array
    {
        return [
            'Successfully got bin data with alpha2' => [
                [
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
                new BinProviderResponseDto('DK')
            ],
            'Invalid response data format' => [
                [
                    'country' => [
                        'numeric' => 208,
                        'name' => 'Denmark',
                        'emoji' => '🇩🇰',
                        'currency' => 'DKK',
                        'latitude' => '56',
                        'longitude' => '10',
                    ],
                ],
                new BinProviderResponseDto('DK'),
                InvalidResponseDataFormatException::class
            ],
        ];
    }
}
