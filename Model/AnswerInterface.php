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
 * Interface defining shape of answers in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface AnswerInterface
{
    /**
     * Gets field this answer belongs to.
     *
     * @return FieldInterface
     */
    public function getField();

    /**
     * Gets answer group this answer belongs to.
     *
     * @return AnswerGroupInterface
     */
    public function getAnswerGroup();

    /**
     * Sets group this answer belongs to
     *
     * @param AnswerGroupInterface $answerGroup
     */
    public function setAnswerGroup(AnswerGroupInterface $answerGroup);

    /**
     * Sets value of answer.
     *
     * @param string $value Value of answer
     *
     * @return AnswerInterface
     */
    public function setValue($value);

    /**
     * Gets value of answer.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
