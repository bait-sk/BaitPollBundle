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

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;

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
    protected $children = array();

    /**
     * @var FieldInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $assetPath;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @var array
     */
    protected $validationConstraints = array();

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->children = new ArrayCollection();
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
    public function addChild(FieldInterface $child)
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }

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
     * {@inheritDoc}
     */
    public function hasChildren()
    {
        return !$this->children->isEmpty();
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
        $this->parent = $parent;

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
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets description of poll field.
     *
     * @param string $description
     *
     * @return FieldInterface
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets asset path of poll field.
     *
     * @param string $assetPath
     *
     * @return FieldInterface
     */
    public function setAssetPath($assetPath)
    {
        $this->assetPath = $assetPath;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAssetPath()
    {
        return $this->assetPath;
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
     * Sets when the field was deleted at.
     *
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
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
     * {@inheritDoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Adds validation constraint for the field.
     *
     * @param string $validationConstraint
     *
     * @return FieldInterface
     */
    public function addValidationConstraint(Constraint $validationConstraint)
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
        if ($this->isStandalone()) {
            return $this->validationConstraints;
        }

        return null;
    }

    /**
     * Sets if the field is active.
     *
     * @return FieldInterface
     */
    public function setActive($activity)
    {
        $this->isActive = $activity;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * {@inheritDoc}
     */
    public function isRenderable()
    {
        return $this->isActive() && $this->getDeletedAt() === null;
    }

    /**
     * {@inheritDoc}
     */
    public function getYearRange()
    {
        $begin = strtotime("-5 years", time());
        $end = strtotime("+5 years", time());

        return range(date("Y", $begin), date("Y", $end));

    }


}
