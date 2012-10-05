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

use Doctrine\ORM\PersistentCollection;

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
     * Counts all answers for given fields. Works only for checkboxes, radio buttons
     * and multiselects.
     *
     * @param PersistentCollection $fields Fields to check answers for
     *
     * @return array Array containing number of votes as field_id => votes
     */
    public function countVotesOf(PersistentCollection $fields);
}
