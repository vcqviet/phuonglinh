<?php

namespace HK\CoreBundle\Helper;

use Symfony\Component\VarDumper\VarDumper;

class FormHelper
{

    public static function debug($form)
    {
        VarDumper::dump($form->getErrors(true));
        die();
    }

    public static $_ELEMENT_TYPE_TEXT = 'text';
    public static $_ELEMENT_TYPE_DATE = 'date';
    public static $_ELEMENT_TYPE_TEXTAREA = 'textarea';
    public static $_ELEMENT_TYPE_SELECT = 'select';
    public static $_ELEMENT_TYPE_CHECKBOX = 'checkbox';
    public static $_ELEMENT_TYPE_RADIO = 'radio';
    public static $_ELEMENT_TYPE_EDITOR = 'editor';

    public static $_METHOD_POST = 'POST';
    public static $_METHOD_GET = 'GET';

    public static $_FORM_TYPE_ADD = '_add';
    public static $_FORM_TYPE_EDIT = '_edit';
    

    public static $_FORM_ACTION_REINIT_URL = 'rb-reinit-url';
    public static $_FORM_ACTION_REINIT_modal = 'rb-reinit-modal';
    public static $_FORM_ACTION_REINIT_action = 'rb-reinit-action';
    public static $_CALLBACK_AFTER = 'rb-callback-after';
    public static $_CALLBACK_BEFORE = 'rb-callback-before';
    

    public static $_FORM_VALIDATE_CLASS = 'rb-check';
    public static $_FORM_CLASS = 'rb-form';
    public static $_FORM_CLASS_MEDIA_FILE = 'rb-media-file';
    public static $_FORM_CLASS_PHOTO_SINGLE = 'rb-photo-single';
    public static $_FORM_CLASS_PHOTO = 'rb-photo';
    public static $_FORM_CLASS_PHOTO_HIDDEN = 'rb-photo-hidden';
    public static $_FORM_CLASS_PHOTO_SUPPORT = 'rb-photo-support';
    public static $_FORM_CLASS_EDIT_ID = 'rb-edit-id';
    public static $_FORM_CLASS_EDITOR = 'rb-editor';
    public static $_FORM_IS_MULTIPART = 'rb-form-is-multipart';
    
    public static $_VALIDATE_CLASS_REQUIRED = 'rb-validate-required';
    public static $_VALIDATE_CLASS_EMAIL_OR_TEL = 'rb-validate-email-or-tel';
    public static $_VALIDATE_CLASS_GROUP_REQUIRED = 'rb-validate-group-required';
    public static $_VALIDATE_CLASS_NUMBER = 'rb-validate-number';
    public static $_VALIDATE_CLASS_ZIPCODE_FORMAT = 'rb-validate-zipcode-format';
    public static $_VALIDATE_CLASS_NON_NEGATIVE = 'rb-validate-non-negative';
    public static $_VALIDATE_CLASS_PHONE_NUMBER = 'rb-validate-phone-number';
    public static $_VALIDATE_CLASS_NOT_EXIST = 'rb-validate-not-exist';
    public static $_VALIDATE_CLASS_EMAIL = 'rb-validate-email';
    public static $_VALIDATE_CLASS_MIN = 'rb-validate-min';
    public static $_VALIDATE_CLASS_MAX = 'rb-validate-max';
    public static $_VALIDATE_CLASS_CONFIRM = 'rb-validate-confirm';
    public static $_VALIDATE_CLASS_CUSTOM = 'rb-validate-custom';
    public static $_VALIDATE_CLASS_CUSTOM_CALLBACK = 'rb-validate-custom-callback';
    public static $_DATA_URL = 'rb-data-url';
    public static $_DATA_PARAMS = 'rb-data-params';
    public static $_DATA_IS_MULTI_LANGUAGES = 'rb-data-is-multi-languages';
    public static $_REF_ID = 'rb-ref-id';
    public static $_REF_CLASS = 'rb-ref-class';
    public static $_REF_NAME = 'rb-ref-name';
    public static $_CLASS_PLACEHOLDER = 'rb-placeholder';
    public static $_CLASS_REINIT_PLACEHOLDER = 'rb-reinit-placeholder';
    public static $_CLASS_DATETIME_PICKER = 'rb-datetime-picker';
    public static $_CLASS_ENTITY_COLLECTION = 'rb-entity-collection';
    public static $_VALIDATE_CLASS_MULTI_SELECT_REQUIRED = 'rb-validate-multi-select-required';
    public static $_CLASS_MONEY = 'rb-money';
}
