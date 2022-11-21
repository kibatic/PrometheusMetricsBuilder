<?php

namespace Kibatic\PrometheusMetricsBuilder\Tests;

use Kibatic\PrometheusMetricsBuilder\Metric;
use PHPUnit\Framework\TestCase;
use Kibatic\PrometheusMetricsBuilder\MetricException;

class MetricTest extends TestCase
{
    public function testSetName(): void
    {
        $metric = new Metric('foo', 1);
        $this->assertSame('foo', $metric->getName());
        try {
            $metric = new Metric('foo[', 1);
            $this->fail('Expected exception not thrown');
        } catch (MetricException $e) {
            $this->assertSame('Invalid metric name. It Should only contain [a-zA-Z0-9:_]', $e->getMessage());
        }
    }

    public function testGoodValues(): void
    {
        // value en int
        $metric = new Metric('foo', 1);
        $this->assertSame('foo 1', $metric->toString());
        // value en float
        $metric = new Metric('foo', 1.2);
        $this->assertSame('foo 1.2', $metric->toString());
        // value en string
        $metric = new Metric('foo', "1.2");
        $this->assertSame('foo 1.2', $metric->toString());
        // value en Nan,+Inf, -Inf
        $metric = new Metric('foo', "NaN");
        $this->assertSame('foo NaN', $metric->toString());
        $metric = new Metric('foo', "+Inf");
        $this->assertSame('foo +Inf', $metric->toString());
        $metric = new Metric('foo', "-Inf");
        $this->assertSame('foo -Inf', $metric->toString());
    }

    public function testBadValues(): void
    {
        try {
            $metric = new Metric('foo', 'bar');
            $this->fail('Expected exception not thrown');
        } catch (MetricException $e) {
            $this->assertSame('Invalid value (not a number or "NaN", "+Inf", "-Inf"', $e->getMessage());
        }
        try {
            $metric = new Metric('foo', []);
            $this->fail('Expected exception not thrown');
        } catch (MetricException $e) {
            $this->assertSame('Invalid value (not a number or "NaN", "+Inf", "-Inf"', $e->getMessage());
        }
    }

    public function testLabels(): void
    {
        $metric = new Metric('foo', 1, ['bar' => 'baz']);
        $this->assertSame('foo{bar="baz"} 1', $metric->toString());
        $metric = new Metric('foo', 1, ['bar' => 'baz', 'foo2' => 'bar2']);
        $this->assertSame('foo{bar="baz",foo2="bar2"} 1', $metric->toString());
    }

    public function testHappendAt(): void
    {
        // without microseconds
        $metric = new Metric('foo', 1, [], new \DateTimeImmutable('2020-01-01 00:00:00'));
        $this->assertSame('foo 1 1577836800000', $metric->toString());
        // with microseconds
        $metric = new Metric('foo', 1, [], new \DateTimeImmutable('2020-01-01 00:00:00.012'));
        $this->assertSame('foo 1 1577836800012', $metric->toString());
    }
}
