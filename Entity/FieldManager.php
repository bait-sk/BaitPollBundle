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
class FieldManager extends BaseFieldManager
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
        $this->class = $class;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($class);
    }

    /**
     * {@inheritDoc}
     */
    public function findRenderableOrderedPollFields($pollId) {
        return $this->entityManager->createQuery(
            "SELECT f, c FROM {$this->class} f
            LEFT JOIN f.children c
            WHERE f.poll = :pollId
            AND f.isActive = true
            AND f.deletedAt IS NULL
            ORDER BY f.position ASC, c.position ASC"
        )->setParameters(array('pollId' => $pollId))
        ->getResult();
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}
