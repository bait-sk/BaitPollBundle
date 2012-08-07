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
 * Interface defining shape of answerGroup managers in this bundle.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
interface AnswerGroupManagerInterface
{
    /**
     * Creates answer group instance.
     *
     * @param PollInterface $poll Poll
     *
     * @return AnswerGroupInterface
     */
    public function create(PollInterface $poll);

    /**
     * Saves answer group to DB.
     *
     * @param AnswerGroupInterface $answerGroup
     * @return AnswerGroupInterface
     */
    public function save(AnswerGroupInterface $answerGroup);

    /**
     * Checks if user has already submitted an answer in poll.
     *
     * @return boolean
     */
    public function hasAnswered(PollInterface $poll);

    /**
     * Check if anonymous user has already submitted an answer in poll.
     *
     * @return boolean
     */
    public function hasAnsweredAnonymously(PollInterface $poll);
}
