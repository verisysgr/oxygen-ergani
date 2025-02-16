<?php

namespace OxygenSuite\OxygenErgani\Models;

use BackedEnum;
use OxygenSuite\OxygenErgani\Traits\HasAttributes;

class Model
{
    use HasAttributes;

    protected array $expectedOrder = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Create a new model instance.
     *
     * @param  array  $attributes The model attributes
     * @return static
     */
    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }

    /**
     * Returns the model attributes sorted according to the expected order.
     * @return array
     */
    public function toSortedArray(): array
    {
        if (empty($this->expectedOrder)) {
            return $this->toArray();
        }

        $sortedAttributes = [];
        foreach ($this->expectedOrder as $key) {
            if (array_key_exists($key, $this->attributes)) {
                $sortedAttributes[$key] = $this->attributes[$key];
            }
        }

        return $this->processValue($sortedAttributes, true);
    }

    /**
     * Converts the object and its attributes into an associative array.
     *
     * @return array An array representation of the object attributes.
     */
    public function toArray(): array
    {
        return $this->processValue($this->attributes());
    }

    /**
     * Processes a value to convert it to array format.
     *
     * @param mixed $value The value to process
     * @return array The processed value
     */
    protected function processValue(mixed $value, bool $sort = false): mixed
    {
        return match (true) {
            $value instanceof self => $sort ? $value->toSortedArray() : $value->toArray(),
            $value instanceof BackedEnum => $value->value,
            is_array($value) => array_map(fn($item) => $this->processValue($item, $sort), $value),
            default => $value
        };
    }
}
