<?php


namespace WeatherPredictor\Entities\Partners;


use Carbon\Carbon;
use SimpleXMLElement;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Entities\WeatherPartnerContract;
use WeatherPredictor\Exceptions\ScaleNotFoundException;

class WeatherPartnerThree implements WeatherPartnerContract
{

    /**
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return mixed
     * @throws ScaleNotFoundException
     * @throws \Exception
     */
    public function loadForecast(string $city, Carbon $day, TemperatureScaleContract $scale, $customParams = [])
    {
        $data = $this->fetchXml($city, $day, $scale, $customParams);
        $attributes = $data->attributes();

        $scale = TemperatureFactory::getTemperatureFromName(strval($attributes->scale));
        $city = strval($data->city);
        $date = Carbon::createFromFormat('Ymd', strval($data->date));

        $dayForecastData = [];

        foreach ($data->prediction as $prediction) {
            $dayForecastData[] = new HourlyPrediction(substr($prediction->time, 0, 2), floatval($prediction->value));
        }

        $forecast = new Forecast($city, $scale, class_basename($this), $date, $dayForecastData);

        return $forecast;
    }


    /**
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return SimpleXMLElement
     */
    private function fetchXml(string $city, Carbon $day, TemperatureScaleContract $scale, $customParams = [])
    {
        return simplexml_load_file(public_path('samples/temps.xml'));
    }
}
