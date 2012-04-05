<?php

namespace Bait\PollBundle\Model;

/**
 * Interface used by polls in this bundle.
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
    function getId();

    /**
     * Gets owner of poll.
     *
     * @return string
     */
    function getCreatedBy();

    /**
     * Gets date of creation of poll.
     *
     * @return \DateTime
     */
    function getCreatedAt();

    /**
     * Sets date of creation of poll.
     *
     * @param \DateTime $datetime
     */
    function setCreatedAt(\DateTime $datetime);
}
