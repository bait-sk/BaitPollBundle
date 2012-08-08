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
 * Base Answer model.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class Answer implements AnswerInterface
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
     * @var AnswerGroupInterface
     */
    protected $answerGroup;

    /**
     * Gets id of answer.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets field this answer belongs to.
     *
     * @param FieldInterface Poll field
     *
     * @return AnswerInterface
     */
    public function setField(FieldInterface $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getAnswerGroup()
    {
        return $this->answerGroup;
    }

    /**
     * {@inheritDoc}
     */
    public function setAnswerGroup(AnswerGroupInterface $answerGroup) {
        $this->answerGroup = $answerGroup;
    }
}
