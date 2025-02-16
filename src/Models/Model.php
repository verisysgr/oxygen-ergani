<?php

namespace OxygenSuite\OxygenErgani\Models;

use BackedEnum;
use OxygenSuite\OxygenErgani\Traits\HasAttributes;

class Model
{
    use HasAttributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Converts the object and its attributes into an associative array.
     *
     * @return array An array representation of the object attributes.
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->attributes() as $key => $value) {
            if ($value instanceof Model) {
                $array[$key] = $value->toArray();
            } elseif ($value instanceof BackedEnum) {
                $array[$key] = $value->value;
            } elseif (is_array($value)) {
                $array[$key] = array_map(fn ($v) => $v instanceof Model ? $v->toArray() : $v, $value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }
}
