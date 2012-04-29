<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\EntityManager;
use Bait\PollBundle\Model\PollManager as BasePollManager;

/**
 * Doctrine 2 ORM dependant poll manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class PollManager extends BasePollManager
{
    /**
     * @var \Doctrine\ORM\EntityRepository
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

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Finds all polls.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }
}
