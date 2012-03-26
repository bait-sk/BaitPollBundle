<?php

namespace Bait\PollBundle\Model;

/**
 * Base Poll object
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class Poll
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $createdBy;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $modifiedBy;

    /**
     * @var \DateTime
     */
    protected $modifiedAt;

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
    protected $isVisible;

    /**
     * @var boolean
     */
    protected $votesVisible;

    public function __construct()
    {
        $this->setVisible(true);
        $this->setVotesVisible(true);
        $this->createdAt = new \DateTime();
    }

    /**
     * Returns unique id of poll.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets title of poll.
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
     * Sets owner of poll.
     *
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Gets owner of poll.
     *
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Gets date of creation of poll.
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets who modified the poll.
     *
     * @param mixed $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Gets who modified the poll.
     *
     * @return mixed
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Sets date of modification of poll.
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt(\DateTime $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Gets date of modification of poll.
     *
     * @return datetime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Sets start date of poll.
     *
     * @param datetime $startAt
     */
    public function setStartAt(\DateTime $startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Gets start date of poll.
     *
     * @return datetime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Sets end date of poll.
     *
     * @param datetime $endAt
     */
    public function setEndAt(\DateTime $endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Gets end date of poll.
     *
     * @return datetime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Sets visibility of poll.
     *
     * @param boolean $isVisible
     */
    public function setVisible($isVisible)
    {
        $this->isVisible = (boolean) $isVisible;

        return $this;
    }

    /**
     * Gets visbility of poll.
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->isVisible;
    }

    /**
     * Sets visibility of votes after user voted.
     *
     * @param boolean $votesVisible
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
