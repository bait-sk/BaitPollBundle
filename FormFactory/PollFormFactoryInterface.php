<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\FormFactory;

/**
 * Form factories for poll forms must implement this interface.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface PollFormFactoryInterface
{
    /**
     * Creates poll form.
     *
     * @argument int $id Id of poll to create
     *
     * @return Symfony\Component\Form\Form
     */
    public function create($id);
}
