<?php


namespace WeatherPredictor\Entities;

use Carbon\Carbon;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;

interface WeatherPartnerContract
{
    /**
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return mixed
     */
    public function loadForecast(string $city, Carbon $day, TemperatureScaleContract $scale, $customParams = []);
}
