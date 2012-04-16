<?php

namespace Bait\PollBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

class PollFormFactory
{
    protected $formFactory;

    protected $type;

    protected $name;


    public function __construct($formFactory, $type, $name)
    {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->name = $name;
    }

    public function createForm($id)
    {
        $type = $this->formFactory->getType($this->type);
        $type->setId($id);

        $builder = $this->formFactory->createNamedBuilder($type, sprintf('%s_%s', $this->name, $id));

        return $builder->getForm();
    }
}
