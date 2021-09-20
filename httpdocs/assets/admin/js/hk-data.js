var BOB = {
    getCallBack: function ($ob) {
        return $ob.attr('rb-callback') || '';
    },
    getCallBackBefore: function ($ob) {
        return $ob.attr('rb-callback-before') || '';
    },
    getCallBackAfter: function ($ob) {
        return $ob.attr('rb-callback-after') || '';
    },
    getParams: function ($ob) {
        return JSON.stringify(JSON.parse($ob.attr('rb-data-params') || '{}'));
    },
    setParams: function ($ob, $val) {
        return $ob.attr('rb-data-params', $val);
    },
    getUrl: function ($ob) {
        return $ob.attr('rb-data-url') || '';
    },
    setUrl: function ($ob, $val) {
        return $ob.attr('rb-data-url', $val);
    },
    getMethod: function ($ob) {
        return $ob.attr('rb-data-method') || '';
    },
    setMethod: function ($ob, $val) {
        return $ob.attr('rb-data-method', $val);
    },
    getId: function ($ob) {
        return $ob.attr('rb-data-id') || '-1';
    },
    setId: function ($ob, $val) {
        return $ob.attr('rb-data-id', $val);
    },
    getIsConfirm: function ($ob) {
        return $ob.attr('rb-data-is-confirm') || '0';
    },
    setIsConfirm: function ($ob, $val) {
        return $ob.attr('rb-data-is-confirm', $val);
    },
    getIsConfirmText: function ($ob) {
        return $ob.attr('rb-data-is-confirm-text') || '';
    },
    setIsConfirmText: function ($ob, $val) {
        return $ob.attr('rb-data-is-confirm-text', $val);
    },
    getType: function ($ob) {
        return $ob.attr('rb-data-type') || '-1';
    },
    setType: function ($ob, $val) {
        return $ob.attr('rb-data-type', $val);
    },
    getIsReload: function ($ob) {
        return $ob.attr('rb-data-is-reload') || '';
    },
    setIsReload: function ($ob, $val) {
        return $ob.attr('rb-data-is-reload', $val);
    },
    getSelected: function ($ob) {
        return $ob.attr('rb-data-selected') || '';
    },
    setSelected: function ($ob, $val) {
        return $ob.attr('rb-data-selected', $val);
    },
    getRefId: function ($ob) {
        return $ob.attr('rb-ref-id') || '';
    },
    setRefId: function ($ob, $val) {
        return $ob.attr('rb-ref-id', $val);
    },
    getRefName: function ($ob) {
        return $ob.attr('rb-ref-name') || '';
    },
    setRefName: function ($ob, $val) {
        return $ob.attr('rb-ref-name', $val);
    },
    getRefClass: function ($ob) {
        return $ob.attr('rb-ref-class') || '';
    },
    setRefClass: function ($ob, $val) {
        return $ob.attr('rb-ref-class', $val);
    },
    getFormError: function ($ob) {
        return $ob.attr('rb-form-error') || '';
    },
    setFormError: function ($ob, $val) {
        return $ob.attr('rb-form-error', $val);
    },
    getFormIsMultiPart: function ($ob) {
        return $ob.attr('rb-form-is-multipart') || '';
    },
    setFormIsMultiPart: function ($ob, $val) {
        return $ob.attr('rb-form-is-multipart', $val);
    },
    getValidateRequired: function ($ob) {
        return $ob.attr('rb-validate-required') || '';
    },
    setValidateRequired: function ($ob, $val) {
        return $ob.attr('rb-validate-required', $val);
    },
    getValidateRequiredError: function ($ob) {
        return $ob.attr('rb-validate-required-error') || '';
    },
    setValidateRequiredError: function ($ob, $val) {
        return $ob.attr('rb-validate-required-error', $val);
    },
    getValidateEmailOrTel: function ($ob) {
        return $ob.attr('rb-validate-email-or-tel') || '';
    },
    setValidateEmailOrTel: function ($ob, $val) {
        return $ob.attr('rb-validate-email-or-tel', $val);
    },
    getValidateEmailOrTelError: function ($ob) {
        return $ob.attr('rb-validate-email-or-tel-error') || '';
    },
    setValidateEmailOrTelError: function ($ob, $val) {
        return $ob.attr('rb-validate-email-or-tel-error', $val);
    },
    getValidateGroupRequired: function ($ob) {
        return $ob.attr('rb-validate-group-required') || '';
    },
    setValidateGroupRequired: function ($ob, $val) {
        return $ob.attr('rb-validate-group-required', $val);
    },
    getValidateGroupRequiredError: function ($ob) {
        return $ob.attr('rb-validate-group-required-error') || '';
    },
    setValidateGroupRequiredError: function ($ob, $val) {
        return $ob.attr('rb-validate-group-required-error', $val);
    },
    getGroup: function ($ob) {
        return $ob.attr('rb-data-group') || '';
    },
    setGroup: function ($ob, $val) {
        return $ob.attr('rb-data-group', $val);
    },
    getValidateNumber: function ($ob) {
        return $ob.attr('rb-validate-number') || '';
    },
    setValidateNumber: function ($ob, $val) {
        return $ob.attr('rb-validate-number', $val);
    },
    getValidateNumberError: function ($ob) {
        return $ob.attr('rb-validate-number-error') || '';
    },
    setValidateNumberError: function ($ob, $val) {
        return $ob.attr('rb-validate-number-error', $val);
    },
    getValidateZipcodeFormat: function ($ob) {
        return $ob.attr('rb-validate-zipcode-format') || '';
    },
    setValidateZipcodeFormat: function ($ob, $val) {
        return $ob.attr('rb-validate-zipcode-format', $val);
    },
    getValidateZipcodeFormatError: function ($ob) {
        return $ob.attr('rb-validate-zipcode-format-error') || '';
    },
    setValidateZipcodeFormatError: function ($ob, $val) {
        return $ob.attr('rb-validate-zipcode-format-error', $val);
    },
    getValidateNonNegative: function ($ob) {
        return $ob.attr('rb-validate-non-negative') || '';
    },
    setValidateNonNegative: function ($ob, $val) {
        return $ob.attr('rb-validate-non-negative', $val);
    },
    getValidateNonNegativeError: function ($ob) {
        return $ob.attr('rb-validate-non-negative-error') || '';
    },
    setValidateNonNegativeError: function ($ob, $val) {
        return $ob.attr('rb-validate-non-negative-error', $val);
    },
    getValidatePhoneNumber: function ($ob) {
        return $ob.attr('rb-validate-phone-number') || '';
    },
    setValidatePhoneNumber: function ($ob, $val) {
        return $ob.attr('rb-validate-phone-number', $val);
    },
    getValidatePhoneNumberError: function ($ob) {
        return $ob.attr('rb-validate-phone-number-error') || '';
    },
    setValidatePhoneNumberError: function ($ob, $val) {
        return $ob.attr('rb-validate-phone-number-error', $val);
    },
    getValidateNotExist: function ($ob) {
        return $ob.attr('rb-validate-not-exist') || '';
    },
    setValidateNotExist: function ($ob, $val) {
        return $ob.attr('rb-validate-not-exist', $val);
    },
    getValidateNotExistError: function ($ob) {
        return $ob.attr('rb-validate-not-exist-error') || '';
    },
    setValidateNotExistError: function ($ob, $val) {
        return $ob.attr('rb-validate-not-exist-error', $val);
    },
    getValidateEmail: function ($ob) {
        return $ob.attr('rb-validate-email') || '';
    },
    setValidateEmail: function ($ob, $val) {
        return $ob.attr('rb-validate-email', $val);
    },
    getValidateEmailError: function ($ob) {
        return $ob.attr('rb-validate-email-error') || '';
    },
    setValidateEmailError: function ($ob, $val) {
        return $ob.attr('rb-validate-email-error', $val);
    },
    getValidateMin: function ($ob) {
        return $ob.attr('rb-validate-min') || '';
    },
    setValidateMin: function ($ob, $val) {
        return $ob.attr('rb-validate-min', $val);
    },
    getValidateMinError: function ($ob) {
        return $ob.attr('rb-validate-min-error') || '';
    },
    setValidateMinError: function ($ob, $val) {
        return $ob.attr('rb-validate-min-error', $val);
    },
    getValidateMax: function ($ob) {
        return $ob.attr('rb-validate-max') || '';
    },
    setValidateMax: function ($ob, $val) {
        return $ob.attr('rb-validate-max', $val);
    },
    getValidateMaxError: function ($ob) {
        return $ob.attr('rb-validate-max-error') || '';
    },
    setValidateMaxError: function ($ob, $val) {
        return $ob.attr('rb-validate-max-error', $val);
    },
    getValidateConfirm: function ($ob) {
        return $ob.attr('rb-validate-confirm') || '';
    },
    setValidateConfirm: function ($ob, $val) {
        return $ob.attr('rb-validate-confirm', $val);
    },
    getValidateConfirmError: function ($ob) {
        return $ob.attr('rb-validate-confirm-error') || '';
    },
    setValidateConfirmError: function ($ob, $val) {
        return $ob.attr('rb-validate-confirm-error', $val);
    },
    getPage: function ($ob) {
        return $ob.attr('rb-data-page') || '';
    },
    setPage: function ($ob, $val) {
        return $ob.attr('rb-data-page', $val);
    },
    getLimit: function ($ob) {
        return $ob.attr('rb-data-limit') || '';
    },
    setLimit: function ($ob, $val) {
        return $ob.attr('rb-data-limit', $val);
    },
    getIsPublished: function ($ob) {
        return $ob.attr('rb-data-is-published') || '';
    },
    setIsPublished: function ($ob, $val) {
        return $ob.attr('rb-data-is-published', $val);
    },
    getIsPublishedText: function ($ob) {
        return $ob.attr('rb-data-is-published-text') || '';
    },
    setIsPublishedText: function ($ob, $val) {
        return $ob.attr('rb-data-is-published-text', $val);
    },
    getUrlBase: function ($ob) {
        return $ob.attr('rb-data-url-base') || '';
    },
    setUrlBase: function ($ob, $val) {
        return $ob.attr('rb-data-url-base', $val);
    },
    getUrlFilter: function ($ob) {
        return $ob.attr('rb-data-url-filter') || '';
    },
    setUrlFilter: function ($ob, $val) {
        return $ob.attr('rb-data-url-filter', $val);
    },
    getPaginatorLength: function ($ob) {
        return $ob.attr('rb-data-paginator-length') || '';
    },
    setPaginatorLength: function ($ob, $val) {
        return $ob.attr('rb-data-paginator-length', $val);
    },
    getLimitText: function ($ob) {
        return $ob.attr('rb-data-limit-text') || '';
    },
    setLimitText: function ($ob, $val) {
        return $ob.attr('rb-data-limit-text', $val);
    },
    getModalTextOk: function ($ob) {
        return $ob.attr('rb-data-modal-text-ok') || '';
    },
    setModalTextOk: function ($ob, $val) {
        return $ob.attr('rb-data-modal-text-ok', $val);
    },
    getModalTextConfirm: function ($ob) {
        return $ob.attr('rb-data-modal-text-confirm') || '';
    },
    setModalTextConfirm: function ($ob, $val) {
        return $ob.attr('rb-data-modal-text-confirm', $val);
    },
    getModalTitleConfirm: function ($ob) {
        return $ob.attr('rb-data-modal-title-confirm') || '';
    },
    setModalTitleConfirm: function ($ob, $val) {
        return $ob.attr('rb-data-modal-title-confirm', $val);
    },
    getModalTitleOk: function ($ob) {
        return $ob.attr('rb-data-modal-title-ok') || '';
    },
    setModalTitleOk: function ($ob, $val) {
        return $ob.attr('rb-data-modal-title-ok', $val);
    },
    getAdminCheckedRequireError: function ($ob) {
        return $ob.attr('rb-data-admin-checked-require-error') || '';
    },
    setAdminCheckedRequireError: function ($ob, $val) {
        return $ob.attr('rb-data-admin-checked-require-error', $val);
    },
    getValidateCustom: function ($ob) {
        return $ob.attr('rb-validate-custom') || '';
    },
    setValidateCustom: function ($ob, $val) {
        return $ob.attr('rb-validate-custom', $val);
    },
    getValidateCustomError: function ($ob) {
        return $ob.attr('rb-validate-custom-error') || '';
    },
    setValidateCustomError: function ($ob, $val) {
        return $ob.attr('rb-validate-custom-error', $val);
    },
    getValidateCustomCallback: function ($ob) {
        return $ob.attr('rb-validate-custom-callback') || '';
    },
    setValidateCustomCallback: function ($ob, $val) {
        return $ob.attr('rb-validate-custom-callback', $val);
    },
    getLevel: function ($ob) {
        return $ob.attr('rb-data-level') || '';
    },
    setLevel: function ($ob, $val) {
        return $ob.attr('rb-data-level', $val);
    },
    getModule: function ($ob) {
        return $ob.attr('rb-data-module') || '';
    },
    setModule: function ($ob, $val) {
        return $ob.attr('rb-data-module', $val);
    },
    getIsMultiLanguages: function ($ob) {
        return $ob.attr('rb-data-is-multi-languages') || '';
    },
    setIsMultiLanguages: function ($ob, $val) {
        return $ob.attr('rb-data-is-multi-languages', $val);
    },
    getLang: function ($ob) {
        return $ob.attr('rb-data-lang') || '';
    },
    setLang: function ($ob, $val) {
        return $ob.attr('rb-data-lang', $val);
    },
    getCurrentLang: function ($ob) {
        return $ob.attr('rb-data-current-lang') || '';
    },
    setCurrentLang: function ($ob, $val) {
        return $ob.attr('rb-data-current-lang', $val);
    },
    getSize: function ($ob) {
        return $ob.attr('rb-data-size') || '';
    },
    setSize: function ($ob, $val) {
        return $ob.attr('rb-data-size', $val);
    },
    getCount: function ($ob) {
        return $ob.attr('rb-data-count') || '';
    },
    setCount: function ($ob, $val) {
        return $ob.attr('rb-data-count', $val);
    },
    getQuantity: function ($ob) {
        return $ob.attr('rb-data-quantity') || '';
    },
    setQuantity: function ($ob, $val) {
        return $ob.attr('rb-data-quantity', $val);
    },
    getPrice: function ($ob) {
        return $ob.attr('rb-data-price') || '';
    },
    setPrice: function ($ob, $val) {
        return $ob.attr('rb-data-price', $val);
    },
    getError: function ($ob) {
        return $ob.attr('rb-data-error') || '';
    },
    setError: function ($ob, $val) {
        return $ob.attr('rb-data-error', $val);
    },
    getTotalPage: function ($ob) {
        return $ob.attr('rb-data-total-page') || '';
    },
    setTotalPage: function ($ob, $val) {
        return $ob.attr('rb-data-total-page', $val);
    },
    getValidateNumberMin: function ($ob) {
        return $ob.attr('rb-validate-number-min') || '';
    },
    setValidateNumberMin: function ($ob, $val) {
        return $ob.attr('rb-validate-number-min', $val);
    },
    getValidateNumberMinError: function ($ob) {
        return $ob.attr('rb-validate-number-min-error') || '';
    },
    setValidateNumberMinError: function ($ob, $val) {
        return $ob.attr('rb-validate-number-min-error', $val);
    },
    getValidateNumberMax: function ($ob) {
        return $ob.attr('rb-validate-number-max') || '';
    },
    setValidateNumberMax: function ($ob, $val) {
        return $ob.attr('rb-validate-number-max', $val);
    },
    getValidateNumberMaxError: function ($ob) {
        return $ob.attr('rb-validate-number-max-error') || '';
    },
    setValidateNumberMaxError: function ($ob, $val) {
        return $ob.attr('rb-validate-number-max-error', $val);
    },
}