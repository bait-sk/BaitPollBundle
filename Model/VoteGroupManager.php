<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\Model;

/**
 * Connection agnostic voteGroup manager.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
abstract class VoteGroupManager implements VoteGroupManagerInterface {
    /**
     * {@inheritDoc}
     */
    public function create(PollInterface $poll)
    {
        $voteGroupClass = $this->getClass();

        $voteGroup = new $voteGroupClass();
        $voteGroup->setPoll($poll);

        return $voteGroup;
    }

    /**
     * {@inheritDoc}
     */
    public function save(VoteGroupInterface $voteGroup)
    {
        $this->doSave($voteGroup);
    }

    /**
     * Connection dependant save.
     *
     * @paramter VoteGroupInterface $voteGroup
     */
    abstract public function doSave($voteGroup);

    /**
     * Gets class name of vote.
     *
     * @return string
     */
    abstract public function getClass();

    /**
     * {@inheritDoc}
     */
    public function hasVoted(PollInterface $poll)
    {
        return $this->hasVotedAnonymously($poll);
    }

    /**
     * {@inheritDoc}
     */
    public function hasVotedAnonymously(PollInterface $poll)
    {
        if ($this->request->cookies->has(sprintf('%svoted_%s', $this->cookiePrefix, $poll->getId()))) {
            return true;
        }

        return false;
    }
}