import $ from 'jquery';
import { updateBasketBadge } from './cart-total.js';

/**
 * Order page: product list, add-ons modal, cart animation.
 * Loaded by main.js (Tailwind shop layout).
 */
function showAddOnModal() {
    const $m = $('#selectAddOnModal');
    $m.removeClass('hidden').addClass('flex');
    $('body').addClass('overflow-hidden');
}

function hideAddOnModal() {
    const $m = $('#selectAddOnModal');
    $m.addClass('hidden').removeClass('flex');
    $('body').removeClass('overflow-hidden');
}

function initOrderPage() {
    if (!$('#products-grid').length) {
        return;
    }

    $('#addOnProductsList').on('click', '.productAddonRow', function () {
        const id = $(this).data('product-id');
        const price = $(this).data('price');
        if (id != null && price != null) {
            window.addOnProduct(String(id), price);
        }
    });

    $('.add-to-cart').on('click', function () {
        $('#overlay').show();
        const cart = $('#ordertotal');
        const $card = $(this).closest('.product-card');
        const imgtodrag = $card.find('[data-product-image]');
        if (imgtodrag.length) {
            window.moveImage(imgtodrag, cart);
            $('#overlay').fadeOut();
            $('#basketLink').addClass('glowbutton');
            $('#basketLink').one(
                'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                function () {
                    $(this).removeClass('glowbutton');
                }
            );
        } else {
            $('#overlay').fadeOut();
        }
    });

    $('[data-product-image].img-drag').on('click', function () {
        $('#overlay').show();
        const cart = $('#ordertotal');
        const imgtodrag = $(this);
        if (imgtodrag.length) {
            window.moveImage(imgtodrag, cart);
            $('#overlay').fadeOut();
        } else {
            $('#overlay').fadeOut();
        }
    });

    $(document).on('click', '[data-close-addon-modal]', function () {
        hideAddOnModal();
    });
}

$(function () {
    initOrderPage();
    $('#overlay').fadeOut();
});

window.addProduct = function (productID) {
    $.ajax({
        url: '/order/addproduct/' + productID,
        type: 'POST',
        dataType: 'json',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        success: function (data) {
            if (!data || typeof data !== 'object') {
                return;
            }
            const adonnproducts = data.addOns || [];

            if (adonnproducts.length > 0) {
                const $list = $('#addOnProductsList');
                $list.empty();
                $.each(adonnproducts, function (_index, value) {
                    const priceVal = Number(value[2]);
                    $list.append(
                        '<button type="button" class="productAddonRow flex w-full items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-left hover:bg-slate-50" ' +
                            'data-product-id="' +
                            String(value[0]) +
                            '" data-price="' +
                            String(priceVal) +
                            '">' +
                            '<img src="/dbimage/' +
                            value[0] +
                            '.png" width="40" height="40" class="h-10 w-10 shrink-0 rounded object-cover" alt="" />' +
                            '<span class="flex-1 text-sm font-medium text-slate-800">' +
                            (value[1] || '') +
                            '</span>' +
                            '<span class="text-sm font-semibold text-slate-900">' +
                            priceVal.toFixed(2) +
                            '€</span>' +
                            '</button>'
                    );
                });
                showAddOnModal();
            }
            if (data.total != null) {
                updateBasketBadge(data.total, data.lineCount);
            }
        },
    });
};

window.addOnProduct = function (addOnProductID, price) {
    $.ajax({
        url: '/order/addAddonProduct',
        type: 'POST',
        data: { product_id: addOnProductID, price: price, _token: $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        success: function (data) {
            hideAddOnModal();
            if (data && data.total != null) {
                updateBasketBadge(data.total, data.lineCount);
            }
        },
    });
};

window.cancelProduct = function (productID) {
    jQuery.ajax({
        url: '/order/cancelproduct/' + productID,
        type: 'POST',
        dataType: 'json',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        success: function (data) {
            if (data && data.total != null) {
                updateBasketBadge(data.total, data.lineCount);
            }
        },
    });
};

window.moveImage = function (imgtodrag, cart) {
    const $img = $(imgtodrag);
    const $cart = $(cart);
    if (!$img.length || !$cart.length) {
        return;
    }
    const imgOff = $img.offset();
    const cartOff = $cart.offset();
    if (!imgOff || !cartOff) {
        return;
    }

    const imgclone = $img
        .clone()
        .offset({
            top: imgOff.top,
            left: imgOff.left,
        })
        .css({
            opacity: '0.5',
            position: 'absolute',
            height: '150px',
            width: '150px',
            'z-index': '100',
        })
        .appendTo($('body'))
        .animate(
            {
                top: cartOff.top + 10,
                left: cartOff.left + 10,
                width: 75,
                height: 75,
            },
            1000
        );

    imgclone.animate(
        {
            width: 0,
            height: 0,
        },
        function () {
            $(this).detach();
        }
    );
};
