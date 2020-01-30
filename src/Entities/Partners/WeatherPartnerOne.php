<?php


namespace WeatherPredictor\Entities\Partners;


use App\Imports\ForecastCsvSampleImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\WeatherPartnerContract;

class WeatherPartnerOne implements WeatherPartnerContract
{
    /**
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return mixed
     */
    public function loadForecast(string $city, Carbon $day, TemperatureScaleContract $scale, $customParams = [])
    {
       $data = $this->fetchCsv($city, $day, $scale, $customParams);
    }

    /**
     *
     * Here we mock the response of a CSV response example from the external partner assuming some customParams and expected response based on partner documentation.
     * With the $coolFeature $param we will use the third party package for process excel or csv files.
     *
     * @param string $city
     * @param Carbon $day
     * @param TemperatureScaleContract $scale
     * @param array $customParams
     * @return mixed
     */
    private function fetchCsv(string $city, Carbon $day, TemperatureScaleContract $scale, array $customParams)
    {
        Excel::import(new ForecastCsvSampleImport(class_basename($this)), public_path('samples/temps.csv'));
    }
}
