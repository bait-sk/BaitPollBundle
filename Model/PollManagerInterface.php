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
