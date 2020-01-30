<?php

namespace App\Doctrine\Repositories;

use Doctrine\ORM\EntityManager;
use WeatherPredictor\Repositories\BasicPersistRepository;

class DoctrineBasicPersistRepository implements BasicPersistRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($entities)
    {
        if ($entities) {
            if (! is_iterable($entities)) {
                $entities = [$entities];
            }

            foreach ($entities as $entity) {
                $this->entityManager->persist($entity);
            }

            $this->entityManager->flush();
        }
    }

    public function transactional(callable $function)
    {
        return $this->entityManager->getConnection()->transactional($function);
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function clear()
    {
        $this->entityManager->clear();
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function detach($entity)
    {
        $this->entityManager->detach($entity);
    }
}
