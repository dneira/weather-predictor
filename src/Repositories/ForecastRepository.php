<?php


namespace WeatherPredictor\Repositories;


use Carbon\Carbon;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Repositories\Filters\ForecastFilter;

interface ForecastRepository extends BasicRepository
{
    const CLASS_NAME = Forecast::class;

    /**
     * @param ForecastFilter $filter
     * @return array
     */
    public function getForecast(ForecastFilter $filter) : array;

    /**
     * @param string $partner
     * @param string $city
     * @param Carbon $date
     * @return Forecast|null
     */
    public function findByPartnerCityAndDate(string $partner, string $city, Carbon $date): ?Forecast;

}
