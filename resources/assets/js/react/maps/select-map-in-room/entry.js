import React from 'react'
import ReactDOM from 'react-dom'
import AppSelectMapInRoom from "./AppSelectMapInRoom";
import StoreSelectMapInRoom from "./StoreSelectMapInRoom";
import $ from 'jquery'

let elementoDOM = $('#AppSelectMapInRoom');
let lang = [];
let activeMaps = [];
let details = elementoDOM.find('.AppSelectMapInRoom--details').text();
let capacity = parseInt(elementoDOM.find('.AppSelectMapInRoom--capacity').text());
capacity = isNaN(capacity) ? 0 : capacity;
let maps_id = elementoDOM.find('.AppSelectMapInRoom--maps_id').text();

try {
    let langText = elementoDOM.find('.AppSelectMapInRoom--lang').text();
    lang = langText !== '' ? JSON.parse(langText) : null;
} catch (e) {
    console.error('lang:\n', e)
}
try {
    let activeMapsText = elementoDOM.find('.AppSelectMapInRoom--activeMaps').text();
    activeMaps = activeMapsText !== '' ? JSON.parse(activeMapsText) : null;
} catch (e) {
    console.error('lang:\n', e)
}

StoreSelectMapInRoom.lang = lang;
StoreSelectMapInRoom.details = details;
StoreSelectMapInRoom.capacity = capacity;
StoreSelectMapInRoom.activeMaps = activeMaps;
StoreSelectMapInRoom.maps_id = maps_id;

ReactDOM.render(<AppSelectMapInRoom/>, document.getElementById('AppSelectMapInRoom'));
