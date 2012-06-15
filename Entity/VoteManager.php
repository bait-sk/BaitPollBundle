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

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Bait\PollBundle\Model\VoteManager as BaseVoteManager;
use Bait\PollBundle\Model\VoteInterface;
use Bait\PollBundle\Model\FieldInterface;

/**
 * Doctrine 2 ORM specific vote manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class VoteManager extends BaseVoteManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $cookiePrefix;

    /**
     * @param EntityManager $entityManager
     * @param string $class
     */
    public function __construct(EntityManager $entityManager, Request $request, $class, $cookiePrefix)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($class);
        $this->request = $request;
        $this->class = $class;
        $this->cookiePrefix = $cookiePrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function findBy($criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Doctrine 2 ORM specific save.
     *
     * {@inheritDoc}
     */
    public function doSave(array $votes)
    {
        foreach ($votes as $vote) {
            if (!$vote instanceof VoteInterface) {
                throw new \InvalidArgumentException('Vote must be instance of VoterInterface.');
            }

            $this->entityManager->persist($vote);
        }

        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function doCountByField(FieldInterface $field)
    {
        $query = $this->entityManager->createQuery(
            sprintf("SELECT COUNT(v.id) FROM %s v WHERE v.value = '%s'", $this->class, $field->getId())
        );

        return $query->getSingleScalarResult();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}
