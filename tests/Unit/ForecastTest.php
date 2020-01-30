<?php


namespace Tests\Unit;


use Carbon\Carbon;
use Tests\TestCase;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;
use WeatherPredictor\Entities\Scales\Celsius;
use WeatherPredictor\Entities\Scales\Fahrenheit;
use WeatherPredictor\Entities\TemperatureFactory;
use WeatherPredictor\Exceptions\InvalidHourException;

class ForecastTest extends TestCase
{

    public function test_create_forecast()
    {
        $scaleCelsius = TemperatureFactory::getTemperatureFromName('celsius');
        $scaleFahrenheit = TemperatureFactory::getTemperatureFromName('fahrenheit');

        $predictionCelsius = new HourlyPrediction('0', 5);
        $predictionFahrenheit = new HourlyPrediction('0', 0);

        $forecastCelsius = new Forecast('amsterdam', $scaleCelsius, 'WeatherPartnerOne', new Carbon(), [$predictionCelsius]);
        $forecastFahrenheit = new Forecast('bogota', $scaleFahrenheit, 'WeatherPartnerTwo', new Carbon(), [$predictionFahrenheit]);

        $this->assertInstanceOf(Forecast::class, $forecastCelsius);
        $this->assertInstanceOf(Celsius::class, $forecastCelsius->getScale());
        $this->assertInstanceOf(Carbon::class, $forecastCelsius->getDate());

        $this->assertInstanceOf(Forecast::class, $forecastFahrenheit);
        $this->assertInstanceOf(Fahrenheit::class, $forecastFahrenheit->getScale());
        $this->assertInstanceOf(Carbon::class, $forecastFahrenheit->getDate());

        $predictionsCelsius = $forecastCelsius->getPredictions();
        $predictionsFahrenheit = $forecastCelsius->getPredictions();

        foreach ($predictionsCelsius as $prediction)
        {
            $this->assertInstanceOf(HourlyPrediction::class, $prediction);
        }

        foreach ($predictionsFahrenheit as $prediction)
        {
            $this->assertInstanceOf(HourlyPrediction::class, $prediction);
        }
    }

    public function test_create_hourly_predictions()
    {
        $prediction = new HourlyPrediction(1, 4);
        $this->assertInstanceOf(HourlyPrediction::class, $prediction);
    }

    public function test_invalid_hourly_predictions_top()
    {
        $this->expectException(InvalidHourException::class);
        $prediction = new HourlyPrediction(25, 4);
    }

    public function test_invalid_hourly_predictions_floor()
    {
        $this->expectException(InvalidHourException::class);
        $prediction = new HourlyPrediction(-1, 4);
    }
}
