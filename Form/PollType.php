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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
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
     * @var array $fields
     */
    protected $fields;

    /**
     * @var array Translation table of field types
     */
    protected $fieldTypes = array(
        FieldInterface::TYPE_INPUT => 'text',
        FieldInterface::TYPE_TEXTAREA => 'textarea',
        FieldInterface::TYPE_EMAIL => 'email',
        FieldInterface::TYPE_URL => 'url',
        FieldInterface::TYPE_ASSET_URL => 'url',
        FieldInterface::TYPE_INTEGER => 'integer',
        FieldInterface::TYPE_RADIO => 'choice',
        FieldInterface::TYPE_CHECKBOX => 'choice',
        FieldInterface::TYPE_SELECT => 'choice',
        FieldInterface::TYPE_FILE => 'file',
        FieldInterface::TYPE_DATE => 'date',
        FieldInterface::TYPE_DATETIME => 'datetime',
        FieldInterface::TYPE_TIME => 'time',
    );

    /**
     * @param FieldManager $fieldManager Field manager
     */
    public function __construct(FieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
    }

    /**
     * @param mixed $pollId Sets id of poll
     */
    public function setPollFields($pollId)
    {
        $this->fields = $this->fieldManager->findRenderableOrderedPollFields($pollId);
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->fields as $field) {
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
                        FieldInterface::TYPE_ASSET_TEXT,
                        FieldInterface::TYPE_ASSET_URL,
                        FieldInterface::TYPE_ASSET_AUDIO,
                        FieldInterface::TYPE_ASSET_IMAGE,
                        FieldInterface::TYPE_ASSET_VIDEO,
                        FieldInterface::TYPE_ASSET_VIDEO_FLV,
                        FieldInterface::TYPE_ASSET_VIDEO_YOUTUBE,
                    );

                    $choices = array();

                    foreach ($field->getChildren() as $choice) {
                        $choiceFieldType = $choice->getType();

                        if ($fieldType === FieldInterface::TYPE_SELECT
                            ||$fieldType === FieldInterface::TYPE_SELECT_MULTIPLE
                        ) {
                            $choices[$choice->getId()] = $choice->getTitle();
                        } else {
                            if (in_array($choiceFieldType, $assetChoiceFields)) {
                                $choices[$choice->getId()] = array(
                                    'id' => $choice->getId(),
                                    'title' => $choice->getTitle(),
                                    'asset_type' => $choiceFieldType,
                                    'asset_url' => $choice->getAssetPath(),
                                    'description' => $choice->getDescription()
                                );
                            }
                            else {
                                $choices[$choice->getId()] = array(
                                    'label' => $choice->getTitle(),
                                    'description' => $choice->getDescription()
                                );
                            }
                        }
                    }

                    $options = array(
                        'label' => array(
                            'label' => $field->getTitle(),
                            'description' => $field->getDescription()
                        ),
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
                    $additionalOptions = array();
                    $options = array(
                        'label' => array(
                            'label' => $field->getTitle(),
                            'description' => $field->getDescription()
                        ),
                        'required' => $field->isRequired()
                    );

                    if ($field->getType() == FieldInterface::TYPE_DATE
                        || $field->getType() == FieldInterface::TYPE_DATETIME) {
                        $additionalOptions['years'] = $field->getYearRange();
                    }

                    $finalOptions = array_merge($options, $additionalOptions);
                    $builder->add(
                        sprintf('field_%s', $field->getId()),
                        $this->fieldTypes[$field->getType()],
                        $finalOptions
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
        $constraints = $this->loadValidationConstraints($this->fields);

        $constraints = new Collection(
            array(
                'fields' => $constraints,
                'allowExtraFields' => true,
                'allowMissingFields' => true,
            )
        );

        return array('constraints' => $constraints, 'fields' => $this->fields);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'bait_poll_form';
    }

    /**
     * Loads validation constraints of all provided fields.
     *
     * @param array $fields Fields to load validation constraints from
     *
     * @return array
     */
    protected function loadValidationConstraints(array $fields)
    {
        $validationConstraints = array();

        foreach ($fields as $field) {
            $fieldValidationConstraints = $field->getValidationConstraints();

            if ($fieldValidationConstraints) {
                $validationConstraints[sprintf('field_%s', $field->getId())] = $field->getValidationConstraints();
            }

            if ($field->isRequired()) {
                $validationConstraints[sprintf('field_%s', $field->getId())][] = new NotBlank();
            }

            //needed to clean-up the array keys to be numerical only - issue in symfony2.1
            sort($validationConstraints[sprintf('field_%s', $field->getId())]);
        }

        return $validationConstraints;
    }
}
