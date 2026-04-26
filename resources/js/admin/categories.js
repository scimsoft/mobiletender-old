import $ from 'jquery';

$(function () {
    if (!$('#admin-categories-index').length) {
        return;
    }
    $('#admin-categories-index input.category-active-toggle').on('change', function () {
        $('#overlay').show();
        const category_id = $(this).closest('tr').attr('id');
        $.ajax({
            type: 'POST',
            url: '/categories/toggleactive',
            data: { category_id: category_id },
            success: function () {
                $('#overlay').fadeOut();
            },
        });
    });
});
