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
 * Interface defining shape of answer groups in this bundle.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
interface AnswerGroupInterface
{
    /**
     * Gets poll this answer belongs to.
     *
     * @return PollInterface
     */
    public function getPoll();


    /**
     * Sets client's IP address
     *
     * @param $ip
     *
     * @return mixed
     */
    public function setClientIp($ip);
}
