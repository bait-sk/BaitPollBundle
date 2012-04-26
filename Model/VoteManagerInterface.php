<?php

namespace Bait\PollBundle\Model;

/**
 * Interface defining shape of vote managers in this bundle.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface VoteManagerInterface
{
    /**
     * Creates vote instance.
     *
     * @param FieldInterface $field Poll field
     * @param string $value Vote value
     *
     * @return VoteInterface
     */
    public function create(FieldInterface $field, $value);

    /**
     * Saves vote to DB.
     *
     * @param mixed $votes Votes to save (VoteInterface or array of them)
     */
    public function save($votes);
}
