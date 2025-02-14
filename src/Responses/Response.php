<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateTime;
use Throwable;

abstract class Response
{
    protected array $rawData = [];

    public function __construct(mixed $data)
    {
        if (!is_array($data)) {
            return;
        }

        $this->rawData = $data;
        $this->processData();
    }

    abstract protected function processData(): void;

    /**
     * Retrieves a datetime value associated with the given key, formatted as 'Y-m-d\TH:i:s.uP'.
     * If the key does not exist, returns the provided default value or null.
     *
     * @param  string  $key  The key to look up in the data array.
     * @param  mixed  $default  The default value to return if the key is not found or invalid.
     * @return DateTime|null The DateTime object created from the value, or null if not found or invalid.
     */
    public function datetime(string $key, mixed $default = null): ?DateTime
    {
        $value = $this->string($key, $default);
        if ($value === null) {
            return null;
        }

        try {
            return new DateTime($value);
        } catch (Throwable) {
            return $default;
        }
    }

    /**
     * Retrieves a string value associated with the given key. If the value is not a string, returns null.
     *
     * @param  string  $key  The key to look up in the data source.
     * @param  mixed  $default  The default value to return if the key is not found.
     * @return string|null The string value associated with the given key, or null if it is not a string.
     */
    public function string(string $key, mixed $default = null): ?string
    {
        $value = $this->get($key, $default);
        return is_string($value) ? $value : null;
    }

    /**
     * Retrieves the value associated with the given key from the data array.
     * If the key does not exist, returns the provided default value.
     *
     * @param  string  $key  The key to look up in the data array.
     * @param  string|null  $default  The default value to return if the key is not found.
     * @return mixed The value associated with the given key or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->rawData[$key] ?? $default;
    }

    /**
     * Retrieves a boolean value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param  string  $key  The key for the value to be retrieved.
     * @param  mixed  $default  The default value to return if the key does not exist.
     * @return ?bool Returns the boolean value if valid, null if the value cannot be determined as boolean.
     */
    public function bool(string $key, mixed $default = null): ?bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Retrieves an integer value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param  string  $key  The key for the value to be retrieved.
     * @param  mixed  $default  The default value to return if the key does not exist.
     * @return ?int Returns the integer value if valid, null if the value cannot be determined as an integer.
     */
    public function int(string $key, mixed $default = null): ?int
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Retrieves a floating-point value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param  string  $key  The key for the value to be retrieved.
     * @param  mixed  $default  The default value to return if the key does not exist.
     * @return ?float Returns the floating-point value if valid, null if the value cannot be determined as a float.
     */
    public function float(string $key, mixed $default = null): ?float
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Transforms an array of values associated with the specified key to a new array
     * of objects of the given type.
     *
     * @param  string  $key  The key for the array to be retrieved.
     * @param  string  $type  The fully qualified class name to which the values should be mapped.
     * @return array Returns an array of objects of the specified type.
     */
    public function morphToArray(string $key, string $type): array
    {
        $value = $this->array($key, []);

        return array_map(fn ($value) => new $type($value), $value);
    }

    /**
     * Retrieves an array value associated with the specified key or returns the default value if the key does not exist.
     * If the retrieved value is not an array, the provided default value will be returned.
     *
     * @param  string  $key  The key for the value to be retrieved.
     * @param  mixed  $default  The default value to return if the key does not exist or the value is not an array.
     * @return ?array Returns the array value if valid, or the default value if the key does not exist or the value is not an array.
     */
    public function array(string $key, mixed $default = null): ?array
    {
        $value = $this->get($key, $default);
        return is_array($value) ? $value : $default;
    }
}