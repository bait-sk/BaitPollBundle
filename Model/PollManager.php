<?php

namespace Bait\PollBundle\Model;

/**
 * Connection agnostic poll manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class PollManager implements PollManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function findOneById($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    /**
     * Finds multiple polls by their ids.
     *
     * @param array $ids Ids of polls
     *
     * @return array
     */
    public function findByIds($ids)
    {
        return $this->findBy($ids);
    }

    /**
     * Finds poll by given criteria.
     *
     * @param array $criteria Criteria by which function filters polls
     *
     * @return PollInterface
     */
    abstract public function findOneBy(array $criteria);

    /**
     * Finds polls by given criteria.
     *
     * @param array $criteria Criteria by which function filters polls
     *
     * @return array
     */
    abstract public function findBy(array $criteria);
}
