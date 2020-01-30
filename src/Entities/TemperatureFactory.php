<?php


namespace WeatherPredictor\Entities;

use Illuminate\Http\Response;
use WeatherPredictor\Exceptions\ScaleNotFoundException;

class TemperatureFactory
{

    /**
     * @param string $className
     * @return mixed
     * @throws ScaleNotFoundException
     */
    public static function getTemperatureByScale(string $className)
    {
        $className = __NAMESPACE__ . '\\' .'Scales'. '\\' . $className;

        if ( !class_exists( $className ) ) {
            throw new ScaleNotFoundException('Scale not found or not available for the predictor.', Response::HTTP_BAD_REQUEST);
        }

        return new $className();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ScaleNotFoundException
     */
    public static function getTemperatureFromName(string $name) {
        $availableScales = config('predictor.temperatureScalesAvailable');

        $filteredTemperatures = array_filter($availableScales, function ($availableScale) use ($name)
        {
            return strtolower(class_basename($availableScale)) == strtolower($name);
        });

        $filteredTemperature = array_first($filteredTemperatures);

        return self::getTemperatureByScale(class_basename($filteredTemperature));
    }

}
