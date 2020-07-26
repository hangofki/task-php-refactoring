<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Dto\TransactionDto;
use App\TransactionsDataLoader;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SplTempFileObject;

/**
 * @see TransactionsDataLoader
 *
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class TransactionsDataLoaderTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @see TransactionsDataLoader::getFromFile()
     *
     * @param array<string>                  $rawData
     * @param array<TransactionDto>          $expectedData
     * @psalm-param class-string<\Throwable> $expectException
     */
    public function testGetFromFile(array $rawData, array $expectedData, string $expectException = null): void
    {
        $file = new SplTempFileObject();
        foreach ($rawData as $rawTransaction) {
            $file->fwrite($rawTransaction);
        }

        if ($expectException) {
            $this->expectException($expectException);
        }

        $file->rewind();
        $transactionsData = [];
        foreach ((new TransactionsDataLoader())->getFromFile($file) as $transactionData) {
            $transactionsData[] = $transactionData;
        }

        $this->assertEquals($expectedData, $transactionsData);
    }

    /**
     * @return array<array>
     */
    public function dataProvider(): array
    {
        return [
            'Successfully parsed data from file' => [
                [
                    '{"bin":"45717360","amount":"100.00","currency":"EUR"}' . PHP_EOL,
                    '{"bin":"516793","amount":"50.00","currency":"USD"}' . PHP_EOL,
                    '{"bin":"45417360","amount":"10000.00","currency":"JPY"}' . PHP_EOL,
                    '{"bin":"41417360","amount":"130.00","currency":"USD"}' . PHP_EOL,
                    '{"bin":"4745030","amount":"2000.00","currency":"GBP"}' . PHP_EOL
                ],
                [
                    new TransactionDto(45717360, 100.00, 'EUR'),
                    new TransactionDto(516793, 50.00, 'USD'),
                    new TransactionDto(45417360, 10000.00, 'JPY'),
                    new TransactionDto(41417360, 130.00, 'USD'),
                    new TransactionDto(4745030, 2000.00, 'GBP'),
                ],
            ],
            'Invalid json format' => [
                [
                    '{"bin":"45717360"amount":"100""currency":"EUR"}' . PHP_EOL,
                ],
                [],
                InvalidArgumentException::class
            ],
            'Invalid data format' => [
                [
                    '{"bin":"45717360","another_key":"100","currency":"EUR"}' . PHP_EOL,
                ],
                [],
                DomainException::class
            ],
        ];
    }
}
