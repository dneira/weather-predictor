<?php


namespace WeatherPredictor\Util;


abstract class Filter
{
    protected $values = [];

    private $keys = [];

    /**
     * Filter constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct(array $data)
    {
        $oClass = new \ReflectionClass(get_called_class());
        $keys = array_values($oClass->getConstants());

        $this->keys = $keys;
        $this->values = array_only($data, $keys);
    }

    /**
     * Returns NULL if value is empty() === true.
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        $value = array_get($this->values, $key, $default);

        return empty($value) ? null : $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getRaw(string $key, $default = null)
    {
        return array_get($this->values, $key, $default);
    }

    /**
     * Returns an empty array as default.
     * @param string $key
     * @return array|mixed
     */
    public function getArray(string $key)
    {
        $value = $this->getRaw($key);

        return empty($value) ? [] : $value;
    }

    public function has(string $key)
    {
        return array_has($this->values, $key);
    }

    public function isNotEmpty(string $key)
    {
        return ! empty($this->getRaw($key));
    }

    public function keys()
    {
        return $this->keys;
    }

    public function values()
    {
        return $this->values;
    }
}

