var badmin_callbackFilterBefore = function () {
    let $button = $('#rb-admin-form-filter button#' + BJGRID.idButtonFilter);
    BOB.setUrl($button, BOB.getUrlBase($button) + '/' + BJGRID.page + '/' + BJGRID.limit);
    return true;
};
var badmin_callbackFilterAfter = function (data) {
    if (BCOMMON.isOk(data)) {
        BJGRID.renderTable(data.data, $('div.rb-table-render'));
        BJGRID.renderPaginator(data.data, $('div.rb-paginator-render'));
        if (typeof (window['badmin_callbackFilterIndex']) === 'function') {
            var fn = window['badmin_callbackFilterIndex'];
            return fn(data);
        }
        return;
    }
    if (typeof (window['badmin_callbackFilterIndex']) === 'function') {
        var fn = window['badmin_callbackFilterIndex'];
        return fn(data);
    }
    BALERT.show('', 'Vui lòng kiểm tra lại đường truyền và thử lại ! <br/> Can not connect to server, please checking your internet and try again !', BALERT.TYPE.error);
};
var BJGRID = {
    idSelectLimit: 'rb-select-filter-limit',
    idButtonFilter: 'rb-button-filter',
    idUlPaginator: 'rb-ul-filter-paginator',
    idCheckBoxAll: 'rb-checkbox-filter-all',
    classCheckBoxListId: 'rb-checkbox-list-id',
    limit: 10,
    page: 1,
    renderFilter: function (data, $ob) {
        //console.log(data, 'render');
        let $form = $('<form class="form-inline" id="rb-admin-form-filter"></form>');
        let $div = $('<div class="form-row align-items-center"></div>');
        for (var i = 0; i < data.length; i++) {
            var item = data[i];
            switch (item.type.toString().trim()) {
                case 'text':
                    $div.append('<div class="col-auto"><div class="input-group mb-2"><input type="text" class="form-control ' + item.class + '" name="' + item.name + '" id="' + item.name + '" ' + item.attr + ' placeholder="' + item.placeholder + '" value="' + item.value + '"/></div></div>');
                    break;
                case 'date':
                    $div.append('<div class="col-auto"><div class="input-group mb-2"><label> ' + item.placeholder + ' </label>&nbsp;<input type="date" class="form-control ' + item.class + '" name="' + item.name + '" id="' + item.name + '" ' + item.attr + '" value="' + item.value + '"/></div></div>');
                    break;
                case 'select':
                    var $divAuto = $('<div class="col-auto"></div>');
                    var $divInner = $('<div class="input-group mb-2"></div>');
                    var $select = $('<select class="form-control custom-select d-block w-100 ' + item.class + '" id="' + item.name + '" name="' + item.name + '" placeholder="' + item.placeholder + '" ' + item.attr + '></select>')
                    for (var j = 0; j < item.options.length; j++) {
                        $select.append('<option value="' + item.options[j].value + '" ' + item.options[j].attr + '>' + item.options[j].text + '</option>');
                    }
                    $div.append($divAuto.append($divInner.append($select)));
                    break;
            }
        }
        $div.append('<div class="col-auto"><button id="' + BJGRID.idButtonFilter + '" type="button" class="btn btn-primary mb-2 rb-form" rb-data-url-base="' + BOB.getUrlFilter($ob) + '" rb-data-url="/" rb-callback-before="badmin_callbackFilterBefore" rb-callback-after="badmin_callbackFilterAfter" rb-ref-id="rb-admin-form-filter"><i class="fa fa-search"></i></button></div>');
        $form.append($div);
        $ob.html('');
        $ob.append($form);
        BADMIN.reInit();
        if (typeof (window['__reinit_filterform']) === 'function') {
            var fnb = window['__reinit_filterform'];
            fnb();
        }
    },
    renderLimit: function ($ob) {
        var $selectLimit = $('<select class="form-control custom-select mt-10 col-md-6 col-sm-12" id="' + BJGRID.idSelectLimit + '"></select>');
        var limits = BOB.getLimit($ob).split(',');
        for (var i = 0; i < limits.length; i++) {
            $selectLimit.append('<option value="' + limits[i] + '">' + limits[i] + '</option>');
        }
        $selectLimit.val(limits[0] + '');
        BJGRID.limit = limits[0];
        $ob.html('').append('<label for="' + BJGRID.idSelectLimit + '" class="col-md-6 col-sm-12 mt-20">' + BOB.getLimitText($ob) + ' </label').append($selectLimit);
        BADMIN.reInit();
    },
    renderPaginator: function (data, $ob) {
        let $nav = $('<nav class="pagination-wrap d-inline-block mt-15" aria-label="HK"></nav>');
        let $ul = $('<ul class="pagination custom-pagination pagination-rounded pagination-filled" id="' + BJGRID.idUlPaginator + '"></ul>');
        $ul.append('<li class="page-item ' + (data.data.page <= 1 ? 'disabled' : '') + '"><a class="page-link" rb-data-page="' + (data.data.page - 1 == 0 ? 1 : data.data.page - 1) + '" href="' + BCOMMON.VOID + '"><i class="ion ion-ios-arrow-round-back"></i></a></li>');
        let length = Math.ceil(parseInt(BOB.getPaginatorLength($ob), 10) / 2);
        let from = data.data.page - length < 1 ? 1 : data.data.page - length;
        let to = data.data.page + length > data.data.total_page ? data.data.total_page : data.data.page + length;
        if (from > 1) {
            $ul.append('<li class="page-item"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="1">1</li>');
            if (from > 2) {
                $ul.append('<li class="page-item"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="' + (from - 1) + '">...</li>');
            }
        }
        for (var i = from; i <= to; i++) {
            $ul.append('<li class="page-item ' + (data.data.page == i ? 'active' : '') + '"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="' + i + '">' + i + '</a></li>');
        }
        if (to < data.data.total_page) {
            if (to < data.data.total_page - 1) {
                $ul.append('<li class="page-item"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="' + (to + 1) + '">...</a></li>');
            }
            $ul.append('<li class="page-item"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="' + data.data.total_page + '">' + data.data.total_page + '</a></li>');
        }
        $ul.append('<li class="page-item ' + (data.data.page >= data.data.total_page ? 'disabled' : '') + '"><a class="page-link" href="' + BCOMMON.VOID + '" rb-data-page="' + (data.data.page + 1) + '"><i class="ion ion-ios-arrow-round-forward"></i></a></li>');

        $ob.html('');
        $nav.append($ul);
        $ob.append($nav);
        BADMIN.reInit();
    },
    renderTable: function (data, $ob) {
        let $div = $('<div class="col-sm"></div>');
        let $divTableWrap = $('<div class="table-wrap"></div>');
        let $divTableReponse = $('<div class="table-responsive"></div>');
        let $table = $('<table class="table table-bordered table-hover table-striped mb-0"></table>');
        let $thead = $('<thead class="thead-active">');
        let $tbody = $('<tbody></tbody>');

        $table.append($thead);
        $table.append($tbody);
        $div.append($divTableWrap.append($divTableReponse.append($table)));
        $ob.html('');
        $ob.append($div);

        let str = '<tr>';
        str += '<th width="20px"><div class="custom-control custom-checkbox checkbox-primary"><input type="checkbox" class="custom-control-input" id="' + BJGRID.idCheckBoxAll + '"/><label for="' + BJGRID.idCheckBoxAll + '" class="custom-control-label"></label></div></th>';
        for (var i = 0; i < data.columns.length; i++) {
            var col = data.columns[i];
            str += '<th width="' + (col.width) + '">' + col.text + '</th>'
        }
        str += '</str>';
        $thead.append(str);

        for (var i = 0; i < data.data.items.length; i++) {
            var item = data.data.items[i];
            str = '<tr>';
            str += '<td><div class="custom-control custom-checkbox checkbox-primary"><input type="checkbox" class="custom-control-input ' + BJGRID.classCheckBoxListId + '" id="' + BJGRID.idCheckBoxAll + '-' + item.id + '" rb-data-id="' + item.id + '"/><label for="' + BJGRID.idCheckBoxAll + '-' + item.id + '" class="custom-control-label"></label></div></td>';
            for (var j = 0; j < data.columns.length; j++) {
                var col = data.columns[j];
                if (col.name == 'action' && (!item[col.name] || item[col.name] == 'undefined')) {
                    str += '<td>';
                    for (var k = 0; k < data.actions.length; k++) {
                        var action = data.actions[k];
                        str += ' <a href="' + BCOMMON.VOID + '" class="' + action.class + '" title="' + action.text + '" ' + action.attr + ' rb-data-url="' + action.url + '" rb-data-id="' + item.id + '" rb-data-method="' + action.method + '"><i class="font-18 ' + action.icon + '"></i></a> '
                    }
                    str += '</td>';
                    continue;
                }
                if (col.name == 'isPublished') {
                    if (item.isPublished != 'undefined') {
                        str += '<td><a rb-callback-after="badmin_reload" title="' + BOB.getIsPublishedText($ob) + '" href="' + BCOMMON.VOID + '" class="rb-reinit-action" rb-data-id="' + item.id + '" rb-data-url="' + (BOB.getUrlBase($ob) + '/publish') + '" rb-data-method="POST">'
                            + '<div class="toggle toggle-sm toggle-simple toggle-light toggle-bg-primary admin-toggle-' + (item.isPublished ? 'on' : 'off') + '" style="height: 20px; width: 35px;">'
                            + '<div class="toggle-slide">'
                            + '<div class="toggle-inner" style="width: 50px; margin-left: 0px;">'
                            + '<div class="toggle-on active" style="height: 20px; width: 25px; text-indent: -6.66667px; line-height: 20px;"></div>'
                            + '<div class="toggle-blob" style="height: 20px; width: 20px; margin-left: -10px;"></div>'
                            + '<div class="toggle-off" style="height: 20px; width: 25px; margin-left: -10px; text-indent: 6.66667px; line-height: 20px;"></div>'
                            + '</div></div></div></a></td>';
                    }
                    continue;
                }
                str += '<td>' + item[col.name] + '</td>'
            }
            str += '</str>';
            $tbody.append(str);
        }
        BCOMMON.reInit();
        //BFORM.reInit();
        BADMIN.reInit();
        if (typeof (window['__reinit_rendertable']) === 'function') {
            var fnb = window['__reinit_rendertable'];
            fnb(data);
        }
    }
}