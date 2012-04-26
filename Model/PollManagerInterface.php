<?php

namespace Bait\PollBundle\Model;

/**
 * Interface defining shape of poll manager in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface PollManagerInterface
{
    /**
     * Finds one poll by its id.
     *
     * @param mixed $id Id of poll
     *
     * @return PollInterface
     */
    public function findOneById($id);
}
