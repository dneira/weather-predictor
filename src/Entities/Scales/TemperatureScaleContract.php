<?php


namespace WeatherPredictor\Entities\Scales;


interface TemperatureScaleContract
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getSymbol() : string;

    /**
     * @param float $value
     * @return float
     */
    public function toCelsius(float $value) : float;

    /**
     * @param float $value
     * @return float
     */
    public function fromCelsius(float $value) : float;
}
