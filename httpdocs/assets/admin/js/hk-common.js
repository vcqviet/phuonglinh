if (typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, '');
    }
}
var BJSON = {
    formToJSON: function (form) {
        var json = {};
        form.forEach(function (e) {
            json[e.name] = e.value || '';
        });
        return json;
    },
    stringToJSON: function (str) {
        return JSON.parse(str);
    },
    JSONToString: function (json) {
        return JSON.stringify(json);
    }
};
var BSTRING = {
    getStringNumber: function ($num, $length) {
        $num = parseInt($num, 10);
        $rt = '';
        for (var $i = 0; $i < $length - $num.toString().length; $i++) {
            $rt += '0';
        }
        return $rt += $num.toString();
    }
};
var BLOADING = {
    isLoading: false,
    show: function () {
        // show loading box here
        BLOADING.isLoading = true;
        $(".preloader-it").fadeIn("fast");
        $('#rb-pre-loader').show();
    },
    hide: function () {
        BLOADING.isLoading = false;
        $(".preloader-it").fadeOut("fast");
        $('#rb-pre-loader').hide();
        // hide loading box here
    }
};
var BMONEY = {
    sepa: ',',
    init: function () {
        $('.rb-money').each(function () {
            $(this).val(BMONEY.format($(this).val()));
        });
        $('.rb-money-show').each(function () {
            $(this).text(BMONEY.format($(this).text()));
        });
        $('input.rb-money').change(function () {
            $(this).val(BMONEY.format($(this).val()));
        }).focus(function () {
            if ($(this).val() == '0') {
                $(this).val('');
            }
        }).focusout(function () {
            if ($(this).val() == '') {
                $(this).val('0');
            }
        });
    },
    format: function (val) {
        if (val == '') {
            return '0';
        }
        var arr = (val + '').split('.');
        var strVal = BMONEY.replace(arr[0]);
        var length = strVal.length;
        var newStrVal = '';
        for (var i = 1; i <= length; i++) {
            if ((i - 1) % 3 == 0 && i > 1) {
                newStrVal = strVal[length - i] + BMONEY.sepa + newStrVal;
            } else {
                newStrVal = strVal[length - i] + newStrVal;
            }
        }
        if (arr.length == 2) {
            return newStrVal + '.' + arr[1];
        }
        return newStrVal;
    },
    replace: function (val) {
        if (val == '') {
            return '0';
        }
        var strVal = val + '';
        return strVal.replace(/,/g, '');
    }
};
var BVALIDATOR = {
    isEmail: function (val) {
        var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        return pattern.test(val);
    },
    isPhoneNumber: function (val) {
        var pattern = new RegExp(/^(((\(\+\d+\))|(\+\d+))(-|\.|\s)+)?(\d|-|\.|\s){6,20}$/i);
        return pattern.test(val);
    },
    isNumber: function (val) {
        return /^[-+]?[0-9]+$/.test(val);
    },
    isNumberGreaterThanZero: function (val) {
        return /^[+]?[1-9][0-9]*$/.test(val);
    },
    isNumberNoneNegative: function (val) {
        return /^[+]?[0-9]+$/.test(val);
    }
};
var BALERT = {
    TYPE: {
        success: 'success',
        warning: 'warning',
        error: 'error',
        confirm: 'confirm',
        information: 'info'
    },
    position: 'top-right',
    loaderBg: '#7a5449',
    delay: 12000,
    effect: 'fade',
    showArray: function (header, arrTexts, type) {
        var $class = '';
        var text = '<ul>';
        for (var i = 0; i < arrTexts.length; i++) {
            text += '<li>' + arrTexts[i] + '</li>'
        }
        text += '<ul>';
        switch (type) {
            case BALERT.TYPE.success:
                if (header == '') {
                    header = ' Success';
                }
                $class = 'jq-has-icon jq-toast-success';
                header = '<i class="far fa-check-circle"></i> ' + header;
                break;
            case BALERT.TYPE.warning:
                if (header == '') {
                    header = ' Warning';
                }
                $class = 'jq-has-icon jq-toast-warning';
                header = '<i class="fas fa-exclamation"></i>' + header;
                break;
            case BALERT.TYPE.error:
                if (header == '') {
                    header = ' Error';
                }
                $class = 'jq-has-icon jq-toast-danger';
                header = '<i class="fas fa-exclamation-triangle"></i>' + header;
                break;
            case BALERT.TYPE.information:
                if (header == '') {
                    header = ' Information';
                }
                $class = 'jq-has-icon jq-toast-info';
                header = '<i class="fas fa-info"></i>' + header;
                break;
        }
        $.toast().reset('all');
        $.toast({
            heading: header,
            text: text,
            position: BALERT.position,
            loaderBg: BALERT.loaderBg,
            class: $class,
            hideAfter: BALERT.delay,
            stack: 6,
            showHideTransition: BALERT.effect
        });
        BLOADING.hide();

        return false;
    },
    showError: function (data) {

        BALERT.show('', data.error, BALERT.TYPE.error);
    },
    showOk: function (message) {
        BALERT.show('', message, BALERT.TYPE.success);
    },
    show: function (header, text, type) {

        var $class = '';
        switch (type) {
            case BALERT.TYPE.success:
                if (header == '') {
                    header = ' Success';
                }
                $class = 'jq-has-icon jq-toast-success';
                header = '<i class="far fa-check-circle"></i> ' + header;
                break;
            case BALERT.TYPE.warning:
                if (header == '') {
                    header = ' Warning';
                }
                $class = 'jq-has-icon jq-toast-warning';
                header = '<i class="fas fa-exclamation"></i>' + header;
                break;
            case BALERT.TYPE.error:
                if (header == '') {
                    header = ' Error';
                }
                $class = 'jq-has-icon jq-toast-danger';
                header = '<i class="fas fa-exclamation-triangle"></i>' + header;
                break;
            case BALERT.TYPE.information:
                if (header == '') {
                    header = ' Information';
                }
                $class = 'jq-has-icon jq-toast-info';
                header = '<i class="fas fa-info"></i>' + header;
                break;
        }
        $.toast().reset('all');
        $.toast({
            heading: header,
            text: text,
            position: BALERT.position,
            loaderBg: BALERT.loaderBg,
            class: $class,
            hideAfter: BALERT.delay,
            stack: 6,
            showHideTransition: BALERT.effect
        });
        BLOADING.hide();
        return false;
    }
}
var BMODAL = {
    TYPE: {
        confirm: 'confirm',
        information: 'information'
    },
    addArray: function (data) {
        var $ul = $('<ul></ul>');
        for ($i = 0; $i < data.length; $i++) {
            $ul.append('<li>' + data[i] + '</li>');
        }
        $('#rb-modal-page-content').html('').append($ul);
    },
    setText: function (text) {
        $('#rb-modal-page-content').html('').append(text);
    },
    setTitle: function (title) {
        $('#rb-modal-page-title').html('').append(title);
    },
    setButtonCancel: function (text) {
        $('#rb-modal-page-cancel').html('').append(text);
    },
    setButtonOk: function (text) {
        $('#rb-modal-page-ok').html('').append(text);
    },
    setType: function (type) {
        var $ob = $('#rb-modal-page');
        switch (type) {
            case BMODAL.TYPE.information:
                BMODAL.setTitle(BOB.getModalTitleOk($ob));
                BMODAL.setButtonOk(BOB.getModalTextOk($ob));
                break;
            case BMODAL.TYPE.confirm:
                BMODAL.setTitle(BOB.getModalTitleConfirm($ob));
                BMODAL.setButtonOk(BOB.getModalTextConfirm($ob));
                break;
        }
    },
    show: function (cb) {
        var $ob = BMODAL.hide();
        cb = cb || '';
        $('#rb-modal-page-ok').unbind('click').click(function () {
            BMODAL.hide();
            if (typeof (cb) === 'function') {
                return cb();
            }
        });
        $ob.modal('show');
    },
    hide: function () {
        var $ob = $('#rb-modal-page');
        $ob.modal('hide');
        return $ob;
    }
};
var BFORM = {
    editorOptions: {

    },
    editorOptionMins: {
        toolbarGroups: [
            { name: 'styles' },
            { name: 'basicstyles', groups: ['basicstyles', 'undo'] },
            { name: 'colors' },
            { name: 'paragraph', groups: ['list', 'align'] },
            { name: 'insert' },
            { name: 'others' }
        ]
    },
    edtiors: [],
    classError: 'border-danger',
    reInit: function () {
        $('input.rb-form, button.rb-form, a.rb-form').unbind('click').click(function () {
            if (BLOADING.isLoading) {
                return;
            }
            BLOADING.show();
            var $ob = $(this);

            var $form = null;
            if (BOB.getRefId($ob) != '') {
                $form = $('form#' + BOB.getRefId($ob));
            }
            if (!$form) {
                $form = $('form[name=' + BOB.getRefName($ob) + ']');
            }
            $form.submit(function () {
                return false;
            });
            try {
                /*for (var i = 0; BFORM.edtiors.length; i++) {
                    var $formElement = $('#' + BFORM.edtiors[i].sourceElement.id);
                    if ($formElement) {
                        $formElement.val(BFORM.edtiors[i].getData());
                    }
                }*/
                if (CKEDITOR && typeof (CKEDITOR) != 'undefined') {
                    $form.find('textarea.rb-editor').each(function () {
                        var $textarea = $(this);
                        $textarea.val(CKEDITOR.instances[$textarea.attr('id')].getData());
                    });
                    $form.find('textarea.rb-editor-min').each(function () {
                        var $textarea = $(this);
                        $textarea.val(CKEDITOR.instances[$textarea.attr('id')].getData());
                    });
                    $('input.rb-money').each(function () {
                        $(this).val(BMONEY.replace($(this).val()));
                    });
                }
            } catch (ex) { }

            if (BFORM.FORM.isValidateForm($form)) {
                var url = BOB.getUrl($ob);
                if (!url || url == '') {
                    $form.submit();
                    return false;
                }
                var submit = function ($url) {
                    $url = $url || url;
                    var $option = {
                        'type': 'POST',
                        'url': $url,
                        'success': function (data) {
                            BLOADING.hide();
                            var callbackAfter = BOB.getCallBackAfter($ob);
                            if (callbackAfter != '' && typeof (window[callbackAfter]) === 'function') {
                                var fn = window[callbackAfter];
                                return fn(data);
                            }
                            if (!BCOMMON.isOk(data)) {
                                BALERT.show('', data.error, BALERT.TYPE.error);
                            }
                        }, 'error': function (e) {
                            BLOADING.hide();
                            var formError = BOB.getFormError($ob);
                            if (formError != '') {
                                BALERT.show('', data.formError, BALERT.TYPE.error);
                            }
                        }
                    }
                    if (BOB.getFormIsMultiPart($ob) == '1') {
                        $option.data = new FormData($form[0]);
                        // $option.async = false;
                        $option.cache = false;
                        $option.contentType = false;
                        $option.processData = false;
                    } else {
                        $option.dataType = 'json';
                        $option.data = $form.serialize();
                    }
                    $.ajax($option);
                };
                var callbackBefore = BOB.getCallBackBefore($ob);
                if (callbackBefore != '' && typeof (window[callbackBefore]) === 'function') {
                    var f = window[callbackBefore];
                    if (f($ob)) {
                        submit(BOB.getUrl($ob));
                    }
                    return false;
                }
                submit();
                return false;
            }
        });
    },
    init: function () {
        //ClassicEditor.builtinPlugins.map(plugin => { console.log(plugin.pluginName) });

        BFORM.FORM.editorInit();
    },
    FORM: {
        editorInit: function () {
            $('textarea.rb-editor').each(function () {
                $ob = $(this);
                BFORM.FORM.renderEditor($ob);
            });
            $('textarea.rb-editor-min').each(function () {
                $ob = $(this);
                BFORM.FORM.renderEditorMin($ob);
            });
        },
        renderEditorMin: function ($ob, f) {

            if (CKEDITOR && typeof (CKEDITOR) !== 'undefined') {
                CKEDITOR.replace($ob.attr('id'), BFORM.editorOptionMins);
                CKEDITOR.on("instanceReady", function (event) {
                    if (f) {
                        f();
                    }
                });
            }
        },
        renderEditor: function ($ob, f) {

            if (CKEDITOR && typeof (CKEDITOR) !== 'undefined') {
                CKEDITOR.replace($ob.attr('id'), BFORM.editorOptions);
                CKEDITOR.on("instanceReady", function (event) {
                    if (f) {
                        f();
                    }
                });
            }

            /*if (ClassicEditor && typeof (ClassicEditor) !== 'undefined') {
                ClassicEditor.create(document.querySelector('#' + $ob.attr('id')), {
                    extraPlugins: [ MyCustomUploadAdapterPlugin ]

                }).then(editor => {
                    BFORM.edtiors.push(editor);
                    if (f) {
                        f(editor);
                    }
                }).catch(error => {
                    //console.log(error);
                });;
            }*/
        },
        isValidate: function ($ob) {
            var error = '';
            if (!$ob || !$ob.val()) {
                //return false;
            }
            var val = $ob.val().trim();
            if (BOB.getValidateRequired($ob) == '1') {
                error = BOB.getValidateRequiredError($ob);
                var vcheck = 0;
                if (BOB.getValidateRequired($ob) == 'none-negative') {
                    vcheck = -1;
                }
                if (val == '' || val == $ob.attr("placeholder") || (parseInt(val, 10) <= vcheck && $ob.is('select'))) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateEmailOrTel($ob) == '1') {
                error = BOB.getValidateEmailOrTelError($ob);
                if (val != '' && !BVALIDATOR.isEmail(val) && BVALIDATOROR.isPhoneNumber(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateGroupRequired($ob) == '1') {
                error = BOB.getValidateGroupRequiredError($ob);
                var isError = true;
                $('*[rb-data-group=' + BOB.getGroup($ob) + ']').each(function () {
                    val = ($(this).is(':checkbox') ? ($(this).is(':checked') ? $(this).val() : '') : $(this).val());
                    if (val != '') {
                        isError = false;
                    }
                });
                if (isError) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateNumber($ob) == '1') {
                error = BOB.getValidateNumberError($ob);
                if (val != '' && !BVALIDATOR.isNumber(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateZipcodeFormat($ob) == '1') {
                error = BOB.getValidateZipcodeFormatError($ob);
                if (val != '' && !BVALIDATOR.isNumberNoneNegative(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateNonNegative($ob) == '1') {
                error = BOB.getValidateNonNegativeError($ob);

                if (val != '' && !BVALIDATOR.isNumberNoneNegative(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }

            if (BOB.getValidatePhoneNumber($ob) == '1') {
                error = BOB.getValidatePhoneNumberError($ob);
                if (val != '' && !BVALIDATOR.isPhoneNumber(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }

            if (BOB.getValidateNotExist($ob) == '1') {
                if (val != '') {
                    error = BOB.getValidateNotExistError($ob);
                    id = $('.' + BOB.getRefClass($ob)).val();
                    var $isAjaxCheck = false;
                    //BLOADING.show();
                    $.ajax({
                        'url': BOB.getUrl($ob),
                        'type': 'POST',
                        'async': false,
                        'data': {
                            'params': BOB.getParams($ob),
                            'val': val,
                            'id': id
                        },
                        'success': function (data) {
                            //BLOADING.hide();
                            if (BCOMMON.isOk(data) && parseInt(data.data.isExist, 10) != 0) {
                                $isAjaxCheck = true;
                            }
                        }, 'error': function (e) {
                            $isAjaxCheck = true;
                        }
                    });
                    if ($isAjaxCheck) {
                        $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                        return error;
                    }
                }
            }
            if (BOB.getValidateEmail($ob) == '1') {
                error = BOB.getValidateEmailError($ob);
                if (val != '' && !BVALIDATOR.isEmail(val)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateMin($ob) != '') {
                error = BOB.getValidateMinError($ob);
                if (val != '' && val.length < parseInt(BOB.getValidateMin($ob), 10)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }

            if (BOB.getValidateMax($ob) != '') {
                error = BOB.getValidateMaxError($ob);
                if (val != '' && val.length > parseInt(BOB.getValidateMax($ob), 10)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateNumberMin($ob) != '') {
                error = BOB.getValidateNumberMinError($ob);
                if (val == '' || parseInt(val, 10) < parseInt(BOB.getValidateNumberMin($ob), 10)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }

            if (BOB.getValidateNumberMax($ob) != '') {
                error = BOB.getValidateNumberMaxError($ob);
                if (val == '' || parseInt(val, 10) > parseInt(BOB.getValidateNumberMax($ob), 10)) {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateConfirm($ob) == '1') {
                error = BOB.getValidateConfirmError($ob);
                var $obc = null;
                if (BOB.getRefId($ob) != '') {
                    $obc = $('#' + BOB.getRefId($ob));
                }
                if (!$obc) {
                    $obc = $('input.' + BOB.getRefClass($ob));
                }
                if (!$obc || !$obc.val()) {
                    //return false;
                }
                var val2 = $obc.val().trim();
                if (val != val2 && val2 != '') {
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return error;
                }
            }
            if (BOB.getValidateCustom($ob) == '1') {
                var callback = BOB.getValidateCustomCallback($ob);
                if (callback != '') {
                    var f = window[callback];
                    if (typeof (f) === 'function') {
                        var error = f($ob);
                        if (error !== 0) {
                            $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                            return error;
                        }
                        $ob.removeClass(BFORM.classError);
                        return 0;
                    }
                    $ob.removeClass(BFORM.classError).addClass(BFORM.classError);
                    return BOB.getValidateCustomError($ob);
                }
            }
            $ob.removeClass(BFORM.classError);
            return 0;
        },
        isValidateForm: function ($form) {
            var $arrErrors = [];
            //BLOADING.show();
            $form.find('input.rb-check, textarea.rb-check, select.rb-check').each(function () {
                var $isError = BFORM.FORM.isValidate($(this));
                if ($isError !== 0) {
                    $arrErrors.push($isError);
                }
            });
            //BLOADING.hide();
            if ($arrErrors.length > 0) {
                BALERT.showArray('', $arrErrors, BALERT.TYPE.error);
                return false;
            }
            return true;
        }
    },
    CHECKBOX: {
        isCheckedOne: function ($obs) {
            var $isCheckedOne = false;
            $obs.each(function () {
                if (BFORM.CHECKBOX.isChecked($(this))) {
                    $isCheckedOne = true;
                }
            });
            return $isCheckedOne;
        },
        isCheckedAll: function ($obs) {
            var $isCheckedAll = true;
            $obs.each(function () {
                if (!BFORM.CHECKBOX.isChecked($(this))) {
                    $isCheckedAll = false;
                }
            });
            return $isCheckedAll;
        },
        isChecked: function ($ob) {
            return $ob.is(':checked');
        },
        check: function ($ob, isChecked) {
            $ob.prop('checked', isChecked);
        },
        checkAllList: function ($obs, isChecked) {
            $obs.each(function () {
                BFORM.CHECKBOX.check($(this), isChecked);
            });
        },
    },
    SELECT: {
        setSelected: function ($ob, $val = '') {
            if ($val != '') {
                $ob.val($val);
                return;
            }
            var selected = BOB.getSelected($ob);
            if (selected != '') {
                $ob.val(selected);
                return;
            }
            $ob.val($ob.find('option:first').val());
        },
        addOption: function ($ob, data) {
            $ob.html('');
            data = data.data;
            for (var i = 0; i < data.length; i++) {
                var $option = $('<option value=""></option>');
                $option.text(data[i].name);
                $option.val(data[i].id);
                $ob.append($option);
            }
            BFORM.SELECT.addOptionPlaceholder($ob);
        },
        addOptionPlaceholder: function ($ob) {
            if ($ob.attr('placeholder')) {
                var $option = $('<option value=""></option>');
                $option.text($ob.attr('placeholder'));
                $option.val('-1');
                $ob.prepend($option);
                if (window.location.pathname.indexOf('/add') >= 0) {
                    $ob.val('-1');
                }
            }
            if ($ob.find('> option').length == 1) {
                $ob.val('-1');
            }
            BFORM.SELECT.setSelected($ob);
        }
    }
};
var BAJAX = {
    METHOD_GET: 'GET',
    METHOD_POST: 'POST',
    error: function (error) {
        BLOADING.hide();
        BALERT.show('Error', 'Vui lòng kiểm tra lại đường truyền và thử lại ! <br/> Can not connect to server, please checking your internet and try again !', BALERT.TYPE.error);
    },
    send: function (url, data, fx, method) {
        method = method || 'GET';
        $.ajax({
            'type': method,
            'url': url,
            'dataType': 'json',
            'data': data,
            'success': fx,
            'error': BAJAX.error
        });
    },
};
var BCOMMON = {
    ACTIVE_CLASS: '-active',
    VOID: 'javascript:void(0);',
    isOk: function (data) {
        return parseInt(data.status, 10) == 0;
    },
    reInit: function () {
        $('select.rb-source-reinit').each(function () {
            var $ob = $(this);
            var url = BOB.getUrl($ob);
            if (url) {
                BAJAX.send(url, {
                    'params': BOB.getParams($ob)
                }, function (data) {
                    BLOADING.hide();
                    if (BCOMMON.isOk(data)) {
                        BFORM.SELECT.addOption($ob, data.data);
                        $ob.trigger('change');
                        //$ref.trigger('change');
                    }
                });
            }
        });
        $('.rb-reinit-action').each(function () {
            var $ob = $(this);
            $ob.unbind('click').click(function () {
                var url = BOB.getUrl($ob);
                if (url != '') {
                    var callbackBeforeShow = $ob.attr('rb-callback-before-show');
                    if (typeof (window[callbackBeforeShow]) === 'function') {
                        var fnb = window[callbackBeforeShow];
                        if (!fnb($ob)) {
                            return
                        };
                    }
                    var submit = function () {
                        BAJAX.send(url, {
                            id: BOB.getId($ob),
                            params: BOB.getParams($ob),
                        }, function (data) {
                            var callback = BOB.getCallBackAfter($ob);
                            if (typeof (window[callback]) === 'function') {
                                var fn = window[callback];
                                return fn(data);
                            }
                        }, BOB.getMethod($ob));
                    }
                    if (BOB.getIsConfirm($ob) == '1') {
                        BMODAL.setText(BOB.getIsConfirmText($ob));
                        BMODAL.setTitle(BOB.getModalTitleConfirm($ob));
                        BMODAL.setButtonOk(BOB.getModalTextConfirm($ob));
                        if (BOB.getModalTitleConfirm($ob) == '') {
                            BMODAL.setType(BMODAL.TYPE.confirm);
                        }
                        BMODAL.show(function () {
                            var callbackBefore = BOB.getCallBackBefore($ob);
                            if (typeof (window[callbackBefore]) === 'function') {
                                var fnb = window[callbackBefore];
                                if (fnb($ob)) {
                                    submit();
                                };
                                return;
                            }
                            submit();
                        });
                        return;
                    }
                    var callbackBefore = BOB.getCallBackBefore($ob);
                    if (typeof (window[callbackBefore]) === 'function') {
                        var fnb = window[callbackBefore];
                        if (fnb($ob)) {
                            submit();
                        };
                        return;
                    }
                    submit();
                }
            });
        });
    },
    init: function () {
        $('select.rb-placeholder').each(function () {
            var $ob = $(this);
            BFORM.SELECT.addOptionPlaceholder($ob);
        });
        $('select.rb-source-ref').each(function () {
            var $ob = $(this);
            if (BOB.getRefClass($ob) != '') {
                var $ref = $('select.' + BOB.getRefClass($ob));
                if ($ref) {
                    $ref.change(function () {
                        var url = BOB.getUrl($ob);
                        if (url) {
                            BLOADING.show();
                            var $cateId = $(this).val();
                            BAJAX.send(url, {
                                'cate_id': $cateId,
                                'params': BOB.getParams($ob)
                            }, function (data) {
                                BLOADING.hide();
                                if (BCOMMON.isOk(data)) {
                                    BFORM.SELECT.addOption($ob, data.data);
                                    $ob.trigger('change');
                                }
                            });
                        }
                    });
                }
            }
        });
        $('select.rb-source').each(function () {
            var $ob = $(this);
            var url = BOB.getUrl($ob);
            if (url) {
                BAJAX.send(url, {
                    'params': BOB.getParams($ob)
                }, function (data) {
                    BLOADING.hide();
                    if (BCOMMON.isOk(data)) {
                        BFORM.SELECT.addOption($ob, data.data);
                        $ob.trigger('change');
                        //$ref.trigger('change');
                    }
                });
            }
        });
    }
}
$(function () {
    BCOMMON.init();
    BCOMMON.reInit();
    BFORM.init();
    BFORM.reInit();
    BMONEY.init();
});