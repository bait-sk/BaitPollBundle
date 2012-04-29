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
 * Connection agnostic vote manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class VoteManager implements VoteManagerInterface
{
    /**
     * @param FieldInterface $field Poll field
     *
     * @return VoteInterface
     */
    public function findByField(FieldInterface $field)
    {
        return $this->findBy(array('field' => $field));
    }

    /**
     * @param PollInterface $poll Poll
     *
     * @return array
     */
    public function findByPoll(PollInterface $poll)
    {
        return $this->findBy(array('poll' => $poll));
    }

    /**
     * {@inheritDoc}
     */
    public function create(FieldInterface $field, $value)
    {
        $voteClass = $this->getClass();

        $vote = new $voteClass();
        $vote->setField($field);
        $vote->setValue($value);

        return $vote;
    }

    /**
     * {@inheritDoc}
     */
    public function save($votes)
    {
        $votes = (array) $votes;

        $this->doSave($votes);
    }

    /**
     * Finds votes by given criteria.
     *
     * @param array $criteria Criteria by which function filters votes
     */
    abstract public function findBy($criteria);

    /**
     * Connection dependant save.
     *
     * @paramter array $votes Array of votes
     */
    abstract public function doSave(array $votes);

    /**
     * Gets class name of vote.
     *
     * @return string
     */
    abstract public function getClass();
}
