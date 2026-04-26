import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap.bundle.js';

function addOnProduct(productId, addOnProductID, price) {
    $.ajax({
        url: '/addOnProduct/add',
        type: 'POST',
        data: { product_id: productId, adon_product_id: addOnProductID, price: price },
        dataType: 'json',
        success: function () {},
    });
}

function removeaddOnProduct(productId, addOnProductID) {
    $.ajax({
        url: '/addOnProduct/remove',
        type: 'POST',
        data: { product_id: productId, adon_product_id: addOnProductID },
        dataType: 'json',
        success: function () {},
    });
}

$(function () {
    const $form = $('#product-edit-form');
    if (!$form.length) {
        return;
    }
    const productId = $form.attr('data-product-id');

    $('#products_list').on('change', function () {
        const price = prompt('Price ?', '0');
        const productID = $(this).find(':selected').val();
        const addOnProductID = $(this).find(':selected').text();
        $('#addon_products_list').append($('<option>', { value: productID, text: addOnProductID }));
        addOnProduct(productId, productID, price);
    });

    $('#addon_products_list').on('change', function () {
        const productID = $(this).find(':selected').val();
        $(this).find(':selected').remove();
        removeaddOnProduct(productId, productID);
    });

    $('#category_addon').on('change', function () {
        const categoryid = $(this).find(':selected').val();
        $.ajax({
            url: '/products/list/' + categoryid,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const $el = $('#products_list');
                $el.empty();
                $.each(data, function (_id, name) {
                    $el.append($('<option></option>').attr('value', name.id).text(name.name));
                });
            },
        });
    });

    $('.toggleAlergen').on('click', function () {
        const alergid = this.id;
        const opacity = this.style;
        $.ajax({
            url: '/product/alergen',
            type: 'POST',
            data: { product_id: productId, alergen_id: alergid },
            dataType: 'json',
            success: function () {},
        });
        if (opacity.opacity === '1') {
            opacity.opacity = '0.3';
        } else {
            opacity.opacity = '1';
        }
    });

    if (typeof $().popover === 'function') {
        $('[data-toggle="popover"]').popover();
    }
});
