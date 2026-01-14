import './bootstrap';
import 'preline';
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";

window.Toastify = Toastify;

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

window.flatpickr = flatpickr;

document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
});