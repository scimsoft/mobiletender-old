import $ from 'jquery';

function loadPayPalSdk(clientId) {
    if (window.paypal) {
        return Promise.resolve();
    }
    return new Promise((resolve, reject) => {
        const s = document.createElement('script');
        s.src = `https://www.paypal.com/sdk/js?client-id=${encodeURIComponent(clientId)}&currency=EUR`;
        s.async = true;
        s.onload = () => resolve();
        s.onerror = () => reject(new Error('PayPal SDK load failed'));
        document.head.appendChild(s);
    });
}

$(function () {
    const el = document.getElementById('checkout-page-config');
    if (!el) {
        return;
    }
    let cfg;
    try {
        cfg = JSON.parse(el.textContent);
    } catch (e) {
        return;
    }

    const ticketId = cfg.ticketId;

    $('.add-to-cart').on('click', function () {
        $('#overlay').show();
    });
    $('.eatInButton').on('click', function () {
        $('#eatinrow').slideToggle('slow');
    });

    $('#pagarEfectivo').on('click', function () {
        window.location.href = '/checkout/printOrderEfectivo/' + ticketId;
    });
    $('#apuntarEnLaMesa').on('click', function () {
        window.location.href = '/checkout/printOrder/' + ticketId;
    });
    $('#pagarOnline').on('click', function () {
        window.location.href = '/checkout/pay';
    });
    $('#pagarTarjeta').on('click', function () {
        window.location.href = '/checkout/printOrderTarjeta/' + ticketId;
    });

    $('#eatin').on('click', function () {
        $('#scan-qr-instructions').slideToggle('slow');
    });
    $('#takeaway').on('click', function () {
        $('#div-takeaway').slideToggle('slow');
        window.location.href = '/checkout/pickup';
    });

    $('#paypal-button-container').hide();

    if (!cfg.paypalPrepay || !cfg.paypalClientId) {
        return;
    }

    loadPayPalSdk(String(cfg.paypalClientId)).then(function () {
        if (!window.paypal) {
            return;
        }
        window.paypal
            .Buttons({
                createOrder: function (_data, actions) {
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: String(cfg.newLinesTotal),
                                },
                            },
                        ],
                    });
                },
                onApprove: function (_data, actions) {
                    return actions.order.capture().then(function () {
                        $('#overlay').fadeOut();
                        if (cfg.hasTableNumber) {
                            window.location.href = '/checkout/printOrder/' + ticketId;
                        } else {
                            const tn = $('#table_number').val() || '';
                            window.location.href = '/checkout/confirmForTable/' + tn;
                        }
                    });
                },
                onCancel: function () {
                    $('#overlay').fadeOut();
                },
            })
            .render('#paypal-button-container');
    });
});
