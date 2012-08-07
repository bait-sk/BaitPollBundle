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

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Inferface to be used in conjunction with FOSUserBundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface SignedAnswerGroupManagerInterface extends AnswerGroupManagerInterface
{
    /**
     * Finds out if user has already answered, depending on
     * type of poll.
     *
     * @return boolean
     */
    public function hasUserAnswered(PollInterface $poll);
}
