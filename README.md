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

You must also define a `getNovaChartjsSettings` function in the model which should rturn the required settings for the Chart.

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

    // ...
}
```
### NovaChartjs Field

Coming Soon

## To-Do
- [x] Setup Repo
- [x] Create a NovaChartJsMetricValue Model
- [x] Create a HasNovaChartJsChart Trait with relationship and abstract method to return novaChartJsSettings
- [ ] Create a basic chart view for NovaChartJsChart Field Type and add it to Detail Field
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
