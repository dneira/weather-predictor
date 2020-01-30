<?php


namespace WeatherPredictor\Entities\Scales;


class Celsius implements TemperatureScaleContract
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Celsius';
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return '°C';
    }

    /**
     * @param float $value
     * @return float
     */
    public function toCelsius(float $value): float
    {
        return $value;
    }

    /**
     * @param float $value
     * @return float
     */
    public function fromCelsius(float $value): float
    {
        return $value;
    }
}
