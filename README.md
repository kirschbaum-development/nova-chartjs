# Nova ChartJS

## Introduction
Nova ChartJs is meant to add chart.js Chart Field for Laravel Nova Models.

**This is a pre-release project in Active Development. Frequent Changes are expected**

## Requirements

This Nova resource tool requires Nova 2.0 or higher.

## Installation

You can install this package in a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require kirschbaum-development/nova-chartjs
```

You'll also need to run migrations to set up a database table for `NovaChartjsMetricValue`

```bash
php artisan migrate
```
## Usage

After setup, include `HasNovaChartjsChart` trait in the model for which you want to display the chart.

You must also define a static `getNovaChartjsSettings` function in the model which should return the required settings for the Chart.

```php
use KirschbaumDevelopment\NovaChartjs\Traits\HasNovaChartjsChart;

class User extends Model
{
    use HasNovaChartjsChart;

    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public static function getNovaChartjsSettings(): array
    {
        return [
            'type' => 'range',
            'title' => 'Super Cool Chart',
            'panelTitle' => 'Super Cool Chart Panel',
            'color' => '#FF0000',
            'parameters' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'high' => [80, 40, 62, 79, 80, 90, 79, 90, 90, 90, 92, 91],
            'low' => [8, 7, 12, 19, 12, 10, 19, 9, 10, 20, 12, 11],
            'options' => ['responsive' => true, 'maintainAspectRatio' => false],
        ];
    }

    // ...
}
```
### NovaChartjs Panel

Our recommended way of using Nova Chartjs Chart is to add the included Panel `KirschbaumDevelopment\NovaChartjs\NovaChartjsPanel` to your model's Nova fields

```php

namespace App\Nova;

use KirschbaumDevelopment\NovaChartjs\NovaChartjsPanel;

class User extends Resource
{
    
    //...
    public function fields(Request $request)
    {
        return [
            //...

            new NovaChartjsPanel($this),
        ];
    }
}
``` 
**_NOTE:_** You must pass the `Resource` (i.e. `$this`) to the `NovaChartjsPanel` component.

If you instead want to use NovaChartjsChart inline without a panel
```php
namespace App\Nova;

use KirschbaumDevelopment\NovaChartjs\NovaChartjs;

class User extends Resource
{
    
    //...
    public function fields(Request $request)
    {
        return [
            //...

            NovaChartjs::make(
                $this,
                'Chart Title',
                'novaChartjsMetricValue'
            ),
        ];
    }
}
``` 
**_NOTE:_** You must pass the `Resource` (i.e. `$this`) to the `NovaChartjs` component as a first argument.

## To-Do
- [x] Setup Repo
- [x] Create a NovaChartJsMetricValue Model
- [x] Create a HasNovaChartJsChart Trait with relationship and abstract method to return novaChartJsSettings
- [x] Create a basic chart view for NovaChartJsChart Field Type and add it to Detail Field
- [ ] Add a list to compare model with other models
- [ ] Add option to add another model for comparison
- [ ] Add option to remove a model from comparison
- [ ] Create NovaChartJsChartMetricEditor
- [ ] Add NovaChartJsChartMetricEditor to Form Field

## Class Diagram
![Database Model](https://i.imgur.com/CMsJ7NK.jpg "Class Diagram")

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email brandon@kirschbaumdevelopment.com or nathan@kirschbaumdevelopment.com instead of using the issue tracker.

## Sponsorship

Development of this package is sponsored by Kirschbaum Development Group, a developer driven company focused on problem solving, team building, and community. Learn more [about us](https://kirschbaumdevelopment.com) or [join us](https://careers.kirschbaumdevelopment.com)!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
