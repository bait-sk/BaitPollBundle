<?php

namespace Bait\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Bait\PollBundle\Model\PollManager;
use Bait\PollBundle\Model\PollField;

class PollType extends AbstractType
{
    protected $entityManager;

    protected $fieldTypes = array(
        PollField::TYPE_TEXT => 'text',
        PollField::TYPE_RADIO => 'choice',
    );

    public function __construct(PollManager $entityManager)
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
        $fields = $this->getFields();
        $constraints = array();

        foreach ($fields as $field) {
            $validationConstraints = $field->getValidationConstraints();

            if ($field->isStandalone() && !empty($validationConstraints)) {
                $constraintCollection = array();

                foreach ($field->getValidationConstraints() as $name => $validationConstraint) {
                    if ($name === 'maxlength') {
                        $constraintCollection[] = new MaxLength($validationConstraint);
                    } else if ($name === 'notblank') {
                        $constraintCollection[] = new NotBlank();
                    }
                }

                $constraints[sprintf('field_%s', $field->getId())] = $constraintCollection;
            }
        }

        $constraints = new Collection(
            array(
                'fields' => $constraints,
                'allowExtraFields' => true,
            )
        );

        return array('validation_constraint' => $constraints, 'fields' => $fields);
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
