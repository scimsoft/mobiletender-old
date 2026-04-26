import $ from 'jquery';

$(function () {
    if (!$('#admin-pay-basket').length) {
        return;
    }
    $('#subTotalRow').hide();
    $('#admin-pay-basket input.box').on('change', function () {
        $('#subTotalRow').show();
        let total = 0;
        $('#admin-pay-basket .box:checked').each(function () {
            const text = $(this).closest('tr').find('.amount').text().replace(/[^\d,.-]/g, '').replace(',', '.');
            total += parseFloat(text) || 0;
        });
        $('#subTotal').text(
            total.toLocaleString('es-ES', {
                style: 'currency',
                currency: 'EUR',
            })
        );
    });
});
