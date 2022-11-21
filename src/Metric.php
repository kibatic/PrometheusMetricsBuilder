<?php

namespace Kibatic\PrometheusMetricsBuilder;

class Metric
{
    /** can content only [a-zA-Z0-9:_] */
    protected string $name;

    /**
     * @var float|string : can be a float or a string ("NaN", "+Inf", "-Inf")
     */
    protected $value;
    /** @var Label[] */
    protected array $labels;
    protected ?\DateTimeInterface $happendAt;

    /**
     * @example
     * $metric = new Metric('foo', 1.0, ["key1" => "value1", "key2" => "value2"], new \DateTimeImmutable());
     */
    public function __construct(string $name, $value, array $labels = [], \DateTimeInterface $happendAt = null)
    {
        $this->setName($name);
        $this->setValue($value);
        $labelList = [];
        foreach ($labels as $labelName => $labelValue) {
            $labelList[] = new Label($labelName, $labelValue);
        }
        $this->setLabels($labelList);
        $this->setHappendAt($happendAt);
    }

    public function toString(): string
    {
        $labelString = '';
        if (count($this->labels) > 0) {
            $labelList = [];
            foreach ($this->getLabels() as $label) {
                $labelList[] = $label->toString();
            }
            $labelString = '{'.implode(',', $labelList).'}';
        }
        $happendAt = $this->getHappendAt();
        $happendAtString = $happendAt instanceof \DateTimeInterface ? " ".(int)$happendAt->format('Uv') : '';
        return sprintf(
            '%s%s %s%s',
            $this->getName(),
            $labelString,
            $this->getValue(),
            $happendAtString
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function setName(string $name): self
    {
        // if one char does not match [a-zA-Z0-9:_], throw an exception
        if (preg_match('/[^a-zA-Z0-9:_]/', $name)) {
            throw new MetricException('Invalid metric name. It Should only contain [a-zA-Z0-9:_]');
        }
        $this->name = $name;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function setValue($value): self
    {
        if (is_numeric($value)) {
            $this->value = (float)$value;
        } elseif (in_array($value, ['NaN', '+Inf', '-Inf'])) {
            $this->value = $value;
        } else {
            throw new MetricException('Invalid value (not a number or "NaN", "+Inf", "-Inf"');
        }
        return $this;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    protected function setLabels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    public function getHappendAt(): ?\DateTimeInterface
    {
        return $this->happendAt;
    }

    protected function setHappendAt(?\DateTimeInterface $happendAt): self
    {
        $this->happendAt = $happendAt;
        return $this;
    }
}
