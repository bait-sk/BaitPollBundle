<?php

namespace Bait\PollBundle\Model;

/**
 * Interface defining shape of polls in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface PollInterface
{
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
     * Gets visibility of votes after user voted.
     *
     * @return boolean
     */
    public function isVotesVisible();
}
