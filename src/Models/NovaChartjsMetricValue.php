<?php

namespace KirschbaumDevelopment\NovaChartjs\Models;

use Illuminate\Database\Eloquent\Model;

class NovaChartjsMetricValue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'metric_values',
    ];

    protected $casts = [
        'metric_values' => 'array',
    ];

    /**
     * Return Chartable model for which this Metric Values are stored
     *
     * @return MorphTo
     */
    public function chartable(): MorphTo
    {
        return $this->morphTo();
    }
}
