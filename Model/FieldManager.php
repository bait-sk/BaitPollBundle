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
 * Connection agnostic field manager.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
abstract class FieldManager
{
    /**
     * Returns ordered fields of certain poll.
     *
     * @param $poll Id of poll
     *
     * @return array
     */
    abstract public function findRenderableOrderedPollFields($pollId);

    /**
     * Checks if a poll has upload fields.
     *
     * @param PollInterface $poll
     * @return bool
     */
    public function hasUploadFields(PollInterface $poll)
    {
        $fields = $this->findRenderableOrderedPollFields($poll->getId());
        $hasUploadFields = false;

        foreach ($fields as $field) {
            if ($field->getType() === FieldInterface::TYPE_FILE) {
                $hasUploadFields = true;
                
                break;
            }
        }

        return $hasUploadFields;
    }
}

