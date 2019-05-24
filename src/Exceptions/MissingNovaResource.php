<?php

namespace KirschbaumDevelopment\NovaChartjs\Exceptions;

use InvalidArgumentException;

class MissingNovaResource extends InvalidArgumentException
{
    /**
     * @param string $type
     *
     * @return MissingNovaResource
     */
    public static function create($type = 'Chart')
    {
        return new static(sprintf('First argument for NovaChartjs %s must be a valid Nova Resource', $type));
    }
}
