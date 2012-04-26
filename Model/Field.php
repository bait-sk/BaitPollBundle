<?php

namespace Bait\PollBundle\Model;

/**
 * Base Field model
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class Field implements FieldInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $children;

    /**
     * @var FieldInterface
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
     * {@inheritDoc}
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
     * @return FieldInterface
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets parent of poll field.
     *
     * @param FieldInterface $parent
     *
     * @return FieldInterface
     */
    public function setParent(FieldInterface $parent)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets parent of poll field.
     *
     * @return FieldInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
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
     * @return FieldInterface
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
     * @return FieldInterface
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
     * @return FieldInterface
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
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
     * @return FieldInterface
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
     * @return FieldInterface
     */
    public function addValidationConstraint($validationConstraint)
    {
        if (!in_array($validationConstraint, $this->validationConstraints, true)) {
            $this->validationConstraints[] = $validationConstraint;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationConstraints()
    {
        return $this->validationConstraints;
    }
}
