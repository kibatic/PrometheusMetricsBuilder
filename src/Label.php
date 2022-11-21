<?php

namespace Kibatic\PrometheusMetricsBuilder;

class Label
{
    /** can content only [a-zA-Z0-9:_] */
    private string $name;
    // can be any UTF-8 string with escaping for ", \ and newlines
    private string $value;

    public function __construct(string $name, string $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    public function toString(): string
    {
        $value = $this->getValue();
        // escape double quotes, backslash and line feed from value
        $value = str_replace(
            ['"', '\\', "\n"],
            ['\"', '\\\\', '\\n'],
            $value
        );
        return sprintf('%s="%s"', $this->name, $value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function setName(string $name): self
    {
        // if one char does not match [a-zA-Z0-9:_], throw an exception
        if (preg_match('/[^a-zA-Z0-9:_]/', $name)) {
            throw new MetricException('Invalid label name. It Should only contain [a-zA-Z0-9:_]');
        }
        $this->name = $name;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    protected function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
