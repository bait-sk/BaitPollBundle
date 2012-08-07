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
    protected $answersVisible;

    /**
     * @var mixed
     */
    protected $fields;

    /**
     * @var string
     */
    protected $type;

    public function __construct()
    {
        $this->setActive(true);
        $this->setAnswersVisible(true);
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
     * @return PollInterface
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
     * {@inheritdoc}
     */
    public function getAuthorName()
    {
        return 'anonymous';
    }

    /**
     * Sets date of creation of poll.
     *
     * @param \DateTime $datetime
     *
     * @return PollInterface
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets date of creation of poll.
     *
     * @return \DateTime
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
     * @return PollInterface
     */
    public function setStartAt(\DateTime $startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * {@inheritDoc}
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
     * @return PollInterface
     */
    public function setEndAt(\DateTime $endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * {@inheritDoc}
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
     * @return PollInterface
     */
    public function setActive($isActive)
    {
        $this->isActive = (boolean) $isActive;

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
     * Sets visibility of answers after user answered.
     *
     * @param boolean $answersVisible
     *
     * @return PollInterface
     */
    public function setAnswersVisible($answersVisible)
    {
        $this->answersVisible = (boolean) $answersVisible;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isAnswersVisible()
    {
        return $this->answersVisible;
    }

    /**
     * Sets type of poll.
     *
     * @return PollInterface
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
}
