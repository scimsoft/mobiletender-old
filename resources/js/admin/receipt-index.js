import $ from 'jquery';

$(function () {
    if (!$('#admin-receipt-index').length) {
        return;
    }
    $('select.receipt-payment-type').on('change', function () {
        $('#overlay').show();
        const receipt = $(this).closest('tr').attr('id');
        $.ajax({
            type: 'POST',
            url: '/changereceiptpaymenttype',
            data: {
                ticket_id: receipt,
                paymenttype: this.value,
            },
            success: function () {
                $('#overlay').fadeOut();
            },
        });
    });
});
