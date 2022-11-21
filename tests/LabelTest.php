<?php

namespace Kibatic\PrometheusMetricsBuilder\Tests;

use Kibatic\PrometheusMetricsBuilder\Label;
use Kibatic\PrometheusMetricsBuilder\MetricException;
use PHPUnit\Framework\TestCase;

class LabelTest extends TestCase
{
    public function testSetName(): void
    {
        $label = new Label('foo', 'bar');
        $this->assertSame('foo', $label->getName());
        try {
            $label = new Label('foo$', 'bar');
            $this->fail('Expected exception not thrown');
        } catch (MetricException $e) {
            $this->assertSame('Invalid label name. It Should only contain [a-zA-Z0-9:_]', $e->getMessage());
        }
    }

    public function testValue(): void
    {
        $label = new Label('foo', 'bar');
        $this->assertSame(
            'foo="bar"',
            $label->toString()
        );
        $label = new Label('foo', 'strange chars é€ Éă');
        $this->assertSame(
            'foo="strange chars é€ Éă"',
            $label->toString()
        );
        $label = new Label('foo', 'my great "content"');
        $this->assertSame(
            'foo="my great \\\"content\\\""',
            $label->toString()
        );
        $label = new Label('foo', 'this \\ char');
        $this->assertSame(
            'foo="this \\\\ char"',
            $label->toString()
        );
        $label = new Label('foo', 'with a
newline');
        $this->assertSame(
            'foo="with a\\nnewline"',
            $label->toString()
        );
    }
}
