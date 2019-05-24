<?php

namespace KirschbaumDevelopment\NovaChartjs\Exceptions;

use InvalidArgumentException;

class InvalidNovaResource extends InvalidArgumentException
{
    /**
     * @param string $type
     *
     * @return InvalidNovaResource
     */
    public static function create($type = 'Chart')
    {
        return new static(sprintf('Nova Resource provided to NovaChartJs %s must contain a property named model', $type));
    }
}
