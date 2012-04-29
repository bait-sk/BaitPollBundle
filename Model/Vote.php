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
 * Base Vote model.
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

    /**
     * Gets id of vote.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets field this vote belongs to.
     *
     * @param FieldInterface Poll field
     *
     * @return VoteInterface
     */
    public function setField(FieldInterface $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Gets field this vote belongs to.
     *
     * @return FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets value of vote.
     *
     * @param string $value Value of vote
     *
     * @return VoteInterface
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets value of vote.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets time this vote was created at.
     *
     * @param \DateTime $createdAt Time of creation
     *
     * @return VoteInterface
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets time this vote was created at.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets name of author.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return "anonymous";
    }
}
