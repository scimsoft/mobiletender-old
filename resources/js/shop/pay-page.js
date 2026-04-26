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
    const el = document.getElementById('pay-page-config');
    if (!el) {
        return;
    }
    let cfg;
    try {
        cfg = JSON.parse(el.textContent);
    } catch (e) {
        return;
    }

    if (!cfg.paypalClientId || !document.getElementById('paypal-button-container')) {
        return;
    }

    loadPayPalSdk(cfg.paypalClientId).then(function () {
        if (!window.paypal) {
            return;
        }
        window.paypal
            .Buttons({
                onClick: function (data, actions) {
                    if (data.fundingSource !== 'card') {
                        $('#overlay').fadeIn();
                    }
                },
                createOrder: function (_data, actions) {
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: String(cfg.amount),
                                },
                            },
                        ],
                    });
                },
                onApprove: function (_data, actions) {
                    return actions.order.capture().then(function () {
                        $('#overlay').fadeOut();
                        window.location.href = cfg.onApproveUrl;
                    });
                },
                onCancel: function () {
                    $('#overlay').fadeOut();
                },
            })
            .render('#paypal-button-container');
    });
});
