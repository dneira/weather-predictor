<?php


namespace WeatherPredictor\Repositories;


interface BasicPersistRepository
{
    /**
     * @param array|object|\Traversable $entities
     */
    public function persist($entities);

    public function remove($entity);

    public function transactional(callable $function);

    public function clear();

    public function flush();

    public function detach($entity);
}
