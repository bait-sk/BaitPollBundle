<?php

namespace Bait\PollBundle\Model;

/**
 * Base PollQuestion object
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class PollField
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
     * @var string
     */
    protected $createdBy;

    /**
     * @var datetime
     */
    protected $createdAt;

    /**
     * @var datetime
     */
    protected $modifiedBy;

    /**
     * @var datetime
     */
    protected $modifiedAt;

    /**
     * @var boolean
     */
    protected $isActive;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Returns unique id of poll question.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets title of poll question.
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets title of poll question.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets owner of poll question.
     *
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Gets owner of poll question.
     *
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Gets date of creation of poll question.
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets who modified the poll question.
     *
     * @param mixed $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Gets who modified the poll question.
     *
     * @return mixed
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Sets date of modification of poll question.
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt(\DateTime $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Gets date of modification of poll question.
     *
     * @return datetime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Sets whether poll question is active or not.
     *
     * @param boolean $isActive
     */
    public function setActive($isActive)
    {
        $this->isActive = (boolean) $isActive;

        return $this;
    }

    /**
     * Gets whether poll question is active or not.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }
}
