<?php declare(strict_types=1);

namespace App;

use App\Dto\TransactionDto;
use DomainException;
use InvalidArgumentException;
use SplFileObject;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
class TransactionsDataLoader
{
    public const REQUIRED_DATA_KEYS = [
        'bin',
        'amount',
        'currency'
    ];

    /**
     * @param SplFileObject $file
     *
     * @return iterable<TransactionDto>
     */
    public function getFromFile(SplFileObject $file): iterable
    {
        while (!$file->eof()) {
            yield $this->getTransactionData((string)$file->getCurrentLine());
            $file->next();
        }
    }

    /**
     * @param string $line
     *
     * @return TransactionDto
     */
    protected function getTransactionData(string $line): TransactionDto
    {
        $transactionData = json_decode($line, true);

        if (!$transactionData) {
            throw new InvalidArgumentException(
                "There is impossible to decode transaction data from line: '$line'."
            );
        }

        foreach (self::REQUIRED_DATA_KEYS as $key) {
            if (!array_key_exists($key, $transactionData)) {
                throw new DomainException(
                    "Required transaction data key not found, '$key' should be provided in line: '$line'."
                );
            }
        }

        return new TransactionDto(
            (int)$transactionData['bin'],
            (float)$transactionData['amount'],
            (string)$transactionData['currency'],
        );
    }
}
