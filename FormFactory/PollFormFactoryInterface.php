<?php

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
