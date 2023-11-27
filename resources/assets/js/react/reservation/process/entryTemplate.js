import React from 'react'
import ReactDOM from 'react-dom'
import AppReservationTemplate from "./AppReservationTemplate";
import StoreReservation from "./StoreReservation";
import StoreReservationPublicGafafit from "./StoreReservationPublicGafafit";
import $ from 'jquery'

//Inicio de Store
let elementoDOM = $('#CreateReservationFancyTemplate');
let user = null;
let user_Credits = null;
let user_ValidCredits = null;
let user_ValidMembership = null;
let user_waivers = null;
let location = null;
let currency = null;
let admin = null;
let meeting = null;
let meeting_neccesaryCredits = null;
let combo = null;
let membership = null;
let product = null;
let products = null;
let combosSelection = null;
let membershipSelection = null;
let payment_types = null;
let payment_info_userProfile = null;
let countries = null;
let lang = [];
let images = null;
let recurrent_payment = null;
let subscription_payment_types = null;
let requestMap = [];
let tokenMovil = elementoDOM.find('.CreateReservationFancy--tokenMovil').text();
let default_store_tab = null;

try {
    let userText = elementoDOM.find('.CreateReservationFancy--user').text();
    user = userText !== '' ? JSON.parse(userText) : null;
} catch (e) {
    console.error('user:\n', e)
}
try {
    let user_CreditsText = elementoDOM.find('.CreateReservationFancy--user_Credits').text();
    user_Credits = user_CreditsText !== '' ? JSON.parse(user_CreditsText) : null;
} catch (e) {
    console.error('user_Credits:\n', e)
}
try {
    let user_ValidCreditsText = elementoDOM.find('.CreateReservationFancy--user_ValidCredits').text();
    user_ValidCredits = user_ValidCreditsText !== '' ? JSON.parse(user_ValidCreditsText) : null;
} catch (e) {
    console.error('user_ValidCredits:\n', e)
}
try {
    let user_ValidMembershipText = elementoDOM.find('.CreateReservationFancy--user_ValidMembership').text();
    user_ValidMembership = user_ValidMembershipText !== '' ? JSON.parse(user_ValidMembershipText) : null;
} catch (e) {
    console.error('user_ValidMembership:\n', e)
}
try {
    let user_waiversText = elementoDOM.find('.CreateReservationFancy--user_waivers').text();
    user_waivers = user_waiversText !== '' ? JSON.parse(user_waiversText) : null;
} catch (e) {
    console.error('user_ValidMembership:\n', e)
}
try {
    let locationText = elementoDOM.find('.CreateReservationFancy--location').text();
    location = locationText !== '' ? JSON.parse(locationText) : null;
} catch (e) {
    console.error('location:\n', e)
}
try {
    let currencyText = elementoDOM.find('.CreateReservationFancy--currency').text();
    currency = currencyText !== '' ? JSON.parse(currencyText) : null;
} catch (e) {
    console.error('currency:\n', e)
}
try {
    let adminText = elementoDOM.find('.CreateReservationFancy--admin').text();
    admin = adminText !== '' ? JSON.parse(adminText) : null;
} catch (e) {
    console.error('admin:\n', e)
}
try {
    let meetingText = elementoDOM.find('.CreateReservationFancy--meeting').text();
    meeting = meetingText !== '' ? JSON.parse(meetingText) : null;
} catch (e) {
    console.error('meeting:\n', e)
}
try {
    let meeting_neccesaryCreditsText = elementoDOM.find('.CreateReservationFancy--meeting_neccesaryCredits').text();
    meeting_neccesaryCredits = meeting_neccesaryCreditsText !== '' ? JSON.parse(meeting_neccesaryCreditsText) : null;
} catch (e) {
    console.error('meeting_neccesaryCredits:\n', e)
}
try {
    let comboText = elementoDOM.find('.CreateReservationFancy--combo').text();
    combo = comboText !== '' ? JSON.parse(comboText) : null;
} catch (e) {
    console.error('combo:\n', e)
}
try {
    let productText = elementoDOM.find('.CreateReservationFancy--product').text();
    product = productText !== '' ? JSON.parse(productText) : null;
} catch (e) {
    console.error('product:\n', e)
}
try {
    let membershipText = elementoDOM.find('.CreateReservationFancy--membership').text();
    membership = membershipText !== '' ? JSON.parse(membershipText) : null;
} catch (e) {
    console.error('membership:\n', e)
}
try {
    let combosSelectionText = elementoDOM.find('.CreateReservationFancy--combosSelection').text();
    combosSelection = combosSelectionText !== '' ? JSON.parse(combosSelectionText) : null;
} catch (e) {
    console.error('combosSelection:\n', e)
}
try {
    let membershipSelectionText = elementoDOM.find('.CreateReservationFancy--membershipSelection').text();
    membershipSelection = membershipSelectionText !== '' ? JSON.parse(membershipSelectionText) : null;
} catch (e) {
    console.error('membershipSelection:\n', e)
}
try {
    let payment_typesText = elementoDOM.find('.CreateReservationFancy--payment_types').text();
    payment_types = payment_typesText !== '' ? JSON.parse(payment_typesText) : null;
    // console.log('payment_types', payment_types)
} catch (e) {
    console.error('payment_types:\n', e)
}
try {
    let payment_info_userProfileText = elementoDOM.find('.CreateReservationFancy--payment_info_userProfile').text();
    payment_info_userProfile = payment_info_userProfileText !== '' ? JSON.parse(payment_info_userProfileText) : null;
} catch (e) {
    console.error('payment_types:\n', e)
}
try {
    let countriesText = elementoDOM.find('.CreateReservationFancy--countries').text();
    countries = countriesText !== '' ? JSON.parse(countriesText) : null;
} catch (e) {
    console.error('payment_types:\n', e)
}
try {
    let langText = elementoDOM.find('.CreateReservationFancy--lang').text();
    lang = langText !== '' ? JSON.parse(langText) : null;
} catch (e) {
    console.error('lang:\n', e)
}
try {
    let imagesText = elementoDOM.find('.CreateReservationFancy--images').text();
    images = imagesText !== '' ? JSON.parse(imagesText) : null;
} catch (e) {
    console.error('images:\n', e)
}
try {
    let recurrent_payment_text = elementoDOM.find('.CreateReservationFancy--recurrent_payment').text();
    recurrent_payment = recurrent_payment_text !== '' ? JSON.parse(recurrent_payment_text) : null;
} catch (e) {
    console.error('recurrent_payment:\n', e)
}
try {
    let subscripation_payment_types_text = elementoDOM.find('.CreateReservationFancy--subscribable_payment_types').text();
    subscription_payment_types = subscripation_payment_types_text !== '' ? JSON.parse(subscripation_payment_types_text) : null;
} catch (e) {
    console.error('subscription_payment_types:\n', e)
}
try {
    let requestMapText = elementoDOM.find('.CreateReservationFancy--requestMap').text();
    requestMap = requestMapText !== '' ? JSON.parse(requestMapText) : null;
} catch (e) {
    console.error('user:\n', e)
}

