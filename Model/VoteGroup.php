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
 * Base VoteGroup model.
 *
 * @author Matej Zilak <teo@teo.sk>
 */
abstract class VoteGroup implements VoteGroupInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var PollInterface
     */
    protected $poll;

    /**
     * @var string
     */
    protected $clientIp;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Gets id of vote group.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets poll this vote group belongs to.
     *
     * @param PollInterface Poll
     *
     * @return VoteInterface
     */
    public function setPoll(PollInterface $poll)
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param string $clientIp
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Sets time this vote was created at.
     *
     * @param \DateTime $createdAt Time of creation
     *
     * @return VoteInterface
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets time this vote was created at.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets name of author.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return "anonymous";
    }

}
