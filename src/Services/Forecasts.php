<?php


namespace WeatherPredictor\Services;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use ReflectionException;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\WeatherPartnerContract;
use WeatherPredictor\Exceptions\ScaleNotFoundException;
use WeatherPredictor\Repositories\BasicPersistRepository;
use WeatherPredictor\Repositories\Filters\ForecastFilter;
use WeatherPredictor\Repositories\ForecastRepository;

class Forecasts
{
    /**
     * @var BasicPersistRepository
     */
    private $persistRepository;
    /**
     * @var ForecastRepository
     */
    private $forecastRepository;

    /**
     * Forecasts constructor.
     * @param BasicPersistRepository $persistRepository
     * @param ForecastRepository $forecastRepository
     */
    public function __construct(BasicPersistRepository $persistRepository, ForecastRepository $forecastRepository)
    {
        $this->persistRepository = $persistRepository;
        $this->forecastRepository = $forecastRepository;
    }

    /**
     * @param Forecast $forecast
     * @return Forecast
     */
    public function create(Forecast $forecast): Forecast
    {
        $this->persistRepository->persist($forecast);
        return $forecast;
    }

    /**
     *
     * Removes the data from the partners from the given city and date for load and have stored the updated forecasts.
     *
     * @param string $city
     * @param Carbon $date
     * @throws ReflectionException
     */
    public function flushByDay(string $city, Carbon $date) {

        $forecastFilter = new ForecastFilter([
            ForecastFilter::CITY => $city,
            ForecastFilter::DATE => $date->startOfDay()->toDateTime()
        ]);

        $forecasts = $this->forecastRepository->getForecast($forecastFilter);

        foreach ($forecasts as $forecast) {
            $this->persistRepository->remove($forecast);
        }
    }

    /**
     * @param string $city
     * @param TemperatureScaleContract $scale
     * @param Carbon $date
     * @return array
     * @throws ScaleNotFoundException
     * @throws ReflectionException
     */
    public function doTheMagic(string $city, TemperatureScaleContract $scale, Carbon $date)
    {
        $forecastData = [];

        $cacheKey = strtolower('predictions.'.$city.'.'.$date->format('Ymd').'.'.$scale->getName());
        if (Cache::has($cacheKey)) {
            $forecastData = Cache::get($cacheKey);
        } else {
            $this->fetchPartnersData($city, $scale, $date);
            $rawForecasts = $this->getForecast($city, $date);
            $forecastData = $this->getAverageInScale($rawForecasts, $scale);
            $this->addToCache($cacheKey, json_encode($forecastData));
        }

        return $forecastData;
    }

    /**
     *
     * Loads the data from the external partners and flush and save the updated forecasts to the database.
     *
     * @param string $city
     * @param TemperatureScaleContract $scale
     * @param Carbon $date
     * @throws ReflectionException
     */
    public function fetchPartnersData(string $city, TemperatureScaleContract $scale, Carbon $date)
    {

        $weatherPartners = config('predictor.weatherPartners');

        foreach ($weatherPartners as $partner)
        {
            /**
             * Check if there is some existing data for the current forecast request and load new data when there is no existing data in the database.
             */
            $existingData = $this->forecastRepository->findByPartnerCityAndDate(class_basename($partner), $city, $date);

            if ($existingData) {
                $this->flushByDay($city, $date);
            }
            /** @var WeatherPartnerContract $partner */
            $partner = new $partner;
            $forecast = $partner->loadForecast($city, $date, $scale, []);
            /** This is because the PartnerOne use the Importer and have no return in the implementation (see comment in the ParterOne implementation). I guess this can be improved with another approach */
            if ($forecast) {
                $this->create($forecast);
            }
        }
    }

    /**
     *
     * Get the forecasts stored in the database filtering by city and date.
     *
     * @param string $city
     * @param Carbon $date
     * @return array
     * @throws ReflectionException
     */
    public function getForecast(string $city, Carbon $date)
    {
        $forecastFilter = new ForecastFilter([
            ForecastFilter::CITY => $city,
            ForecastFilter::DATE => $date->startOfDay()->toDateTime()
        ]);

        return $this->forecastRepository->getForecast($forecastFilter);
    }

    /**
     *
     * Converts between scales for transform and show the degree values in the requested scale.
     *
     * @param TemperatureScaleContract $scaleFrom
     * @param TemperatureScaleContract $scaleTo
     * @param float $value
     * @return float
     */
    public function convertValueToScale(TemperatureScaleContract $scaleFrom, TemperatureScaleContract $scaleTo, float $value) : float
    {
        return $scaleTo->fromCelsius($scaleFrom->ToCelsius($value));
    }

    /**
     *
     * This method dot the magic of loading the forecasts, organize, transform the forecasts and finally calculate the hourly average as expected.
     *
     * @param array $forecasts
     * @param TemperatureScaleContract $outputScale
     * @return array
     * @throws ScaleNotFoundException
     */
    public function getAverageInScale(array $forecasts, TemperatureScaleContract $outputScale)
    {
        $hourly = [];
        $hourlyAverage = [];

        /** @var Forecast $forecast */
        foreach($forecasts as $forecast) {
            $forecastScale =  $forecast->getScale();
            $sameScale = class_basename($forecastScale) == class_basename($outputScale);
            $predictions = $forecast->getPredictions();
            foreach ($predictions as $prediction) {
                $hourly[$prediction->getHour()][] = $sameScale ?  $prediction->getValue() : $this->convertValueToScale($forecastScale, $outputScale, $prediction->getValue());
            }
        }

        /** @var Collection $hourlyCollection */
        $hourlyCollection = new Collection(collect($hourly));

        foreach ($hourlyCollection as $hour => $value)
        {
            $hourlyValues = collect($value);
            $hourlyAverage[] = [
                'hour' => $hour,
                'value' => $hourlyValues->avg(),
            ];
        }

        return $hourlyAverage;
    }

    /**
     *
     * This method just stores the forecast in the cache
     *
     * @param string $key
     * @param mixed $data
     */
    private function addToCache(string $key, $data) : void {
        $cacheExpiration = config('predictor.cacheExpirationInSeconds');
        Cache::put($key, $data, $cacheExpiration);
    }

}
