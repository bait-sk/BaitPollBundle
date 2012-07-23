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
use Bait\PollBundle\Model\VoteGroupManager as BaseVoteGroupManager;
use Bait\PollBundle\Model\VoteGroupInterface;
use Bait\PollBundle\Model\PollInterface;

/**
 * Doctrine 2 ORM specific vote group manager.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
class VoteGroupManager extends BaseVoteGroupManager
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

    public function create(PollInterface $poll)
    {
        $voteGroup =  parent::create($poll);
        $voteGroup->setClientIp($this->request->getClientIp());

        return $voteGroup;
    }


    /**
     *{@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Doctrine 2 ORM specific save.
     *
     * {@inheritDoc}
     */
    public function doSave($voteGroup)
    {
        if (!$voteGroup instanceof VoteGroupInterface) {
            throw new \InvalidArgumentException('Vote Group must be instance of VoteGroupInterface.');
        }
        $this->entityManager->persist($voteGroup);
        $this->entityManager->flush();
    }


}