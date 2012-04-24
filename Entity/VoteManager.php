<?php

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\EntityManager;
use Bait\PollBundle\Model\VoteManager as BaseVoteManager;
use Bait\PollBundle\Model\VoteInterface;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class VoteManager extends BaseVoteManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
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
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param string $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($class);
        $this->class = $class;
    }

    public function findBy($criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function doSave($votes)
    {
        foreach ($votes as $vote) {
            if (!$vote instanceof VoteInterface) {
                throw new InvalidArgumentException('Vote must be instance of VoterInterface.');
            }

            $this->entityManager->persist($vote);
        }

        $this->entityManager->flush();
    }

    public function getClass()
    {
        return $this->class;
    }
}