let bearer = elementoDOM.find('.CreateReservationFancy--bearer').text();

StoreReservation.user = user;
StoreReservation.user_Credits = user_Credits;
StoreReservation.user_ValidCredits = user_ValidCredits;
StoreReservation.user_ValidMembership = user_ValidMembership;
StoreReservation.user_waivers = user_waivers;
StoreReservation.location = location;
StoreReservation.currency = currency;
StoreReservation.admin = admin;
StoreReservation.meeting = meeting;
StoreReservation.meeting_neccesaryCredits = meeting_neccesaryCredits;
StoreReservation.combo = combo;
StoreReservation.product = product;
StoreReservation.products = product;
StoreReservation.membership = membership;
StoreReservation.cart = [];
if (combo) {
    combo.amount = 1;
    combo.type = 'combo';
    StoreReservation.cart.push(combo);
}
if (membership) {
    membership.amount = 1;
    membership.type = 'membership';
    StoreReservation.cart.push(membership);
}
StoreReservation.combosSelection = combosSelection;
StoreReservation.membershipSelection = membershipSelection;
StoreReservation.paymentSelection = payment_types;
StoreReservation.payment_info_userProfile = payment_info_userProfile;
StoreReservation.countries = countries;
StoreReservation.csrf = elementoDOM.find('.CreateReservationFancy--csrf').text();
StoreReservation.urlReservation = elementoDOM.find('.CreateReservationFancy--urlReservation').text();
StoreReservation.urlGenerateCode = elementoDOM.find('.CreateReservationFancy--urlGenerateCode').text();
StoreReservation.urlCheckGiftCode = elementoDOM.find('.CreateReservationFancy--urlCheckGiftCode').text();
StoreReservation.urlCheckDiscountCode = elementoDOM.find('.CreateReservationFancy--urlCheckDiscountCode').text();
StoreReservation.lang = lang;
StoreReservation.images = images;
StoreReservation.bearer = bearer;
StoreReservation.recurrent_payment = recurrent_payment;
StoreReservation.subscription_payment_types = subscription_payment_types;
StoreReservation.requestMap = requestMap;
StoreReservation.tokenMovil = tokenMovil;
StoreReservation.default_store_tab = elementoDOM.find('.CreateReservationFancy--default_store_tab').text()

//Acceso publico
StoreReservationPublicGafafit.init();

//Renderizado
ReactDOM.render(<AppReservationTemplate/>, document.getElementById('CreateReservationFancyTemplate'));
