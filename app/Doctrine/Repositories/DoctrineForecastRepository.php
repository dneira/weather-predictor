<?php


namespace App\Doctrine\Repositories;

use Carbon\Carbon;
use Doctrine\ORM\NonUniqueResultException;
use WeatherPredictor\Entities\Forecast;
use WeatherPredictor\Repositories\Filters\ForecastFilter;
use WeatherPredictor\Repositories\ForecastRepository;

class DoctrineForecastRepository extends DoctrineBasicRepository implements ForecastRepository
{

    /**
     * @param ForecastFilter $filter
     * @return array
     */
    public function getForecast(ForecastFilter $filter): array
    {
        $alias = 'forecasts';
        $queryBuilder = $this->createQueryBuilder($alias);

        if ($filter->has($filter::CITY)) {
            $queryBuilder->andWhere("$alias.city = :city");
            $queryBuilder->setParameter('city', $filter->get($filter::CITY));
        }

        if ($filter->has($filter::DATE)) {
            $queryBuilder->andWhere("$alias.date = :forecastDate");
            $queryBuilder->setParameter('forecastDate', $filter->get($filter::DATE));
        }

        return $queryBuilder->getQuery()->getResult();


    }

    /**
     * @param string $partner
     * @param string $city
     * @param Carbon $date
     * @return Forecast|null
     * @throws NonUniqueResultException
     */
    public function findByPartnerCityAndDate(string $partner, string $city, Carbon $date): ?Forecast
    {
        $alias = 'forecasts';
        $queryBuilder = $this->createQueryBuilder($alias);

        $queryBuilder->andWhere("$alias.partner = :partner");
        $queryBuilder->andWhere("$alias.city = :city");
        $queryBuilder->andWhere("$alias.date = :forecastDate");
        $queryBuilder->setParameter('partner', $partner);
        $queryBuilder->setParameter('city', $city);
        $queryBuilder->setParameter('forecastDate', $date->startOfDay()->toDateTime());

        $queryBuilder->setMaxResults(1)->orderBy("$alias.createdAt", 'desc');

        return $queryBuilder->getQuery()->getOneOrNullResult();

    }
}
