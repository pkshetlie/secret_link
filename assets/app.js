/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import 'bootstrap'

const Swal = require('sweetalert2/dist/sweetalert2.js');

$(function(){
    $(".alert").each(function(){
        Swal.fire({
            title: $(this).data('title'),
            html: $(this).html(),
            icon: $(this).data('type') === "danger"?"error":$(this).data('type'),
            confirmButtonText: 'close'
        });
    });
});

function copyToClipboard(str) {
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.body.removeChild(el);
}

$(document).on('click', ".copy-clipboard", function () {
    let t = $(this);
    let title = t.attr('original-title');
    navigator.clipboard.writeText($(this).data('to-copy'));

    // copyToClipboard($(this).data('to-copy'));
   Swal.fire($(this).data('copy-title'),
       $(this).data('copy-text'),
       'success')

    return false;
});