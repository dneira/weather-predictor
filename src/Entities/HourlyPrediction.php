<?php


namespace WeatherPredictor\Entities;

use Illuminate\Http\JsonResponse;
use WeatherPredictor\Exceptions\InvalidHourException;

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
     * @throws InvalidHourException
     */
    public function __construct(int $hour, float $value)
    {
        $this->hour = $hour;
        $this->value = $value;

        $this->assertValid();
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

    private function assertValid() : void
    {
        $hour = $this->getHour();
        if ($hour < 0 || $hour > 23) {
            throw new InvalidHourException('The hour given is invalid. Must be in the range [0-23]', JsonResponse::HTTP_BAD_REQUEST);
        }
    }

}
