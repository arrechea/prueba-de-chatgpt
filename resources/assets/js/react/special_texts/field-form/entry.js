import React from 'react'
import ReactDOM from 'react-dom'
import AppFieldForm from './AppFieldForm';
import StorageField from "./StorageField";

let lang = [];
let field = [];
let field_types = [];
let options = [];

try {
    let lang_text = [] = $('#CatalogFieldLang').text();
    lang = lang_text !== '' ? JSON.parse(lang_text) : null;
} catch (e) {
    console.error('lang:\n' + e)
}

try {
    let field_text = [] = $('#CatalogField').text();
    field = field_text !== '' ? JSON.parse(field_text) : null;
} catch (e) {
    console.error('field:\n' + e)
}

try {
    let field_types_text = [] = $('#CatalogFieldTypes').text();
    field_types = field_types_text !== '' ? JSON.parse(field_types_text) : null;
} catch (e) {
    console.error('field types:\n' + e)
}

try {
    let options_text = [] = $('#CatalogFieldOptions').text();
    options = options_text !== '' ? JSON.parse(options_text) : [];
} catch (e) {
    console.error('field options:\n' + e)
}

StorageField.lang = lang;
StorageField.field = field;
StorageField.types = field_types;
StorageField.options = options;

ReactDOM.render(<AppFieldForm/>, document.getElementById('catalog-fields-form'));
