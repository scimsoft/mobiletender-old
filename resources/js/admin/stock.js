import $ from 'jquery';

$(function () {
    if (!$('#admin-stock-index').length) {
        return;
    }
    $('button[name=addbutton]').on('click', function () {
        $('#overlay').show();
        const product_id = $(this).closest('tr').attr('id');
        const unitstextbox = $(this).closest('tr').find('input[name=newunits]');
        const currentunitstextbox = $(this).closest('tr').find('input[name=currentunits]');
        const currentunitstextboxvalue = currentunitstextbox.val();
        const units = unitstextbox.val();

        $.ajax({
            type: 'POST',
            url: '/stock/add',
            data: {
                product_id: product_id,
                units: units,
            },
            success: function () {
                $('#overlay').fadeOut();
                unitstextbox.val('');
                currentunitstextbox.val(Number(units) + Number(currentunitstextboxvalue));
            },
        });
    });
});
