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
 * Connection agnostic answerGroup manager.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
abstract class AnswerGroupManager implements AnswerGroupManagerInterface {
    /**
     * {@inheritDoc}
     */
    public function create(PollInterface $poll)
    {
        $answerGroupClass = $this->getClass();

        $answerGroup = new $answerGroupClass();
        $answerGroup->setPoll($poll);

        return $answerGroup;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AnswerGroupInterface $answerGroup)
    {
        $this->doSave($answerGroup);

        return $answerGroup;
    }

    /**
     * Connection dependant save.
     *
     * @paramter AnswerGroupInterface $answerGroup
     */
    abstract public function doSave($answerGroup);

    /**
     * Gets class name of answer.
     *
     * @return string
     */
    abstract public function getClass();

    /**
     * {@inheritDoc}
     */
    public function hasAnswered(PollInterface $poll)
    {
        return $this->hasAnsweredAnonymously($poll);
    }

    /**
     * {@inheritDoc}
     */
    public function hasAnsweredAnonymously(PollInterface $poll)
    {
        if ($this->request->cookies->has(sprintf('%sanswered_%s', $this->cookiePrefix, $poll->getId()))) {
            return true;
        }

        return false;
    }
}
