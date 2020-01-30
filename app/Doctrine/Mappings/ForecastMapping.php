<?php


namespace App\Doctrine\Mappings;


use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Entities\HourlyPrediction;

class ForecastMapping extends EntityMapping
{

    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return Forecast::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     */
    public function map(Fluent $builder)
    {
        $builder->integer('id')->autoIncrement()->primary();
        $builder->text('city');
        $builder->oneToMany(HourlyPrediction::class, 'predictions')->mappedBy('forecast')->cascadeAll();
        $builder->string( 'scale');
        $builder->string( 'partner');
        $builder->date('date');
        $builder->dateTime('createdAt');
        $builder->dateTime( 'updatedAt')->nullable();
    }
}
