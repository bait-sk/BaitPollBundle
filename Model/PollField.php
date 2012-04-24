<?php

namespace Bait\PollBundle\Model;

/**
 * Base PollField model
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class PollField
{
    const TYPE_TEXT = 'TYPE_TEXT';
    const TYPE_INTEGER = 'TYPE_INTEGER';
    const TYPE_TEXTAREA = 'TYPE_TEXTAREA';
    const TYPE_FILE = 'TYPE_FILE';

    const TYPE_RADIO = 'TYPE_RADIO';
    const TYPE_CHECKBOX = 'TYPE_CHECKBOX';
    const TYPE_DROPDOWN = 'TYPE_DROPDOWN';
    const TYPE_DROPDOWN_MULTIPLE = 'TYPE_DROPDOWN_MULTIPLE';

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $children;

    /**
     * @var PollField
     */
    protected $parent;

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

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var array
     */
    protected $validationConstraints = array();

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
     * Sets all children of poll field.
     *
     * @param mixed $children
     *
     * @return PollField
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Gets all children of poll field.
     *
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets parent of poll field.
     *
     * @param PollField $parent
     *
     * @return PollField
     */
    public function setParent($parent)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets parent of poll field.
     *
     * @return PollField
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Checks if field has children. This helps to
     * determine if current field is standalone.
     *
     * @return bool
     */
    public function isStandalone()
    {
        return !$this->getParent();
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
     *
     * @return string
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

    /**
     * Sets if field is be required.
     *
     * @param bool $required
     *
     * @return PollField
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Gets if field is required.
     *
     * @return string
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Adds validation constraint for the field.
     *
     * @param string $validationConstraint
     *
     * @return PollField
     */
    public function addValidationConstraint($validationConstraint)
    {
        if (!in_array($validationConstraint, $this->validationConstraints, true)) {
            $this->validationConstraints[] = $validationConstraint;
        }

        return $this;
    }

    /**
     * Gets validation constraints of field.
     *
     * @return array
     */
    public function getValidationConstraints()
    {
        return $this->validationConstraints;
    }
}
