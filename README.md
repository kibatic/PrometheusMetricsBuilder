Prometheus Metrics Builder
==========================

This library helps you to build a Prometheus metrics endpoint for your PHP application.

Quick start
-----------

### Install the library

```shell
composer require kibatic/prometheus-metrics-builder
```

### Basic usage

```php
$metricList = new MetricList();
$metricList->addMetric(new Metric('foo', 1));
$metricList->addMetric(new Metric('bar', "NaN"));
$metricList->addMetric(new Metric(
    'bar',
    "NaN",
    [
        'label1' => 'value1',
        'label2' => 'value2',
    ]
));
$response = $metricList->getResponseContent();
$metricList->clearMetrics();
```


More advanced usage
-------------------

### attributes

```php
$metric = new Metric(
    'my_metric_name',
    12,
    [
        'module' => 'invoice',
        'instance' => 'value2',
    ],
    new \DateTimeImmutable()
)
```

### timestamp with miliseconds

```php
$metric = new Metric(
    'foo',
    1,
    [],
    new \DateTimeImmutable('2020-01-01 00:00:00.012')
);
```

For developpeurs
----------------

### Run tests

```shell
# install all the needed dependencies
make composer

# run unit tests
make test
```

Roadmap
-------

- no plan for the moment

Versions
--------

2022-11-21 : v1.0.1

* add CI with github actions

2022-11-21 : v1.0.0

* initial version
