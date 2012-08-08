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
use Bait\PollBundle\Model\FieldManager;
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
     * @var FieldManager $fieldManager
     */
    protected $fieldManager;

    /**
     * @var PollManagerInterface Poll manager
     */
    protected $pollManager;

    protected $id;

    /**
     * @var array Translation table of field types
     */
    protected $fieldTypes = array(
        FieldInterface::TYPE_INPUT => 'text',
        FieldInterface::TYPE_EMAIL => 'email',
        FieldInterface::TYPE_URL => 'url',
        FieldInterface::TYPE_INTEGER => 'integer',
        FieldInterface::TYPE_RADIO => 'choice',
        FieldInterface::TYPE_CHECKBOX => 'choice',
        FieldInterface::TYPE_SELECT => 'choice',
        FieldInterface::TYPE_FILE => 'file',
    );

    /**
     * @param FieldManager $fieldManager Field manager
     */
    public function __construct(PollManagerInterface $pollManager, FieldManager $fieldManager)
    {
        $this->pollManager = $pollManager;
        $this->fieldManager = $fieldManager;
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
                    $assetChoiceFields = array(
                        FieldInterface::TYPE_ASSET_AUDIO,
                        FieldInterface::TYPE_ASSET_IMAGE,
                        FieldInterface::TYPE_ASSET_VIDEO,
                    );

                    $choices = array();

                    foreach ($field->getChildren() as $choice) {
                        $choiceFieldType = $choice->getType();

                        if (in_array($choiceFieldType, $assetChoiceFields)) {
                            $choices[$choice->getId()] = array(
                                'id' => $choice->getId(),
                                'title' => $choice->getTitle(),
                                'asset_type' => $choiceFieldType,
                                'asset_url' => $choice->getAssetPath(),
                            );
                        }
                        else {
                            $choices[$choice->getId()] = $choice->getTitle();
                        }
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
            $constraints[sprintf('field_%s', $field->getId())] = $this->loadValidationConstraints($field);
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
     * @return \Doctrine\ORM\PersistentCollection
     */
    protected function getFields()
    {
        $poll = $this->pollManager->findOneById($this->id);

        return $this->fieldManager->findOrderedPollFields($poll);
    }

    /**
     * Loads validation constraints of single field.
     *
     * @param FieldInterface $field Single field
     *
     * @return mixed
     */
    protected function loadValidationConstraints(FieldInterface $field)
    {
        $validationConstraints = $field->getValidationConstraints();

        if ($field->isStandalone() && !empty($validationConstraints)) {
            return $validationConstraints;
        }

        return null;
    }
}
