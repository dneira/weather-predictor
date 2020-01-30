<?php


namespace WeatherPredictor\Repositories;

use Doctrine\Common\Persistence\ObjectRepository;

interface BasicRepository extends ObjectRepository
{
    /**
     * @param int $id
     * @return object
     */
    public function get(int $id);

    /**
     * @param int $id
     * @return object|null
     */
    public function findOne(int $id);

    /**
     * @return array
     */
    public function all();
}
