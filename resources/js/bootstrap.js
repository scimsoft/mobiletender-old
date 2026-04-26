import _ from 'lodash';
import axios from 'axios';
import Popper from 'popper.js';
import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap.bundle.js';

window._ = _;

try {
    window.Popper = Popper;
    window.$ = window.jQuery = $;

    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token.content,
            },
        });
    }
} catch (e) {
    alert('no popper');
}

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
