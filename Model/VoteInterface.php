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
 * Interface defining shape of votes in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface VoteInterface
{
    /**
     * Gets field this vote belongs to.
     *
     * @return FieldInterface
     */
    public function getField();

    /**
     * Sets answer of vote.
     *
     * @param string $answer Value of vote
     *
     * @return VoteInterface
     */
    public function setAnswer($answer);


    /**
     * Sets client's IP address
     *
     * @param $ip
     *
     * @return mixed
     */
    public function setClientIp($ip);
}
