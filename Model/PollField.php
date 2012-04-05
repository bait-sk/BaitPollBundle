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
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var boolean
     */
    protected $isActive;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
     * Sets date of creation of poll question.
     *
     * @return PollField
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets date of creation of poll question.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
