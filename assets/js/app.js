/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'bootstrap/dist/css/bootstrap.css';

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import 'bootstrap';

import 'jsoneditor/dist/jsoneditor.css'
import JSONEditor from 'jsoneditor';


const container = document.getElementById('jsoneditor');
const jsonValue = container.dataset.json ? JSON.parse(container.dataset.json) : {};
const readOnly = container.dataset.readonly ? container.dataset.readonly : 0;

const options = readOnly ?
    {
        modes: ['view', 'preview'],
        mode: 'preview'
    }:{
        modes: ['tree', 'view', 'form', 'code', 'text', 'preview'],
        mode: 'code'
    };
const editor = new JSONEditor(container, options, jsonValue);


$(document).ready(function(){
    $('#create-btn').click(function () {
        let text = editor.getText();
        if (!text) {
            return false;
        }
        let action = $(this).data('action');
        $.post(action, {text: text}, function (data, status) {
            window.location = data;
        })
    });
});
