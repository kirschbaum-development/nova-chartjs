<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests;

use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaChartjs\Traits\HasChart;

class Chartable extends Model
{
    /* Using Trait to a minimal representative Model to test Trait */
    use HasChart;

    /* timestamps not needed it test class */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public function getNovaChartjsSettings(): array
    {
        return [
            'MetricLabels' => [
                'Bananna',
                'Apple',
                'Pear',
            ],
        ];
    }
}
