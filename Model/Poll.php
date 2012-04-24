<?php

namespace Bait\PollBundle\Model;

/**
 * Base Poll model
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class Poll implements PollInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $startAt;

    /**
     * @var \DateTime
     */
    protected $endAt;

    /**
     * @var boolean
     */
    protected $isActive;

    /**
     * @var boolean
     */
    protected $votesVisible;

    public function __construct()
    {
        $this->setActive(true);
        $this->setVotesVisible(true);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets title of poll.
     *
     * @param string $title
     *
     * @return Poll
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets title of poll.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return 'anonymous';
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets start date of poll.
     *
     * @param \DateTime $startAt
     *
     * @return Poll
     */
    public function setStartAt(\DateTime $startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Gets start date of poll.
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Sets end date of poll.
     *
     * @param \DateTime $endAt
     *
     * @return Poll
     */
    public function setEndAt(\DateTime $endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Gets end date of poll.
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Sets visibility of poll.
     *
     * @param boolean $isActive
     *
     * @return Poll
     */
    public function setActive($isActive)
    {
        $this->isActive = (boolean) $isActive;

        return $this;
    }

    /**
     * Gets visbility of poll.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * Sets visibility of votes after user voted.
     *
     * @param boolean $votesVisible
     *
     * @return Poll
     */
    public function setVotesVisible($votesVisible)
    {
        $this->votesVisible = (boolean) $votesVisible;

        return $this;
    }

    /**
     * Gets visibility of votes after user voted.
     *
     * @return boolean
     */
    public function isVotesVisible()
    {
        return $this->votesVisible;
    }
}
