<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\Model;

/**
 * Interface defining shape of poll fields throughout
 * this bundle
 *
 * @author Ondrej Slintak <ondrowan@gmail.com>
 */
interface FieldInterface
{
    const TYPE_TEXT = 'TYPE_TEXT';
    const TYPE_INPUT = 'TYPE_INPUT';
    const TYPE_INTEGER = 'TYPE_INTEGER';
    const TYPE_EMAIL = 'TYPE_EMAIL';
    const TYPE_URL = 'TYPE_URL';
    const TYPE_TEXTAREA = 'TYPE_TEXTAREA';
    const TYPE_FILE = 'TYPE_FILE';

    const TYPE_ASSET_IMAGE = 'TYPE_ASSET_IMAGE';
    const TYPE_ASSET_AUDIO = 'TYPE_ASSET_AUDIO';
    const TYPE_ASSET_VIDEO = 'TYPE_ASSET_VIDEO';

    const TYPE_RADIO = 'TYPE_RADIO';
    const TYPE_CHECKBOX = 'TYPE_CHECKBOX';
    const TYPE_SELECT = 'TYPE_SELECT';
    const TYPE_SELECT_MULTIPLE = 'TYPE_SELECT_MULTIPLE';

    /**
     * Returns unique id of poll field.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Checks if field has children. This helps to
     * determine if current field is standalone.
     *
     * @return bool
     */
    public function isStandalone();

    /**
     * Gets asset path of poll field.
     *
     * @return string
     */
    public function getAssetPath();

    /**
     * Gets all ordered children of poll field.
     *
     * @return mixed
     */
    public function getChildren();

    /**
     * Gets title of poll field.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Gets type of poll field.
     *
     * @return string
     */
    public function getType();

    /**
     * Gets validation constraints of field.
     *
     * @return array
     */
    public function getValidationConstraints();

    /**
     * Gets position of field in poll.
     *
     * @return int
     */
    public function getPosition();

    /**
     * Sets position of field in poll.
     *
     * @param int $position
     *
     * @return FieldInterface
     */
    public function setPosition($position);

    /**
     * Compares position of the field and outputs
     * usort() friendly value
     *
     * @return integer
     */
    public function compareFieldPositions(FieldInterface $field1, FieldInterface $field2);

    /**
     * Checks if the field is active
     *
     * @return bool
     */
    public function isActive();
}
