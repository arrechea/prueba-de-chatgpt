import React from 'react'
import ReactDOM from 'react-dom'
import AppGenerateMap from "./AppGenerateMap";
import StoreMapGenerator from "./StoreMapGenerator";
import $ from 'jquery'

let elementoDOM = $('#AppGenerateMap');
let lang = [];
let location_positions = [];
let initial_room_image = elementoDOM.find('.AppGenerateMap--image_background').text();
let initial_map = null;
let urlForm = elementoDOM.find('.AppGenerateMap--urlForm').text();
let initial_name = elementoDOM.find('.AppGenerateMap--initial_name');
let initial_active = elementoDOM.find('.AppGenerateMap--initial_active');

try {
    let langText = elementoDOM.find('.AppGenerateMap--lang').text();
    lang = langText !== '' ? JSON.parse(langText) : null;
} catch (e) {
    console.error('lang:\n', e)
}
try {
    let location_positionsText = elementoDOM.find('.AppGenerateMap--location_positions').text();
    location_positions = location_positionsText !== '' ? JSON.parse(location_positionsText) : null;
} catch (e) {
    console.error('lang:\n', e)
}
try {
    let initial_mapText = elementoDOM.find('.AppGenerateMap--initial_map').text();
    initial_map = initial_mapText !== '' ? JSON.parse(initial_mapText) : null;
} catch (e) {
    console.error('lang:\n', e)
}

StoreMapGenerator.lang = lang;
StoreMapGenerator.urlForm = urlForm;
StoreMapGenerator.initial_room_image = initial_room_image;
StoreMapGenerator.room_image = initial_room_image;
StoreMapGenerator.setInitialsPositions(location_positions, initial_map);
if (initial_name.length) {
    StoreMapGenerator.set('room_title', initial_name.text());
}

if (initial_active.length) {
    StoreMapGenerator.set('room_active', Boolean(initial_active.text()));
} else {
    StoreMapGenerator.set('room_active', false)
}

ReactDOM.render(<AppGenerateMap/>, document.getElementById('AppGenerateMap'));
