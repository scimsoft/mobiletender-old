import $ from 'jquery';

function ticketNav(id) {
    return '/checkout/printOrder/' + id;
}
function ticketEfectivo(id) {
    return '/checkout/printOrderEfectivo/' + id;
}
function ticketTarjeta(id) {
    return '/checkout/printOrderTarjeta/' + id;
}

$(function () {
    if (!$('#shop-basket-page').length) {
        return;
    }
    $('#doCheckout').on('click', function () {
        window.location.href = '/checkout/';
    });

    const $root = $('#shop-basket-page');
    const ticketId = $root.data('ticket-id');

    $(document).on('click', '[data-basket-action="pagar-efectivo"]', function () {
        if (ticketId) {
            window.location.href = ticketEfectivo(ticketId);
        }
    });
    $(document).on('click', '[data-basket-action="apuntar-mesa"]', function () {
        if (ticketId) {
            window.location.href = ticketNav(ticketId);
        }
    });
    $(document).on('click', '[data-basket-action="pagar-online"]', function () {
        window.location.href = '/checkout/pay';
    });
    $('#PAGADO').on('click', function () {
        window.location.href = '/checkout/payed';
    });
    $(document).on('click', '[data-basket-action="pagar-tarjeta"]', function () {
        if (ticketId) {
            window.location.href = ticketTarjeta(ticketId);
        }
    });
    $(document).on('click', '[data-basket-action="eatin-toggle"]', function () {
        $('#scan-qr-instructions').slideToggle('slow');
    });
    $(document).on('click', '[data-basket-action="takeaway"]', function () {
        $('#div-takeaway').slideToggle('slow');
        window.location.href = '/checkout/pickup';
    });
});
