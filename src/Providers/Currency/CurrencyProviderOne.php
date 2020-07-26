<?php declare(strict_types=1);

namespace App\Providers\Currency;

use App\Dto\CurrencyProviderResponseDto;
use App\Providers\Exception\HttpClientException;
use App\Providers\Exception\InvalidResponseDataFormatException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class CurrencyProviderOne represents
 * @link https://exchangeratesapi.io/
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class CurrencyProviderOne implements CurrencyProviderInterface
{
    public const CURRENCY_PROVIDER_URL = 'https://api.exchangeratesapi.io/latest';

    private HttpClientInterface $client;

    /**
     * BinProviderOne constructor.
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     *
     * @throws HttpClientException
     * @throws InvalidResponseDataFormatException
     */
    public function getCurrencyData(string $currency): CurrencyProviderResponseDto
    {
        try {
            $responseContent = $this->client->request('GET', self::CURRENCY_PROVIDER_URL)->toArray();
        } catch (\Throwable $e) {
            throw new HttpClientException(
                "There is an error occurred when requested to: " . self::CURRENCY_PROVIDER_URL,
                $e->getCode(),
                $e
            );
        }

        if (!isset($responseContent['rates'])) {
            throw new InvalidResponseDataFormatException('Invalid response data format.');
        }

        return new CurrencyProviderResponseDto($responseContent['rates'][$currency] ?? 0);
    }
}
