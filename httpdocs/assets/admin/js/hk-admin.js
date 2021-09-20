var badmin_reload = function (data) {
    if (BCOMMON.isOk(data)) {
        if (data.data.isReload) {
            $('button#' + BJGRID.idButtonFilter).trigger('click');
        }
        BALERT.show('', data.data.message, BALERT.TYPE.success);
        return;
    }
    BALERT.show('', data.error, BALERT.TYPE.error);
}
var badmin_formAfter = function (data) {
    if (BCOMMON.isOk(data)) {
        BALERT.show('', data.data.message, BALERT.TYPE.success);
        if (data.data.isComeback && data.data.isComeback == 'none') {
            if (typeof (window['badmin_formAfterIndex']) === 'function') {
                var fn = window['badmin_formAfterIndex'];
                return fn(data);
            }
            return;
        }
        if (data.data.isReload) {
            window.location.reload();
            return;
        }
        if (typeof (window['badmin_formAfterIndex']) === 'function') {
            var fn = window['badmin_formAfterIndex'];
            return fn(data);
        }
        $('form')[0].reset();
        //$('div.ck.ck-content p').html('<br data-cke-filler="true">');

        try {
            $('input.' + BPHOTO.classPhotoSingle).next('img').attr('src', BPHOTO.urlNoImage);
            $('div.' + BPHOTO.classPhotoSingle).find('div.col-sm-3.rb-div-photo-remove').each(function () {
                $(this).parent().remove();
            });
            $('input[type=hidden][rb-ref-class=' + BPHOTO.classPhotoSupport + ']').val('');
            $('input.' + BPHOTO.classPhoto).next('img').attr('src', BPHOTO.urlNoImage);
            $('div.' + BPHOTO.classPhoto).find('div.col-sm-3.rb-div-photo-remove').each(function () {
                $(this).parent().remove();
            });
            $('div.' + BPHOTO.classCollection).find('div.col-sm-3.rb-div-photo-remove').each(function () {
                $(this).parent().remove();
            });
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.instances[instance].setData('');
            }
            $('a#rb-admin-add-photo').trigger('click');
            $('a#rb-entity-collection-add').trigger('click');


        } catch (ex) { }
        if (data.data.isComeback) {
            window.history.back();
        }
        return;
    }
    BALERT.show('', data.error, BALERT.TYPE.error);
}
var badmin_menuControlBefore = function ($ob) {
    var ids = Array();
    $('div.rb-table-render input[type=checkbox].' + BJGRID.classCheckBoxListId).each(function () {
        var $chk = $(this);
        if (BFORM.CHECKBOX.isChecked($chk)) {
            ids.push(parseInt(BOB.getId($chk), 10));
        }
    });
    if (ids.length <= 0) {
        BALERT.show('', BOB.getAdminCheckedRequireError($('#' + BADMIN.idAdminMenuControl)), BALERT.TYPE.error);
        return false;
    }
    var params = BJSON.stringToJSON(BOB.getParams($ob));
    params.ids = ids;
    BOB.setParams($ob, BJSON.JSONToString(params));
    return true;
}
var BPHOTO = {
    currentPhoto: null,
    classPhotoSingle: 'rb-photo-single',
    classPhotoSupport: 'rb-photo-support',
    classPhoto: 'rb-photo',
    classCollection: 'rb-entity-collection',
    urlNoImage: '/assets/admin/img/no-img.png',
    initPhotoSingle: function () {
        $('input[type=text].rb-check.' + BPHOTO.classPhotoSingle).each(function () {
            var $ob = $(this);
            var $src = $ob.val();
            if ($src == '') {
                $src = BPHOTO.urlNoImage;
            }
            var $callIW = $('<img src="' + $src + '" style="width:120px;cursor: pointer"/>');
            var $imgClickHere = $('<i class="fas fa-sync-alt" style="cursor: pointer; margin-left: 5px; top: 0px; position: absolute" title="click here"></i>');
            $ob.parent().append($callIW).append($imgClickHere);
            $callIW.click(function () {
                $('#rb-modal-media').modal('show');
                BPHOTO.currentPhoto = $(this);
            });
            $imgClickHere.click(function () {
                $callIW.trigger('click');
            });
        });
    },
    addCollection: function ($className) {
        BPHOTO.checkPhotoSelect($className);
        var $collectionHolder = $('div.' + $className);
        var addPhoto = function ($collectionHolder, $newLink) {
            var prototype = $collectionHolder.data('prototype');
            var index = $collectionHolder.data('index');
            var $newForm = $(prototype.replace(/__name__/g, index));
            $collectionHolder.data('index', index + 1);
            $newLink.before($newForm);
            addRemoveLink($newLink.prev());
            removeDiv($collectionHolder);
        }
        var addRemoveLink = function ($div) {
            $div.addClass('border-bottom');
            var $removeFormA = $('<a href="' + BCOMMON.VOID + '" class=""><i class="far fa-trash-alt text-danger"></i></a>');
            $div.append($('<div class="col-sm-3 rb-div-photo-remove"></div>').append($removeFormA));

            $removeFormA.click(function (e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            });
        }
        var removeDiv = function ($collectionHolder) {
            $collectionHolder.find('> div.form-group label.col-sm-3').each(function () {
                $(this).remove();
            });
        }


        removeDiv($collectionHolder);

        var $addLink = $('<a id="rb-entity-collection-add" href="' + BCOMMON.VOID + '" class=""><i class="fas fa-plus"></i></a>');
        var $newLink = $('<div class="form-group text-right"></div>').append($addLink);

        $collectionHolder.find('> div.form-group').each(function () {
            addRemoveLink($(this));
        })
        $collectionHolder.append($newLink);
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addLink.click(function (e) {
            e.preventDefault();
            addPhoto($collectionHolder, $newLink);
        });
    },
    iwSelect: function (path) {
        if (BPHOTO.currentPhoto == null) {
            return;
        }
        BPHOTO.currentPhoto.attr('src', path);
        BPHOTO.currentPhoto.prev('input[type=text].rb-check.' + BPHOTO.classPhotoSingle).val(path);
        BPHOTO.currentPhoto.next().find('input[type=hidden]').val(path);
        $('input[type=hidden][rb-ref-class=' + BPHOTO.classPhotoSupport + ']').val('1');
        $('input[type=hidden][rb-ref-class=' + BPHOTO.classPhoto + ']').val('1');
        $('#rb-modal-media').modal('hide');
    },

    checkPhotoSelect: function ($className) {
        $('input[type=hidden][rb-ref-class=' + $className + ']').val('');
        $('div.' + $className + ' input[type=hidden]').each(function () {
            $ob = $(this);
            if ($ob.val() != '') {
                $('input[type=hidden][rb-ref-class=' + $className + ']').val('1');
            }
        });
    },
    addPhotoCollection: function ($className) {
        BPHOTO.checkPhotoSelect($className);
        var $collectionHolder = $('div.' + $className);
        var addPhoto = function ($collectionHolder, $newLink) {
            var prototype = $collectionHolder.data('prototype');
            var index = $collectionHolder.data('index');
            var $newForm = $(prototype.replace(/__name__/g, index));
            $collectionHolder.data('index', index + 1);
            $newLink.before($newForm);
            addRemoveLink($newLink.prev());
            removeDiv($collectionHolder);
        }
        var addRemoveLink = function ($div) {
            $div.addClass('border-bottom');
            var $src = $div.find('input[type=hidden]').val();
            if ($src == '') {
                $src = BPHOTO.urlNoImage;
            }
            var $callIW = $('<img src="' + $src + '" style="width:120px;cursor: pointer"/>');

            var $removeFormA = $('<a href="' + BCOMMON.VOID + '" class=""><i class="far fa-trash-alt text-danger"></i></a>');
            $div.find('> div.col-sm-9').prepend($callIW);
            $div.append($('<div class="col-sm-3 rb-div-photo-remove"></div>').append($removeFormA));

            $callIW.click(function () {
                $('#rb-modal-media').modal('show');
                BPHOTO.currentPhoto = $(this);
            });
            $removeFormA.click(function (e) {
                e.preventDefault();
                $(this).parent().parent().remove();
                BPHOTO.checkPhotoSelect($className);
            });
        }
        var removeDiv = function ($collectionHolder) {
            $collectionHolder.find('> div.form-group label.col-sm-3').each(function () {
                $(this).remove();
            });
        }


        removeDiv($collectionHolder);

        var $addLink = $('<a id="rb-admin-add-photo" href="' + BCOMMON.VOID + '" class="add-photo"><i class="fas fa-plus"></i></a>');
        var $newLink = $('<div class="form-group text-right mr-5"></div>').append($addLink);

        $collectionHolder.find('> div.form-group').each(function () {
            addRemoveLink($(this));
        })
        $collectionHolder.append($newLink);
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addLink.click(function (e) {
            e.preventDefault();
            addPhoto($collectionHolder, $newLink);
        });
    }
}
var BADMIN = {
    idAdminMenuControl: 'rb-admin-menu-control',
    classAdminMenuControl: 'rb-admin-menu-control',
    reInit: function () {
        $('input.rb-datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
        BPHOTO.addPhotoCollection(BPHOTO.classPhoto);
        BPHOTO.addCollection(BPHOTO.classCollection);
        var editId = -1;
        if ($('input.rb-edit-id')) {
            editId = $('input.rb-edit-id').val();
        }
        if (parseInt(editId, 10) <= 0) {
            $('a#rb-admin-add-photo').trigger('click');
            $('a#rb-entity-collection-add').trigger('click');
        }

        $('.rb-reinit-url').each(function () {
            var $ob = $(this);
            $ob.unbind('click').click(function () {
                var url = BOB.getUrl($ob);
                if (url != '') {
                    if (parseInt(BOB.getId($ob), 10) > 0) {
                        url += '/' + BOB.getId($ob);
                    }
                    if (BOB.getIsConfirm($ob) == '1') {
                        BMODAL.add(BOB.getIsConfirmText, function () {
                            var callbackBefore = BOB.getCallBackBefore($ob);
                            if (typeof (window[callbackBefore]) === 'function') {
                                var fnb = window[callbackBefore];
                                if (fnb($ob)) {
                                    window.location = url;
                                }
                                return;
                            }
                            window.location = url;
                        });
                        return;
                    }
                    var callbackBefore = BOB.getCallBackBefore($ob);
                    if (typeof (window[callbackBefore]) === 'function') {
                        var fnb = window[callbackBefore];
                        if (fnb($ob)) {
                            window.location = url;
                        }
                        return;
                    }
                    window.location = url;
                }
            });
        });
        $('select#' + BJGRID.idSelectLimit).unbind('change').change(function () {
            var $ob = $(this);
            BJGRID.limit = $ob.val();
            BJGRID.page = 1;
            $('button#' + BJGRID.idButtonFilter).trigger('click');
        });
        $('ul#' + BJGRID.idUlPaginator + ' li.page-item > a.page-link').each(function () {
            var $ob = $(this);
            $ob.unbind('click').click(function () {
                var $a = $(this);
                if (BOB.getPage($a) != '') {
                    BJGRID.page = BOB.getPage($a);
                    $('button#' + BJGRID.idButtonFilter).trigger('click');
                }
            });
        });
        $('div.rb-table-render input[type=checkbox]#' + BJGRID.idCheckBoxAll).unbind('click').click(function () {
            var $ob = $(this);
            var $obs = $('div.rb-table-render input[type=checkbox].' + BJGRID.classCheckBoxListId);
            if (BFORM.CHECKBOX.isChecked($ob)) {
                BFORM.CHECKBOX.checkAllList($obs, !BFORM.CHECKBOX.isCheckedAll($('div.rb-table-render input[type=checkbox].' + BJGRID.classCheckBoxListId)));
            } else {
                BFORM.CHECKBOX.checkAllList($obs, false);
            }
        });
        $('div.rb-table-render input[type=checkbox].' + BJGRID.classCheckBoxListId).each(function () {
            var $ob = $(this);
            $ob.unbind('click').click(function () {
                BFORM.CHECKBOX.check($('div.rb-table-render input[type=checkbox]#' + BJGRID.idCheckBoxAll), BFORM.CHECKBOX.isCheckedAll($('div.rb-table-render input[type=checkbox].' + BJGRID.classCheckBoxListId)));
            });
        });

        $('.admin-toggle-on').toggles({
            drag: false, // allow dragging the toggle between positions
            click: true, // allow clicking on the toggle
            text: {
                on: 'ON', // text for the ON position
                off: 'OFF' // and off
            },
            on: true, // is the toggle ON on init
            animate: 250, // animation time (ms)
            easing: 'swing', // animation transition easing function
            checkbox: null, // the checkbox to toggle (for use in forms)
            clicker: null, // element that can be clicked on to toggle. removes binding
            // from the toggle itself (use nesting)

            type: 'compact' // if this is set to 'select' then the select style
            // toggle will be used
        });
        $('.admin-toggle-off').toggles({
            drag: false, // allow dragging the toggle between positions
            click: true, // allow clicking on the toggle
            text: {
                on: 'ON', // text for the ON position
                off: 'OFF' // and off
            },
            on: false, // is the toggle ON on init
            animate: 250, // animation time (ms)
            easing: 'swing', // animation transition easing function
            checkbox: null, // the checkbox to toggle (for use in forms)
            clicker: null, // element that can be clicked on to toggle. removes binding
            // from the toggle itself (use nesting)

            type: 'compact' // if this is set to 'select' then the select style
            // toggle will be used
        });

        //photo
    },
    init: function () {
        $('input.rb-validate-multi-select-required').each(function () {
            var $ob = $(this);
            if (BFORM.CHECKBOX.isCheckedOne($('div.' + BOB.getRefClass($ob) + ' input[type="checkbox"]'))) {
                $ob.val('1');
            }
            $('div.' + BOB.getRefClass($ob) + ' input[type="checkbox"]').unbind('click').click(function () {
                $ob.val('');
                if (BFORM.CHECKBOX.isCheckedOne($('div.' + BOB.getRefClass($ob) + ' input[type="checkbox"]'))) {
                    $ob.val('1');
                }
            });
        });



        $('textarea.rb-editor').parent().addClass('col-sm-12');
        $('textarea.rb-editor').parent().prev('label').addClass('col-sm-12');
        $('div.rb-limit-render').each(function () {
            var $ob = $(this);
            BJGRID.renderLimit($ob);
        });
        $('div.rb-filter-form-render').each(function () {
            var $ob = $(this);
            var url = BOB.getUrl($ob);
            if (url) {
                BAJAX.send(url, {}, function (data) {
                    if (BCOMMON.isOk(data)) {
                        BJGRID.renderFilter(data.data, $ob);
                        BFORM.reInit();
                        $('button#' + BJGRID.idButtonFilter).trigger('click');
                    }
                });
            }
        });
        $('div.rb-admin-menu-control').each(function () {
            var $ob = $(this);
            var url = BOB.getUrl($ob);
            if (url != '') {
                $ob.html('');
                BAJAX.send(url, {}, function (data) {
                    if (BCOMMON.isOk(data)) {
                        for (var i = 0; i < data.data.length; i++) {
                            var item = data.data[i];
                            $ob.append('<a href="' + BCOMMON.VOID + '" class="' + item.class + '" rb-data-url="' + item.url + '" rb-data-method="' + item.method + '" ' + item.attr + '><i class="' + item.icon + '"></i> ' + item.text + '</a>');
                        }
                        BADMIN.reInit();
                        BCOMMON.reInit();
                        BFORM.reInit();
                    }
                });
            }
        });
        $('form .rb-check').each(function () {
            var $ob = $(this);
            if (BOB.getValidateRequired($ob) == '1') {
                $ob.parent().prev('label').append('<span class="badge badge-danger badge-indicator"></span>');
            }
        });
        if ($('form .rb-check').length > 0 && BOB.getIsMultiLanguages($('#rb-section-form')) == '1') {
            BAJAX.send('/admin/language/all', {}, function (data) {
                if (BCOMMON.isOk(data)) {
                    let editId = -1;
                    if ($('input.rb-edit-id')) {
                        editId = $('input.rb-edit-id').val();
                    }
                    if (BOB.getUrl($('#rb-section-form')) != '') {
                        BAJAX.send(BOB.getUrl($('#rb-section-form')), { id: editId }, function (dataItem) {
                            if (BCOMMON.isOk(dataItem)) {
                                BADMIN.renderMultiLanguages(data, dataItem);
                            }
                        });
                        return;
                    }
                    BADMIN.renderMultiLanguages(data, []);
                }
            });
        }
        BPHOTO.initPhotoSingle();
    },
    renderMultiLanguages: function (data, dataItem) {
        let $default = '';
        data.data.forEach(item => {
            if (item.is_default) {
                $default = item.key;
            }
        });

        $('form .rb-check').each(function () {
            var $ob = $(this);
            if (BOB.getIsMultiLanguages($ob) == '1') {

                var str = '<ul class="rb-nav-lang nav nav-tabs nav-tabs-success nav-light pa-5 mb-5 justify-content-end" rb-data-current-lang="' + $default + '">';
                data.data.forEach(item => {
                    var active = '';
                    var subfix = '';
                    if (item.is_default) {
                        active = ' active';
                    } else {
                        subfix = '_' + item.key;
                        var $clone = $ob.clone();
                        $clone.attr('id', $ob.attr('id') + '_' + item.key);
                        $clone.attr('name', $ob.attr('name').replace(']', '_' + item.key + ']'));
                        var $arr = $ob.attr('id').split('_');
                        try {
                            $clone.val(dataItem.data[item.key][$arr[$arr.length - 1]]);
                        } catch (ex) { }

                        $ob.parent().append($clone);
                        if ($ob.hasClass('rb-editor')) {
                            BFORM.FORM.renderEditor($clone, function () {
                                $clone.next('div.cke.cke_reset').addClass('d-none');
                            });
                        } else {
                            $clone.addClass('d-none');
                        }
                    }
                    str += '<li class="nav-item "><a href="' + BCOMMON.VOID + '" rb-ref-id="' + $ob.attr('id') + subfix + '" class="nav-link font-11 pl-5 pr-5' + active + '" rb-data-lang="' + item.key + '">' + item.lang + '</a></li>'
                });
                str += '</ul>';
                $ob.parent().prepend(str);

                $ob.parent().find('ul.rb-nav-lang li > a').unbind('click').click(function () {
                    var $obc = $(this);
                    var $ul = $ob.parent().find('ul.rb-nav-lang');
                    if (BOB.getLang($obc) != BOB.getCurrentLang($ul)) {
                        $ob.parent().find('ul.rb-nav-lang li > a').removeClass('active');
                        $obc.addClass('active');
                        BOB.setCurrentLang($ul, BOB.getLang($obc));

                        if ($ob.hasClass('rb-editor')) {
                            $('#' + BOB.getRefId($obc)).parent().find('div.cke.cke_reset').removeClass('d-none').addClass('d-none');
                            $('#' + BOB.getRefId($obc)).next('div.cke.cke_reset').removeClass('d-none');
                            return;
                        }
                        $('#' + BOB.getRefId($obc)).parent().find('.rb-check').removeClass('d-none').addClass('d-none');
                        $('#' + BOB.getRefId($obc)).removeClass('d-none');

                    }
                });
            }
        });
    }
}
$(function () {
    BADMIN.init();
    BADMIN.reInit();
});

