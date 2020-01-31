<?php


namespace WeatherPredictor\Entities\Partners;


use Carbon\Carbon;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Entities\WeatherPartnerContract;
use WeatherPredictor\Exceptions\ScaleNotFoundException;

class WeatherPartnerTwo implements WeatherPartnerContract
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
        $data = $this->fetchJson($city, $day, $scale, $customParams);
        $dataPredictions = json_decode($data);
        $predictions = $dataPredictions->predictions;

        $dayForecastData = [];
        /** @var TemperatureScaleContract $scale */
        $scale = TemperatureFactory::getTemperatureFromName($predictions->{'-scale'});
        $city = $predictions->city;
        $date = Carbon::createFromFormat('Ymd', $predictions->date);
        $dayPredictions = $predictions->prediction;

        foreach ($dayPredictions as $dataPrediction) {
            $hour = (int) substr($dataPrediction->time, 0, 2);
            $dayForecastData[] = new HourlyPrediction($hour, $dataPrediction->value);
        }

        /** @var Forecast $forecast */
        $forecast = new Forecast($city, $scale, class_basename($this), $date, $dayForecastData);

        return $forecast;
    }

    /**
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return false|string
     */
    private function fetchJson(string $city, Carbon $day, TemperatureScaleContract $scale, $customParams = [])
    {
        return file_get_contents(public_path('samples/temps.json'));
    }
}
