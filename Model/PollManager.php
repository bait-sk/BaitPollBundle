<?php

namespace Bait\PollBundle\Model;

/**
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class PollManager
{
    /**
     * @param string $id
     *
     * @return PollInterface
     */
    public function findPollById($id)
    {
        return $this->findPollBy(array('id' => $id));
    }
}
