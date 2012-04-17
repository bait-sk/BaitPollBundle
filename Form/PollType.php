<?php

namespace Bait\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Bait\PollBundle\Model\PollManager;
use Bait\PollBundle\Model\PollField;

class PollType extends AbstractType
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $poll = $this->entityManager->findOneById($this->id);
        $pollFields = $poll->getFields();

        $options = array();

        foreach ($pollFields as $pollField) {
            switch ($pollField->getType()) {
                case PollField::TYPE_RADIO:
                    $options[] = sprintf('field_%s', $pollField->getId());
                    break;
                case PollField::TYPE_TEXT:
                    $builder->add(sprintf('field_%s', $pollField->getId()), 'text');
                    break;
                case PollField::TYPE_TEXTAREA:
                    $builder->add(sprintf('field_%s', $pollField->getId()), 'textarea');
                    break;
                case PollField::TYPE_FILE:
                    $builder->add(sprintf('field_%s', $pollField->getId()), 'file');
                    break;
                default:
                    // throw exception
                    break;
            }
        }

        if ($options) {
            $builder->add('choices', 'choice', array('choices' => $options));
        }
    }

    public function getName()
    {
        return 'bait_poll_form';
    }
}
