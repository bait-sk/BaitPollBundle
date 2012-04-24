<?php

namespace Bait\PollBundle\Model;

/**
* @author Ondrej Slintak <ondrowan@gmail.com>
*/
abstract class VoteManager implements VoteManagerInterface
{
    /**
     * @param FieldInterface $field
     *
     * @return VoteInterface
     */
    public function findByField(FieldInterface $field)
    {
        return $this->findBy(array('field' => $field));
    }

    /**
     * @param Poll $poll
     *
     * @return array
     */
    public function findByPoll(Poll $poll)
    {
        return $this->findBy(array('poll' => $poll));
    }

    public function create(PollField $field, $value)
    {
        $voteClass = $this->getClass();

        $vote = new $voteClass();
        $vote->setField($field);
        $vote->setValue($value);

        return $vote;
    }

    public function save($votes)
    {
        $votes = (array) $votes;

        $this->doSave($votes);
    }

    abstract public function findBy($criteria);

    abstract public function doSave($votes);

    abstract public function getClass();
}
