<?php

namespace Bait\PollBundle\Model;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class PollManager implements PollManagerInterface
{
    /**
     * @param string $id
     *
     * @return PollInterface
     */
    public function findOneById($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    public function findByIds($ids)
    {
        return $this->findBy($ids);
    }

    abstract public function findOneBy($criteria);

    abstract public function findBy($criteria);

    abstract public function findAll();
}
