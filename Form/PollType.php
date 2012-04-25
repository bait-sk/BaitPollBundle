<?php

namespace Bait\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Bait\PollBundle\Model\PollManagerInterface;
use Bait\PollBundle\Model\Field;

class PollType extends AbstractType
{
    protected $entityManager;

    protected $fieldTypes = array(
        Field::TYPE_TEXT => 'text',
        Field::TYPE_RADIO => 'choice',
    );

    public function __construct(PollManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $fields = $this->getFields();

        foreach ($fields as $field) {
            if ($field->isStandalone()) {
                if (in_array($field->getType(), array('TYPE_RADIO'))) {
                    $choices = array();

                    foreach ($field->getChildren() as $choice) {
                        $choices[$choice->getId()] = $choice->getTitle();
                    }

                    $builder->add(
                        sprintf('field_%s', $field->getId()),
                        'choice',
                        array(
                            'label' => $field->getTitle(),
                            'choices' => $choices,
                        )
                    );
                } else {
                    $builder->add(
                        sprintf('field_%s', $field->getId()),
                        $this->fieldTypes[$field->getType()],
                        array(
                            'label' => $field->getTitle(),
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
