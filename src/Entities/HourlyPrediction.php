<?php


namespace WeatherPredictor\Entities;


class HourlyPrediction
{

    /** @var int */
    private $id;

    /** @var int */
    private $hour;

    /** @var float */
    private $value;

    /** @var Forecast */
    private $forecast;

    /**
     * HourlyPrediction constructor.
     * @param int $hour
     * @param float $value
     */
    public function __construct(int $hour, float $value)
    {
        $this->hour = $hour;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return Forecast
     */
    public function getForecast(): Forecast
    {
        return $this->forecast;
    }

    /**
     * @param Forecast $forecast
     */
    public function setForecast(Forecast $forecast): void
    {
        $this->forecast = $forecast;
    }

}
