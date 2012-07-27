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
 * Interface defining shape of voteGroup managers in this bundle.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
interface VoteGroupManagerInterface
{
    /**
     * Creates vote group instance.
     *
     * @param PollInterface $poll Poll
     *
     * @return VoteGroupInterface
     */
    public function create(PollInterface $poll);

    /**
     * Saves vote group to DB.
     *
     * @param VoteGroupInterface $voteGroup
     * @return VoteGroupInterface
     */
    public function save(VoteGroupInterface $voteGroup);

    /**
     * Checks if user has already voted in poll.
     *
     * @return boolean
     */
    public function hasVoted(PollInterface $poll);

    /**
     * Check if anonymous user has already voted in poll.
     *
     * @return boolean
     */
    public function hasVotedAnonymously(PollInterface $poll);
}