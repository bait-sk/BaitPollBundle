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
    const TYPE_INPUT = 'TYPE_INPUT';
    const TYPE_INTEGER = 'TYPE_INTEGER';
    const TYPE_EMAIL = 'TYPE_EMAIL';
    const TYPE_TEXTAREA = 'TYPE_TEXTAREA';
    const TYPE_FILE = 'TYPE_FILE';
    const TYPE_DATE = 'TYPE_DATE';
    const TYPE_DATETIME = 'TYPE_DATETIME';
    const TYPE_TIME = 'TYPE_TIME';

    const TYPE_ASSET_AUDIO = 'TYPE_ASSET_AUDIO';
    const TYPE_ASSET_IMAGE = 'TYPE_ASSET_IMAGE';
    const TYPE_ASSET_TEXT = 'TYPE_ASSET_TEXT';
    const TYPE_ASSET_URL = 'TYPE_ASSET_URL';
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
     * Gets description of poll field.
     *
     * @return string
     */
    public function getDescription();

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
     * Checks if the field is active
     *
     * @return bool
     */
    public function isActive();

    /**
     * Checks if field is renderable. That means field has to be active
     * and field cannot be deleted.
     *
     * @return bool
     */
    public function isRenderable();
}
