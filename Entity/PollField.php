<?php

namespace Bait\PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bait\PollBundle\Model\PollField as PollFieldModel;

/**
 * Base PollField ORM entity
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class PollField extends PollFieldModel
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
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;
}
