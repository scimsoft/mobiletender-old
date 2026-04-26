import $ from 'jquery';

$(function () {
    if (!$('#admin-users-page').length) {
        return;
    }
    $('#admin-users-page .admin-user-type').on('change', function (event) {
        const selected = $(event.target).val().split('.')[1];
        const userID = $(event.target).val().split('.')[0];
        $.ajax({
            url: '/changeusertype/' + userID + '/' + selected,
            type: 'POST',
            dataType: 'json',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {},
        });
    });
});
