import React from 'react'
import ReactDom from 'react-dom'
import AppSecretInput from './AppSecretInput';

let lang = [];
let url = '';
let secret = '';
let client_id = '';

try {
    let lang_text = [] = $('#SecretLang').text();
    lang = lang_text !== '' ? JSON.parse(lang_text) : null;
} catch (e) {
    console.error('lang:\n' + e);
}

try {
    url = $('#SecretKeyUrl').val();
} catch (e) {
    console.error('url:\n' + e);
}

try {
    secret = $('#SecretKey').val();
} catch (e) {
    console.error('secret:\n' + e);
}

try {
    client_id = $('#SecretClientID').val();
} catch (e) {
    console.error('client id:\n' + e);
}

ReactDom.render(<AppSecretInput lang={lang} url={url} secret={secret}
                                client_id={client_id}/>, document.getElementById('client-secret-key'));
