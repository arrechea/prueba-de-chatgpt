import React from 'react'
import ReactDOM from 'react-dom'
import AppPlacesSelectors from './AppPlacesSelectors';

let lang = [];
let country_required;
let state_required;
let city_required;
try {
    let lang_text = [] = $('#PlacesLang').text();
    lang = lang_text !== '' ? JSON.parse(lang_text) : null;
} catch (e) {
    console.error('lang:\n' + e)
}

window.Places.lang = lang;
country_required = Boolean($('#countries_id--IsRequired').text());
state_required = Boolean($('#country_states_id--IsRequired').text());
city_required = Boolean($('#city--IsRequired').text());

ReactDOM.render(<AppPlacesSelectors
    country_required={country_required}
    state_required={state_required}
    city_required={city_required}
/>, document.getElementById('places-selectors'));
