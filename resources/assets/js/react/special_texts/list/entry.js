import React from 'react'
import ReactDOM from 'react-dom'
import AppSpecialTexts from './AppSpecialTexts';
import StorageSpecialTexts from "./StorageSpecialText";

let url = window.SpecialTexts.urls.create;
let csrf = $('input[name="_token"]').val();

let lang = [];
try {
    let lang_text = [] = $('#SpecialTextsLang').text();
    lang = lang_text !== '' ? JSON.parse(lang_text) : null;
} catch (e) {
    console.error('lang:\n' + e)
}

StorageSpecialTexts.texts = window.SpecialTexts.texts;
StorageSpecialTexts.url = url;
StorageSpecialTexts.csrf = csrf;
StorageSpecialTexts.lang=lang;
StorageSpecialTexts.implement=$('#special-texts').data('implement');

ReactDOM.render(<AppSpecialTexts/>, document.getElementById('special-texts'));
