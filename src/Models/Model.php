<?php

namespace OxygenSuite\OxygenErgani\Models;

use BackedEnum;

class Model
{
    protected array $attributes = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function set(string $key, mixed $value): static
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        $this->attributes[$key] = $value;
        return $this;
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->attributes() as $key => $value) {
            if ($value instanceof Model) {
                $array[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $array[$key] = array_map(fn ($v) => $v instanceof Model ? $v->toArray() : $v, $value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }
}
