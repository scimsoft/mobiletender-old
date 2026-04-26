import $ from 'jquery';

$(function () {
    if (!$('#admin-products-index').length) {
        return;
    }
    $('#admin-products-index input.catalog-toggle').on('change', function () {
        $('#overlay').show();
        const product_id = $(this).closest('tr').attr('id');
        $.ajax({
            type: 'POST',
            url: '/products/catalog',
            data: { product_id: product_id },
            success: function () {
                $('#overlay').fadeOut();
            },
        });
    });
});
