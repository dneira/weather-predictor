<?php


namespace WeatherPredictor\Entities;


use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Support\Collection;
use WeatherPredictor\Entities\Scales\TemperatureScaleContract;
use WeatherPredictor\Exceptions\ScaleNotFoundException;

class Forecast
{

    /** @var int */
    private $id;

    /** @var string */
    private $city;

    /** @var string */
    private $scale;

    /** @var string */
    private $partner;

    /** @var Carbon */
    private $date;

    /** @var ArrayCollection */
    private $predictions;

    /** @var Carbon */
    private $createdAt;

    /** @var Carbon */
    private $updatedAt;

    /**
     * Forecast constructor.
     * @param string $city
     * @param TemperatureScaleContract $scale
     * @param string $partner
     * @param Carbon $date
     * @param array $predictions
     * @throws \Exception
     */
    public function __construct(string $city, TemperatureScaleContract $scale, string $partner, Carbon $date, array $predictions)
    {
        $this->city = strtolower($city);
        $this->scale = class_basename($scale);
        $this->partner = $partner;
        $this->date = $date;
        $this->predictions = new ArrayCollection();
        $this->createdAt = new Carbon();

        /** @var HourlyPrediction $prediction */
        foreach ($predictions as $prediction) {
            $prediction->setForecast($this);
            $this->predictions->add($prediction);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return TemperatureScaleContract
     * @throws ScaleNotFoundException
     */
    public function getScale(): TemperatureScaleContract
    {
        return TemperatureFactory::getTemperatureByScale($this->scale);
    }

    /**
     * @return string
     */
    public function getPartner(): string
    {
        return $this->partner;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getPredictions()
    {
        return $this->predictions->getValues();
    }

}
