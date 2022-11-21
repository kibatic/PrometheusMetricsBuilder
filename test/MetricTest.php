<?php

use Kibatic\PrometheusMetricsBuilder\Metric;
use PHPUnit\Framework\TestCase;

class MetricTest extends TestCase
{
    public function testCopy(): void
    {
        $metric = new Metric();
        $this->assertSame(1, $metric->copy(1));
    }
}
