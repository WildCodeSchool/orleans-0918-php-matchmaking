/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
require('../css/app.scss');
require('../css/event_list.scss');
require('../css/event_manager.scss');
require('../css/event_players.scss');
require('@fortawesome/fontawesome-free/js/all.js');


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');
require('bootstrap');


// Resolve bug custom-file-input, not able to see which file are selected in a input file type
$(document).on('change', '.custom-file-input', function () {
    let fileName = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
    $(this).parent('.custom-file').find('.custom-file-label').text(fileName);
});

import('./updateForm');
import('./presence');
import toastr from 'toastr';
window.toastr = toastr;
import './../../node_modules/toastr/build/toastr.css';