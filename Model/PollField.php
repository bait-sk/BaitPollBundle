<?php

namespace Bait\PollBundle\Model;

/**
 * Base PollField object
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class PollField
{
    const TYPE_INPUT = 'TYPE_INPUT';
    const TYPE_TEXTAREA = 'TYPE_TEXTAREA';
    const TYPE_RADIO = 'TYPE_RADIO';
    const TYPE_FILE = 'TYPE_FILE';

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
     * @var string
     */
    protected $type;

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
     *
     * @param string $title
     *
     * @return PollField
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
     * @param \DateTime $createdAt
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
     * Sets type of poll field.
     *
     * @param string $type
     *
     * @return PollField
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets type of poll field.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
