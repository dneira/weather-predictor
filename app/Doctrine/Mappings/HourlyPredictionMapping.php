<?php


namespace App\Doctrine\Mappings;


use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;

class HourlyPredictionMapping extends EntityMapping
{

    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return HourlyPrediction::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->integer('id')->autoIncrement()->primary();
        $builder->integer('hour');
        $builder->float('value');
        $builder->manyToOne(Forecast::class, 'forecast')->inversedBy('predictions')->cascadeAll();

    }
}
