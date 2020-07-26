<?php declare(strict_types=1);

namespace App\Dto;

/**
 * @author Dmitriy Pikiner <pikiner.dm96@gmail.com>
 * @codeCoverageIgnore
 */
class BinProviderResponseDto
{
    private string $alpha2;

    /**
     * BinDto constructor.
     *
     * @param string $alpha2
     */
    public function __construct(string $alpha2)
    {
        $this->alpha2 = $alpha2;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }
}
