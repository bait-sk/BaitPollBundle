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
 * Connection agnostic poll manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class FieldManager
{
    /**
     * Returns ordered fields of certain poll.
     *
     *
     * @param PollInterface $poll Id of poll
     *
     * @return array
     */
    abstract public function findOrderedPollFields(PollInterface $poll);
}

