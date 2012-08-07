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
use Bait\PollBundle\Model\AnswerGroupManager as BaseAnswerGroupManager;
use Bait\PollBundle\Model\AnswerGroupInterface;
use Bait\PollBundle\Model\PollInterface;

/**
 * Doctrine 2 ORM specific answer group manager.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
class AnswerGroupManager extends BaseAnswerGroupManager
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
        $answerGroup =  parent::create($poll);
        $answerGroup->setClientIp($this->request->getClientIp());

        return $answerGroup;
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
    public function doSave($answerGroup)
    {
        if (!$answerGroup instanceof AnswerGroupInterface) {
            throw new \InvalidArgumentException('Answer Group must be instance of AnswerGroupInterface.');
        }
        $this->entityManager->persist($answerGroup);
        $this->entityManager->flush();
    }
}
