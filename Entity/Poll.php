<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

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

    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function  setDeletedAt(\DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }


}
