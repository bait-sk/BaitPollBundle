<?php

namespace Bait\PollBundle\Model;

interface FieldInterface
{
    const TYPE_TEXT = 'TYPE_TEXT';
    const TYPE_INTEGER = 'TYPE_INTEGER';
    const TYPE_TEXTAREA = 'TYPE_TEXTAREA';
    const TYPE_FILE = 'TYPE_FILE';

    const TYPE_RADIO = 'TYPE_RADIO';
    const TYPE_CHECKBOX = 'TYPE_CHECKBOX';
    const TYPE_DROPDOWN = 'TYPE_DROPDOWN';
    const TYPE_DROPDOWN_MULTIPLE = 'TYPE_DROPDOWN_MULTIPLE';
}
