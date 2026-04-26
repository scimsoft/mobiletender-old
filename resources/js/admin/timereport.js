import $ from 'jquery';

$(function () {
    if (!$('#timereport-page').length) {
        return;
    }
    $('#searchText').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $('#reportTable tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
