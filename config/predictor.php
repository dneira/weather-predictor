<?php


use WeatherPredictor\Entities\Partners\WeatherPartnerOne;
use WeatherPredictor\Entities\Partners\WeatherPartnerThree;
use WeatherPredictor\Entities\Partners\WeatherPartnerTwo;
use WeatherPredictor\Entities\Scales\Celsius;
use WeatherPredictor\Entities\Scales\Fahrenheit;

return [
    'temperatureScalesAvailable' => [
        Celsius::class,
        Fahrenheit::class,
    ],
    'weatherPartners' => [
        WeatherPartnerOne::class,
        WeatherPartnerTwo::class,
        WeatherPartnerThree::class,
    ],
    'rangeOfDays' => 10,
    'cacheExpirationInSeconds' => 60,
];
