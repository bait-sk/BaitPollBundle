<?php

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\EntityManager;
use Bait\PollBundle\Model\PollInterface;
use Bait\PollBundle\Model\PollManager as BasePollManager;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class PollManager extends BasePollManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $repository;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param string $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->repository = $entityManager->getRepository($class);
    }

    public function findOneBy($criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findBy($criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}
