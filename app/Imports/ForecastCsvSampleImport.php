<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Exceptions\ScaleNotFoundException;
use WeatherPredictor\Services\Forecasts;

class ForecastCsvSampleImport implements ToCollection
{
    /**
     * @var string
     */
    private $partner;

    /**
     * ForecastCsvSampleImport constructor.
     * @param string $partner
     */
    public function __construct(string $partner)
    {
        $this->partner = $partner;
    }

    /**
     * @param Collection $rows
     * @return void
     * @throws ScaleNotFoundException
     * @throws \Exception
     */
    public function collection(Collection $rows)
    {
        //Remove the first row of the file (title)
        $rows = $rows->filter( function ($row) use ($rows) {
            return $row != $rows->first();
        });

        $dayForecastData = [];

        $city = null;
        $fileTemperature = null;
        $date = null;

        //Now iterate and collect the data for add to the forecast database
        /** @var Collection $row */
        foreach ($rows as $row)
        {
            if ($row == $rows->first())
            {
                /** @var string $fileScale */
                $fileScale = $row[0];
                $city = $row[1];
                $date = $row[2];
                $fileTemperature = TemperatureFactory::getTemperatureFromName($fileScale);
            }

            $hour = (int) substr($row[3], 0, 2);
            $temperatureValue = $row[4];
            $dayForecastData[] =  new HourlyPrediction($hour, $temperatureValue);
        }

        $forecast = new Forecast($city, $fileTemperature, $this->partner, Carbon::createFromFormat('Ymd', $date), $dayForecastData);

        /*
         * This is not way to do the things, normally this method will return the Forecast object but the documentation said:
         * - Whatever you return in the collection() method will not be returned to the controller.
         * Source: https://docs.laravel-excel.com/3.1/imports/collection.html
         */

        /** @var Forecasts $forecastService */
        $forecastService  = app()->make('WeatherPredictor\Services\Forecasts');
        $forecastService->create($forecast);
    }
}
