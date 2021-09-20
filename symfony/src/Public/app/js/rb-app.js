
function __callback_user_login(data) {
    if (BCOMMON.isOk(data)) {
        BALERT.showOk("Login successful !");
        setTimeout(function () { window.location = '/user/profile/'; }, 4000);
        return;
    }
    BALERT.showError(data);
}
var BAPP = {
    init: function () {

    },
    reInit: function () {
        BAPP.loadCart();
    },
    loadCart: function () {
        BAJAX.send('/cart/info', {}, function (data) {
            if (BCOMMON.isOk(data)) {
                $('#rb-cart-counter').text(data.data.carts.length);
                $carts = $('#rb-carts');
                $carts.html('');
                for (var i = 0; i < data.data.carts.length; i++) {
                    $pro = data.data.carts[i];
                    var $p = $('<div class="d-flex px-2"></div>');
                    var $a = $('<a href="' + $pro.url + '" class="thumb" title="' + $pro.name + '"><img src="' + $pro.photo_url + '" alt="' + $pro.name + '"></a>');
                    var $d = $('<div class="info"></div>');
                    $d.append('<div class="tt">' + $pro.name + '</div>')
                        .append('<div class="text-dark">' + $pro.size + '</div>')
                        .append('<div class="tt">' + $pro.price + '</div>');
                    $p.append($a).append($d);
                    $carts.append($p);
                }
                if (data.data.carts.length <= 0) {
                    $('#rb-checkout #rb-checkout-sub').remove();
                    return;
                }
                $('#rb-checkout').append('<div class="text-center px-2" id="rb-checkout-sub"><a href="' + data.data.url_checkout + '" title="' + data.data.checkout + '" class="btn btn-block btn-md btn-primary-custom">' + data.data.checkout + '</a></div>');
                return;
            }
        });
    }
}
$(function () {
    BAPP.init();
    BAPP.reInit();
    $('.open-sidebar').click(function (e) {
        e.preventDefault();
        $('.user-sidebar').addClass('active');
    });
    $('a.rb-language').unbind('click').click(function () {
        BAJAX.send(BOB.getUrl($(this)), { rblang: BOB.getLang($(this)) }, function (data) {
            if (BCOMMON.isOk(data)) {
                window.location.reload();
                return;
            }
            BALERT.showError(data);
        }, 'POST');
        return false;
    });
    $('a.rb-liked').click(function () {
        var $ob = $(this);
        BAJAX.send(BOB.getUrl($ob), { id: BOB.getId($ob) }, function (data) {
            if (BCOMMON.isOk(data)) {
                console.log(data);
                return;
            }
            BALERT.showError(data);
        }, 'POST');
    });
    $('a.rb-add-to-cart').click(function () {
        var $ob = $(this);
        BAJAX.send(BOB.getUrl($ob), { id: BOB.getId($ob), size: BOB.getSize($ob), quantity: 1 }, function (data) {
            if (BCOMMON.isOk(data)) {
                BALERT.showOk(data.data.message);
                BAPP.reInit();
                return;
            }
            BALERT.showError(data);
        }, 'POST');
    });
});
