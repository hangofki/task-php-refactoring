<?php declare(strict_types=1);

namespace App\Providers\Bin;

use App\Dto\BinProviderResponseDto;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 */
interface BinProviderInterface
{
    /**
     * @param int $bin
     *
     * @return BinProviderResponseDto
     */
    public function getBinData(int $bin): BinProviderResponseDto;
}
