<?php

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bait\PollBundle\Model\Poll as PollModel;

/**
 * Base Poll ORM entity
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class Poll extends PollModel
{
    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="start_at", type="datetime")
     */
    protected $startAt;

    /**
     * @ORM\Column(name="end_at", type="datetime", nullable=true)
     */
    protected $endAt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="votes_visible", type="boolean")
     */
    protected $votesVisible;
}
