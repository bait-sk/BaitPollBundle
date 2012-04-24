<?php

namespace Bait\PollBundle\Model;

/**
 * Base Vote model
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class Vote implements VoteInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setField(PollField $field)
    {
        $this->field = $field;

        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getAuthorName()
    {
        return "anonymous";
    }
}
