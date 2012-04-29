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

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Form factory responsible for creating poll forms.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class PollFormFactory implements PollFormFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string Name of form type service
     */
    protected $type;

    /**
     * @var string Name of form
     */
    protected $name;

    /**
     * @param FormFactoryInterface $formFactory
     * @param string $type Name of poll form type service
     * @param string $name Name of form
     */
    public function __construct(FormFactoryInterface $formFactory, $type, $name)
    {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function create($id)
    {
        $type = $this->formFactory->getType($this->type);
        $type->setId($id);

        $builder = $this->formFactory->createNamedBuilder($type, sprintf('%s_%s', $this->name, $id));

        return $builder->getForm();
    }
}
