<?php


namespace WeatherPredictor\Entities\Scales;


class Fahrenheit implements TemperatureScaleContract
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Farenheit';
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return '°F';
    }

    /**
     * @param float $value
     * @return float
     */
    public function toCelsius(float $value): float
    {
        return (($value - 32) * 5) / 9;
    }

    /**
     * @param float $value
     * @return float
     */
    public function fromCelsius(float $value): float
    {
        return (($value * 9) / 5) + 32;
    }
}
