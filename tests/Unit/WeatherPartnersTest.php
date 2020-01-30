<?php


namespace Tests\Unit;


use Carbon\Carbon;
use Tests\TestCase;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Entities\WeatherPartnerContract;
use WeatherPredictor\Services\Forecasts;

class WeatherPartnersTest extends TestCase
{

    public function test_fetch_data_from_partners()
    {
        $weatherPartners = config('predictor.weatherPartners');

        /** @var WeatherPartnerContract $partner */
        foreach ($weatherPartners as $partner) {
            $scales = config('predictor.temperatureScalesAvailable');
            /** @var TemperatureScaleContract $scale */
            foreach ($scales as $scale) {
                $scale = TemperatureFactory::getTemperatureFromName(class_basename($scale));
                /** @var WeatherPartnerContract $weatherPartner */
                $weatherPartner = new $partner;
                $forecast = $weatherPartner->loadForecast('amsterdam', new Carbon(), $scale, []);

                if ($forecast) {
                    $forecasts = $this->createMock(Forecasts::class);
                    $newForecast = $forecasts->create($forecast);
                    $this->assertInstanceOf(Forecast::class, $newForecast);
                }
            }
        }
    }

}
