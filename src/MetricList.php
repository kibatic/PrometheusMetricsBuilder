<?php

namespace Kibatic\PrometheusMetricsBuilder;

class MetricList
{
    protected array $metrics = [];

    public function addMetric(Metric $metric): self
    {
        $this->metrics[] = $metric;
        return $this;
    }

    public function getResponseContent(): string
    {
        $metricList = [];
        foreach ($this->metrics as $metric) {
            $metricList[] = $metric->toString();
        }
        return implode("\n", $metricList);
    }

    public function clearMetrics(): self
    {
        $this->metrics = [];
        return $this;
    }
}
