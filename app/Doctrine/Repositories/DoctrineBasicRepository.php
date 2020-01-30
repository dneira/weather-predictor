<?php

namespace App\Doctrine\Repositories;

use App\Doctrine\Util\ShorterQueryConditions;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use WeatherPredictor\Exceptions\EntityNotFoundException;
use WeatherPredictor\Repositories\BasicRepository;

class DoctrineBasicRepository extends EntityRepository implements BasicRepository
{

    public function __construct(EntityManager $entityManager)
    {
        // Repository interfaces should have a CLASS_NAME constant defining the Class they belong to
        parent::__construct($entityManager, $entityManager->getClassMetadata(static::CLASS_NAME));
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    public function get(int $id)
    {
        $entity = $this->findOne($id);
        if ($entity) {
            return $entity;
        }

        throw new EntityNotFoundException(static::CLASS_NAME);
    }

    public function all()
    {
        return $this->findAll();
    }

    /**
     * @return object|null
     */
    public function findOne(int $id)
    {
        return $this->find($id);
    }
}
