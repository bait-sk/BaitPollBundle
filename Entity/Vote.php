<?php

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bait\PollBundle\Model\Vote as VoteModel;

/**
 * Base Vote ORM entity
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class Vote extends VoteModel
{
    /**
     * @ORM\Column(name="value", type="text")
     */
    protected $value;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
}
