<?php

namespace KirschbaumDevelopment\NovaChartjs\Cards;

use App\User;
use Laravel\Nova\Card;

class NovaChartjsCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'card-nova-chartjs';
    }

    public function settings(array $settings): self
    {
        return $this->withMeta(['settings' => $settings]);
    }

    public function chartModel(string $model): self
    {
        return $this->withMeta(['chartModel' => $model]);
    }

    public function additionalDatasets(array $additionalDatasets): self
    {
        return $this->withMeta(['additionalDatasets' => $additionalDatasets]);
    }

    public function dataset(array $dataset): self
    {
        return $this->withMeta(['dataset' => $dataset]);
    }

    /**
     * Set chart name for the chart.
     *
     * @param string $chartName
     *
     * @return NovaChartjs
     */
    public function chartName($chartName = 'default'): self
    {
        return $this->withMeta([
            'chartName' => $chartName,
        ]);
    }

    /**
     * Returns chartname for current chart.
     *
     * @return string
     */
    protected function getChartName()
    {
        return data_get($this->meta(), 'chartName', 'default');
    }

    /**
     * Updates Meta values for Charts.
     * 
     * @return void 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException 
     */
    protected function updateChartMeta()
    {
        $model = app(data_get($this->meta(), 'chartModel'));
        $chartName = $this->getChartName();

        $this->withMeta([ 
            'dataset' => data_get($this->meta(), 'dataset', $model::getCardDatasets($chartName)),
            'settings' => data_get($this->meta(), 'settings', $model::getNovaChartjsCardSettings($chartName)),
            'additionalDatasets' => data_get($this->meta(), 'additionalDatasets', $model::getAdditionalCardDatasets($chartName)),
        ]);
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->updateChartMeta();
        return parent::jsonSerialize();
    }
}