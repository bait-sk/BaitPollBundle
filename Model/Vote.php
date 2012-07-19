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
    protected $answer;

    /**
     * @var VoteGroupInterface
     */
    protected $votegroup;


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
     * {@inheritDoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritDoc}
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Gets answer of vote.
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * {@inheritDoc}
     */
    public function getVoteGroup()
    {
        return $this->votegroup;
    }

    /**
     * {@inheritDoc}
     */
    public function setVoteGroup(VoteGroupInterface $voteGroup) {
        $this->votegroup = $voteGroup;
    }


}
