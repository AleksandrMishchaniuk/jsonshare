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
const isEditable = container.dataset.isEditable;
const jsonId = container.dataset.id ? container.dataset.id : null;
const jsonHash = container.dataset.hash ? container.dataset.hash : null;


const options = {
        // modes: ['tree', 'view', 'form', 'code', 'text', 'preview'],
        mode: isEditable ? 'code' : 'preview',
        enableSort: false,
        enableTransform: false,
        history: false
        // , onChange: onChangeJson
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

if (jsonId && jsonHash) {
    let wsUrl = new URL('ws://jsonshare.local');
    wsUrl.port = 3001;
    wsUrl.searchParams.append('id', jsonId);
    wsUrl.searchParams.append('hash', jsonHash);
    const socket = new WebSocket(wsUrl);

    socket.addEventListener('open', function () {
        console.log('CONNECTED');
    });

    socket.addEventListener('message', function (e) {
        console.log(e.data);
    });

    editor.aceEditor.getSession().getDocument().on('change', function (e) {
        console.log(e);
        socket.send(JSON.stringify(e));
    });
}

function onChangeJson() {
    // let range = editor.aceEditor.selection.getRange().start;
    // range.row += 1;
    // range.column -= 5;
    // editor.aceEditor.selection.setRange({
    //     start: range,
    //     end: range
    // });
    // console.log(editor.aceEditor.selection.getRange());
}
