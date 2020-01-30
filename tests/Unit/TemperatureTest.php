<?php


namespace Tests\Unit;


use Tests\TestCase;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Exceptions\ScaleNotFoundException;

class TemperatureTest extends TestCase
{

    public function test_temperature_factory_availables()
    {
        $scales = config('predictor.temperatureScalesAvailable');

        /** @var TemperatureScaleContract $scale */
        foreach ($scales as $scale) {
            $temperatureScale = TemperatureFactory::getTemperatureFromName(class_basename($scale));
            $this->assertInstanceOf($scale, $temperatureScale);
        }

        foreach ($scales as $scale) {
            $temperatureScale = TemperatureFactory::getTemperatureByScale(class_basename($scale));
            $this->assertInstanceOf($scale, $temperatureScale);
        }
    }

    public function test_temperature_factory_invalid_scale()
    {
        $this->expectException(ScaleNotFoundException::class);
        TemperatureFactory::getTemperatureFromName('newton');
    }
}
