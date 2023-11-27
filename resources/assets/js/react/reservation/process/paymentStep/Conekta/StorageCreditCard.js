const Store = {
    listeners: [],
    url: null,
    csrf: null,
    lang: null,
    implement: null,
    cardNumber: '',
    cardExpMonth: '',
    cardExpYear: '',
    cardCvc: '',

    phone: '',
    address: null,
    cardName: null,
    cardType: null,
    numberLength: '100%',

    getOr(slug, false_value = '') {
        return typeof this[slug] !== 'undefined' && this[slug] !== null && this[slug] !== '' ? this[slug] : false_value;
    }
};

export default Store;
