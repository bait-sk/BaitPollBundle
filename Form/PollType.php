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
        $pollFields = $this->getFields();

        foreach ($pollFields as $pollField) {
            if ($pollField->isStandalone()) {
                if (in_array($pollField->getType(), array('TYPE_RADIO'))) {
                    $choices = array();

                    foreach ($pollField->getChildren() as $choice) {
                        $choices[$choice->getId()] = $choice->getTitle();
                    }

                    $builder->add(
                        sprintf('option_%s', $pollField->getId()),
                        'choice',
                        array(
                            'label' => $pollField->getTitle(),
                            'choices' => $choices,
                        )
                    );
                } else {
                    $builder->add(
                        sprintf('field_%s', $pollField->getId()),
                        $this->fieldTypes[$pollField->getType()],
                        array(
                            'label' => $pollField->getTitle(),
                        )
                    );
                }
            }
        }
    }

    public function getDefaultOptions(array $options)
    {
        $constraints = new Collection(
            array(
                'fields' => array(

                ),
                'allowExtraFields' => true,
            )
        );

        return array('validation_constraint' => $constraints);
    }

    public function getName()
    {
        return 'bait_poll_form';
    }

    protected function getFields()
    {
        $poll = $this->entityManager->findOneById($this->id);

        return $poll->getFields();
    }
}
