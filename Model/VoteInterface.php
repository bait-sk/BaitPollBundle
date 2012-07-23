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
 * Interface defining shape of votes in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface VoteInterface
{
    /**
     * Gets field this vote belongs to.
     *
     * @return FieldInterface
     */
    public function getField();

    /**
     * Gets vote group this vote belongs to.
     *
     * @return VoteGroupInterface
     */
    public function getVoteGroup();

    /**
     * Sets group this vote belongs to
     *
     * @param VoteGroupInterface $voteGroup
     */
    public function setVoteGroup(VoteGroupInterface $voteGroup);

    /**
     * Sets answer of vote.
     *
     * @param string $answer Value of vote
     *
     * @return VoteInterface
     */
    public function setAnswer($answer);

}
