<?php

namespace Bait\PollBundle\Model;

/**
 * Base PollField object
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
     * Returns unique id of poll field.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets title of poll field.
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets title of poll field.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets date of creation of poll field.
     *
     * @return PollField
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets date of creation of poll field.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets whether poll field is active or not.
     *
     * @param boolean $isActive
     */
    public function setActive($isActive)
    {
        $this->isActive = (boolean) $isActive;

        return $this;
    }

    /**
     * Gets whether poll field is active or not.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }
}
