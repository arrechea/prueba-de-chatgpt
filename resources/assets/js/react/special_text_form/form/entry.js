import React from 'react'
import ReactDOM from 'react-dom'
import StorageSpecialTextForm from './StorageSpecialTextForm';
import AppSpecialTextsForm from './AppSpecialTextsForm';

let brand = null;
let company = null;
let model = null;
let catalog = '';

try {
    let brand_text = [] = $('#BrandModel').text();
    brand = brand_text !== '' ? JSON.parse(brand_text) : null;
} catch (e) {
    console.error('brand:\n' + e)
}
try {
    let company_text = [] = $('#BrandModel').text();
    company = company_text !== '' ? JSON.parse(company_text) : null;
} catch (e) {
    console.error('company:\n' + e)
}
try {
    let model_text = [] = $('#SpecialTextModel').text();
    model = model_text !== '' ? JSON.parse(model_text) : null;
} catch (e) {
    console.error('model:\n' + e)
}
try {
    let catalog_text = [] = $('#SpecialTextModel').text();
    catalog = catalog_text !== '' ? catalog_text : null;
} catch (e) {
    console.error('catalog:\n' + e)
}

StorageSpecialTextForm.company = company;
StorageSpecialTextForm.brand = brand;
StorageSpecialTextForm.model = model;
StorageSpecialTextForm.catalog = catalog;
StorageSpecialTextForm.fields_url = window.SpecialTextForm.urls.fields_url;
StorageSpecialTextForm.values_url = window.SpecialTextForm.urls.values_url;

ReactDOM.render(<AppSpecialTextsForm/>, document.getElementById('special-text-form'));
