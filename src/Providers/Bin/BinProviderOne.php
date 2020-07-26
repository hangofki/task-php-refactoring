<?php declare(strict_types=1);

namespace App\Providers\Bin;

use App\Dto\BinProviderResponseDto;
use App\Providers\Exception\HttpClientException;
use App\Providers\Exception\InvalidResponseDataFormatException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class BinProviderOne represents
 * @link https://binlist.net/
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class BinProviderOne implements BinProviderInterface
{
    public const BIN_PROVIDER_URI = 'https://lookup.binlist.net/';

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
    public function getBinData(int $bin): BinProviderResponseDto
    {
        try {
            $responseContent = $this->client->request('GET', self::BIN_PROVIDER_URI . $bin)->toArray();
        } catch (\Throwable $e) {
            throw new HttpClientException(
                "There is an error occurred when requested to: " . self::BIN_PROVIDER_URI,
                $e->getCode(),
                $e
            );
        }

        if (!isset($responseContent['country']['alpha2'])) {
            throw new InvalidResponseDataFormatException('Invalid response data format.');
        }

        return new BinProviderResponseDto($responseContent['country']['alpha2']);
    }
}
