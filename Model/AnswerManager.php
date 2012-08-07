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
 * Connection agnostic answer manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class AnswerManager implements AnswerManagerInterface
{
    /**
     * @param FieldInterface $field Poll field
     *
     * @return AnswerInterface
     */
    public function findByField(FieldInterface $field)
    {
        return $this->findBy(array('field' => $field));
    }

    /**
     * {@inheritDoc}
     */
    public function create(FieldInterface $field, $answer, AnswerGroupInterface $answerGroup)
    {
        $answerClass = $this->getClass();

        $answer = new $answerClass();
        $answer->setField($field);
        $answer->setAnswer($answer);
        $answer->setAnswerGroup($answerGroup);

        return $answer;
    }

    /**
     * {@inheritDoc}
     */
    public function save($answers)
    {
        $answers = (array) $answers;

        $this->doSave($answers);
    }

    /**
     * {@inheritDoc}
     */
    public function countByField(FieldInterface $field)
    {
        return $this->doCountByField($field);
    }

    /**
     * Finds answers by given criteria.
     *
     * @param array $criteria Criteria by which function filters answers
     */
    abstract public function findBy($criteria);

    /**
     * Connection dependant save.
     *
     * @paramter array $answers Array of answers
     */
    abstract public function doSave(array $answers);

    /**
     * Connection dependant count by field.
     *
     * @parameter FieldInterface $field Field to check answers for
     *
     * @return integer
     */
    abstract public function doCountByField(FieldInterface $field);

    /**
     * Gets class name of answer.
     *
     * @return string
     */
    abstract public function getClass();
}
