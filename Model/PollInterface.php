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
 * Interface defining shape of polls in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface PollInterface
{
    const POLL_TYPE_USER = 'POLL_TYPE_USER';
    const POLL_TYPE_ANONYMOUS = 'POLL_TYPE_ANONYMOUS';
    const POLL_TYPE_MIXED = 'POLL_TYPE_MIXED';

    /**
     * Returns unique poll ID.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Gets title of poll.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets start date of poll.
     *
     * @return \DateTime
     */
    public function getStartAt();

    /**
     * Gets end date of poll.
     *
     * @return \DateTime
     */
    public function getEndAt();

    /**
     * Gets visbility of poll.
     *
     * @return boolean
     */
    public function isActive();

    /**
     * Gets visibility of answers after user answered.
     *
     * @return boolean
     */
    public function isAnswersVisible();

    /**
     * Gets type of poll.
     *
     * @return string
     */
    public function getType();
}
