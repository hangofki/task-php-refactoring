<?php declare(strict_types=1);

use App\CommissionCalculator;
use App\Providers\Bin\BinProviderOne;
use App\Providers\Currency\CurrencyProviderOne;
use App\TransactionsDataLoader;
use Symfony\Component\HttpClient\HttpClient;

require __DIR__.'/../vendor/autoload.php';

$commissionCalculator = new CommissionCalculator(
    new BinProviderOne(HttpClient::create()),
    new CurrencyProviderOne(HttpClient::create())
);

$file = new SplFileObject( __DIR__ . '/' . $argv[1]);

foreach ((new TransactionsDataLoader())->getFromFile($file) as $transactionDto) {
    echo $commissionCalculator->getCalculatedCommissionForTransaction($transactionDto) . PHP_EOL;
}
