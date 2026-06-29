import $ from 'jquery';

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
        $.ajax({
            url: '/product/alergen',
            type: 'POST',
            data: { product_id: productId, alergen_id: alergid },
            dataType: 'json',
            success: function () {},
        });
        // Toggle "active" visual state. We use opacity so the SVG/PNG icons stay readable.
        this.classList.toggle('opacity-30');
        this.setAttribute('aria-pressed', this.classList.contains('opacity-30') ? 'false' : 'true');
    });
});
