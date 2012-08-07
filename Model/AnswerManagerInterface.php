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
 * Interface defining shape of answer managers in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface AnswerManagerInterface
{
    /**
     * Creates answer instance.
     *
     * @param FieldInterface $field Poll field
     * @param string $value Answer value
     * @param AnswerGroupInterface $answerGroup
     *
     * @return AnswerInterface
     */
    public function create(FieldInterface $field, $value, AnswerGroupInterface $answerGroup);

    /**
     * Saves answer to DB.
     *
     * @param mixed $answers Answers to save (AnswerInterface or array of them)
     */
    public function save($answers);

    /**
     * Counts all answers for given field.
     *
     * @parem FieldInterface $field Field to check answers for
     *
     * @return interger
     */
    public function countByField(FieldInterface $field);
}
