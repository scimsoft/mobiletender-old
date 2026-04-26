import $ from 'jquery';

window.$ = window.jQuery = $;

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token.content,
        },
    });
}
