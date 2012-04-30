<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Bait\PollBundle\Model\PollManagerInterface;
use Bait\PollBundle\Model\FieldInterface;

/**
 * Poll form type. It dynamically generates poll by its DB
 * representation.
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
class PollType extends AbstractType
{
    /**
     * @var PollManagerInterface Poll manager
     */
    protected $pollManager;

    /**
     * @var array Translation table of field types
     */
    protected $fieldTypes = array(
        FieldInterface::TYPE_TEXT => 'text',
        FieldInterface::TYPE_RADIO => 'choice',
        FieldInterface::TYPE_CHECKBOX => 'choice',
        FieldInterface::TYPE_SELECT => 'choice',
    );

    /**
     * @param PollManagerInterface $pollManager Poll manager
     */
    public function __construct(PollManagerInterface $pollManager)
    {
        $this->pollManager = $pollManager;
    }

    /**
     * @param mixed Sets id of poll
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $fields = $this->getFields();

        foreach ($fields as $field) {
            if ($field->isStandalone()) {
                $choiceFields = array(
                    FieldInterface::TYPE_RADIO,
                    FieldInterface::TYPE_SELECT,
                    FieldInterface::TYPE_CHECKBOX,
                    FieldInterface::TYPE_SELECT_MULTIPLE,
                );

                $fieldType = $field->getType();

                if (in_array($fieldType, $choiceFields)) {
                    $choices = array();

                    foreach ($field->getChildren() as $choice) {
                        $choices[$choice->getId()] = $choice->getTitle();
                    }

                    $options = array(
                        'label' => $field->getTitle(),
                        'choices' => $choices,
                    );

                    $additionalOptions = array();

                    if ($fieldType === FieldInterface::TYPE_RADIO) {
                        $additionalOptions = array(
                            'expanded' => true,
                            'multiple' => false,
                        );
                    } else if ($fieldType === FieldInterface::TYPE_CHECKBOX) {
                        $additionalOptions = array(
                            'expanded' => true,
                            'multiple' => true,
                        );
                    } else if ($fieldType === FieldInterface::TYPE_SELECT_MULTIPLE) {
                        $additionalOptions = array(
                            'expanded' => false,
                            'multiple' => true,
                        );
                    }

                    $options = array_merge($options, $additionalOptions);

                    $builder->add(
                        sprintf('field_%s', $field->getId()),
                        'choice',
                        $options
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

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'bait_poll_form';
    }

    /**
     * Gets all fields from current poll.
     *
     * @return Doctrine\ORM\PersistentCollection
     */
    protected function getFields()
    {
        $poll = $this->pollManager->findOneById($this->id);

        return $poll->getFields();
    }
}
