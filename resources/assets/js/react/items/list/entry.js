import React from 'react'
import ReactDOM from 'react-dom'
import $ from 'jquery'
import AppItemList from "./AppItemList";
import StoreItemList from "./StoreItemList";

let elementoDOM = $('#CreateItemList');

let categories = null;
let products = null;
let lang = null;

try {
    let categoriesText = elementoDOM.find('.CreateItemList--categories').text();
    categories = categoriesText !== '' ? JSON.parse(categoriesText) : null;
} catch (e) {
    console.error('categories:\n', e)
}

try {
    let productsText = elementoDOM.find('.CreateItemList--products').text();
    products = productsText !== '' ? JSON.parse(productsText) : null;
} catch (e) {
    console.error('products:\n', e)
}

try {
    let langText = elementoDOM.find('.CreateItemList--lang').text();
    lang = langText !== '' ? JSON.parse(langText) : null;
} catch (e) {
    console.error('lang:\n', e)
}

StoreItemList.set('categories', categories);
StoreItemList.set('products', products);
StoreItemList.set('lang', lang);
StoreItemList.set('csrf', elementoDOM.find('.CreateItemList--csrf').text());
StoreItemList.set('category_url', elementoDOM.find('.CreateItemList--categoryUrl').text());
StoreItemList.set('category_new_url', elementoDOM.find('.CreateItemList--categoryNewUrl').text());
StoreItemList.set('category_delete_url', elementoDOM.find('.CreateItemList--categoryDeleteUrl').text());
StoreItemList.set('product_url', elementoDOM.find('.CreateItemList--productUrl').text());
StoreItemList.set('product_new_url', elementoDOM.find('.CreateItemList--productNewUrl').text());
StoreItemList.set('product_delete_url', elementoDOM.find('.CreateItemList--productDeleteUrl').text());
StoreItemList.set('product_list_url', elementoDOM.find('.CreateItemList--productListUrl').text());
if (categories.length) {
    StoreItemList.set('selected_category', categories[0])
}

ReactDOM.render(<AppItemList/>, document.getElementById('item-management-list'));
