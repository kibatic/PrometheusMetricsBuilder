<?php

namespace Kibatic\PrometheusMetricsBuilder\Tests;

use Kibatic\PrometheusMetricsBuilder\Metric;
use Kibatic\PrometheusMetricsBuilder\MetricList;
use PHPUnit\Framework\TestCase;

class MetricListTest extends TestCase
{
    public function testAddMetric(): void
    {
        $metricList = new MetricList();
        $metricList->addMetric(new Metric('foo', 1));
        $metricList->addMetric(new Metric('bar', "NaN"));
        $this->assertSame(
            "foo 1\nbar NaN",
            $metricList->getResponseContent()
        );
    }

    public function testClearList(): void
    {
        $metricList = new MetricList();
        $metricList->addMetric(new Metric('foo', 1));
        $metricList->addMetric(new Metric('bar', "NaN"));
        $metricList->clearMetrics();
        $this->assertSame('', $metricList->getResponseContent());
    }
}
