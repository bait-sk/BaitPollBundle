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
use Bait\PollBundle\Model\FieldManager as BaseFieldManager;
use Bait\PollBundle\Model\PollInterface;

/**
 * Doctrine 2 ORM dependant field manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class FieldManager extends BaseFieldManager
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

}

