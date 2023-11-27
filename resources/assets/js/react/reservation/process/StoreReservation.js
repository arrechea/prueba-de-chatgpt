import React from 'react';
import BuySystemStep from "./steps/BuySystemStep";
import $ from 'jquery';

const StoreReservation = {
    //Listeners
    listeners: [],
    segmentedListeners: [],
    //User
    user: null,
    user_Credits: null,
    user_ValidCredits: null,
    user_ValidMembership: null,
    user_waivers: null,
    signature: null,
    //Info
    location: null,
    currency: null,
    admin: null,
    //meeting
    meeting: null,
    meeting_data: null,
    meeting_neccesaryCredits: null,
    map_objectsSelected: [],
    invited_data: {},
    //combos
    combo: null,
    combosSelection: null,
    //products
    product: null,
    products: null,
    //memberships
    membership: null,
    membershipSelection: null,
    step: null,
    step_name: null,
    back: null,
    back_name: null,
    step_log: [],
    step_names: [],
    //extras
    csrf: null,
    urlReservation: null,
    urlGenerateCode: null,
    urlCheckGiftCode: null,
    urlCheckDiscountCode: null,
    lang: null,
    images: null,
    //payment
    payment: null,
    payment_data: null,
    paymentSelection: null,
    payment_info_userProfile: null,
    bearer: null,
    confirmPaymentButton: null,
    //conekta
    conektaCards: [],
    //cart
    cart: [],
    //giftcard
    isGiftCard: false,
    isManualGiftCode: false,
    giftCode: '',
    isCorrectGiftCardCode: false,
    isPendingToCheckGiftCardCode: false,
    //discountCode
    hasDiscountCode: false,
    discountCode: '',
    discountCodeObject: '',
    isValidDiscountCode: false,//this is the most important!
    isPendingToCheckDiscountCode: false,
    recurrent_payment: null,
    isSubscribable: false,
    isRecurrent: false,
    isProcessing: false,
    canSubscribe: false,
    subscribe: false,
    set_payment: false,
    subscription_payment_types: [],
    requestMap: [],
    tokenMovil: '',
    payment_slug: null,
    //termsConditions
    areCheckedTermsEnabled: false,
    checkedTerms: false,
    default_store_tab: null,
    /**
     *
     * @param data
     * @param first
     * @param cb
     */
    loguearConInfo(data, first, cb) {
        this.user = data.user;
        this.user_Credits = data.user_Credits;
        this.user_ValidMembership = data.user_ValidMembership;
        this.user_waivers = data.user_waivers;
        this.currency = data.currency;
        this.meeting = data.meeting;
        this.meeting_neccesaryCredits = data.meeting_neccesaryCredits;
        this.combo = data.combo;
        this.product = data.product;
        this.products = data.product;
        this.membership = data.membership;

        if (first) {
            if (data.combo) {
                data.combo.amount = 1;
                data.combo.type = 'combo';
                this.cart.push(data.combo);
            }
            if (data.membership) {
                data.membership.amount = 1;
                data.membership.type = 'membership';
                this.cart.push(data.membership);
            }
        }

        this.combosSelection = data.combosSelection;
        this.membershipSelection = data.membershipSelection;
        this.paymentSelection = data.payment_types;
        this.payment_info_userProfile = data.payment_info_userProfile;
        this.countries = data.countries;
        this.lang = data.langFile;
        this.images = data.images;
        this.bearer = data.bearer;
        this.subscription_payment_types = data.subscription_payment_types;
        this.requestMap = data.requestMap;
        this.tokenMovil = data.tokenMovil;

        this.payment_info_userProfile = data.payment_info_userProfile;
        this.urlReservation = data.urlReservation;
        this.urlGenerateCode = data.urlGenerateCode;
        this.urlCheckGiftCode = data.urlCheckGiftCode;
        this.urlCheckDiscountCode = data.urlCheckDiscountCode;
        this.recurrent_payment = data.recurrent_payment;
        if (cb) {
            cb();
        }
    },
    /**
     *
     * @returns {Array}
     */
    getRequestMap() {
        return this.requestMap;
    },
    /**
     *
     * @returns {string}
     */
    getTokenMovil() {
        return this.tokenMovil;
    },
    /**
     *
     * @returns {null|*|company|{}}
     */
    getCompany() {
        return this.location.company;
    },
    /**
     *
     * @returns {null|brand|{}|t|*}
     */
    getBrand() {
        return this.location.brand;
    },
    /**
     *
     * @returns {null}
     */
    getClientId() {
        let company = this.getCompany();
        return !!company ? company.client.id : null;
    },
    /**
     *
     * @returns {null}
     */
    getClientSecret() {
        let company = this.getCompany();
        return !!company ? company.client.secret : null;
    },
    /**
     *
     * @param cart
     * @returns {boolean}
     */
    isCartSubscribable(cart = null) {
        let cartItems = cart ? cart : this.cart;

        if (cartItems.length === 1 && cartItems[0].subscribable == 1) {
            return true;
        } else {
            return false;
        }
    },
    /**
     *
     * @returns {*|boolean}
     */
    isUserWantSubscribe() {
        return StoreReservation.get('subscribe') && StoreReservation.isPosibleToSubscribe();
    },
    /**
     *
     * @returns {*|boolean}
     */
    isPosibleToSubscribe() {
        return StoreReservation.isCartSubscribable() && StoreReservation.get('canSubscribe');
    },
    /**
     *
     * @returns {boolean}
     */
    hasMoreThanOneReservation() {
        return this.numberOfReservations() > 1;
    },
    /**
     *
     * @returns {*}
     */
    numberOfReservations() {
        let map_objectsSelected = this.getReservationsSelected();
        return map_objectsSelected.length;
    },
    /**
     *
     * @returns {Array}
     */
    getReservationsSelected() {
        let map_objectsSelected = this.map_objectsSelected;
        return Array.isArray(map_objectsSelected) ? map_objectsSelected : [];
    },
    /**
     *
     * @returns {string}
     */
    getDiscountCode() {
        let {
            isValidDiscountCode,
            discountCodeObject,
            hasDiscountCode
        } = StoreReservation;

        if (isValidDiscountCode && discountCodeObject && hasDiscountCode) {
            return discountCodeObject;
        }
    },
    /**
     *
     * @param total
     */
    discountInTotalForDiscountCode(total) {
        let discountCode = StoreReservation.getDiscountCode();
        if (discountCode) {
            let type = discountCode.discount_type;
            let discountNumber = discountCode.discount_number;
            if (!isNaN(discountNumber)) {
                let discountValue = parseFloat(discountNumber);

                switch (type) {
                    case 'price':
                        return discountValue;
                        break;
                    case 'percent':
                        return (discountValue * total) / 100;
                        break;
                }
            }
        }
        return 0;
    },
    /**
     * Set discount code
     * @param discountObject
     */
    applyDiscountCode(discountObject) {
        StoreReservation.isPendingToCheckDiscountCode = false;
        StoreReservation.discountCodeObject = discountObject;

        StoreReservation.set('isValidDiscountCode', true, () => {
            if (StoreReservation.getTotalAmount() <= 0) {
                StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep');
            }
        });
    },
    /**
     *
     */
    resetGiftCardOptions() {
        StoreReservation.isGiftCard = false;
        StoreReservation.isManualGiftCode = false;
        StoreReservation.isCorrectGiftCardCode = false;
        StoreReservation.isPendingToCheckGiftCardCode = false;
        StoreReservation.set('giftCode', '');
    },
    /**
     *
     * @returns {boolean}
     */
    hasMeeting() {
        return !!StoreReservation.meeting;
    },

    /**
     *
     * @returns {boolean}
     */
    hasTerms() {
        let location = StoreReservation.location;
        let link = location.brand.terms_conditions_link;
        return link !== '' && !!link;
    },

    /**
     *
     * @returns {boolean}
     */
    changeTerms() {
        let checkedTerms = StoreReservation.checkedTerms;
        return !checkedTerms;
    },

    /**
     *
     * @returns {boolean}
     */
    isGiftCardCorrect() {
        let isGiftCardEnabled = StoreReservation.isGiftCard;
        if (
            isGiftCardEnabled === true
            &&
            (
                StoreReservation.isCorrectGiftCardCode !== true
                ||
                StoreReservation.isPendingToCheckGiftCardCode === true
            )
        ) {
            return false;
        }
        return true;
    },

    /**
     *
     * @returns {boolean}
     */
    isCheckedTerms() {
        let brandTermsLink = this.brandHasTermLink();
        if (brandTermsLink) {
            return StoreReservation.checkedTerms === true;
        } else {
            return true;
        }
    },
    /**
     *
     * @returns {string}
     */
    getBrandTermsLink() {
        let location = StoreReservation.get('location');
        return location.brand.terms_conditions_link;
    },
    /**
     *
     * @returns {boolean}
     */
    brandHasTermLink() {
        let brandTermsLink = this.getBrandTermsLink();
        return !!brandTermsLink && brandTermsLink !== '';
    },

    /**
     *
     * @returns {*}
     */
    getHeaders() {
        return !!this.admin ?
            {} :
            {
                'GAFAFIT-COMPANY': this.location.companies_id,
                'Authorization': this.bearer
            };
    },
    /**
     *
     * @param payment
     * @param paymentData
     * @param cb
     */
    setPaymentInfo(payment, paymentData, cb) {
        StoreReservation.payment = payment;
        StoreReservation.payment_data = paymentData;
        if (cb) {
            cb()
        }
    },
    /**
     *
     */
    initPayments() {
        StoreReservation.payment = StoreReservation.paymentSelection && StoreReservation.paymentSelection.length ?
            StoreReservation.paymentSelection[0] :
            null;
        StoreReservation.payment_data = null;
        StoreReservation.payment_slug = StoreReservation.payment ? StoreReservation.payment.slug : null;
        if (StoreReservation.payment_slug && StoreReservation.canPaymentSubscribe(StoreReservation.payment_slug)) {
            StoreReservation.canSubscribe = true;
        }
    },
    /**
     * Check if we can subscribe to a payment
     * @param slug
     * @returns {boolean}
     */
    canPaymentSubscribe(slug) {
        let subscribable_payments = StoreReservation.get('subscription_payment_types');
        let subscribable_slugs = subscribable_payments.filter(function (payment) {
            return payment === slug;
        });

        return Boolean(subscribable_slugs.length);
    },
    /**
     * Reset Payment Info
     * @param cb
     */
    clearPayment(cb) {
        StoreReservation.payment = null;
        StoreReservation.payment_data = null;
        StoreReservation.confirmPaymentButton = null;
        this.TriggerChange(cb, 'payment');
    },
    /**
     * Calcula el total a pagar
     */
    getTotalAmount(withoutDiscountCode) {
        let total = 0;
        if (this.cart) {
            this.cart.forEach(elem => {
                total += elem.amount * elem.price_final;
            });
        }
        if (!withoutDiscountCode) {
            total -= StoreReservation.discountInTotalForDiscountCode(total);
        }
        return total;
    },
    /**
     *
     * @param a
     * @param b
     * @returns {number}
     */
    divition(a, b) {
        let resultado = b > 0 ? a / b : 0;
        return Math.round(resultado * 100) / 100;
    },
    /**
     *
     * @returns {number}
     */
    getDiscountAmount() {
        let total = StoreReservation.getTotalAmount(true);
        let subtotal = 0;

        this.cart.forEach(elem => {
            subtotal += parseFloat(elem.price_final) * parseFloat(elem.amount);
        });
        let discount = total - subtotal;
        discount += StoreReservation.discountInTotalForDiscountCode(total);

        if (discount < 0) {
            discount = 0;
        }

        return discount;
    },
    /**
     *
     * @returns {{total: *, items: Array}}
     */
    getCart(lang) {
        let items = this.cart;

        return {
            total: StoreReservation.getTotalAmount(),
            discount: StoreReservation.getDiscountAmount(),
            items: items,
        }
    },
    /**
     *
     * @param step
     * @param step_name
     * @param skip
     * @param back
     * @returns {null}
     */
    setStep(step = null, step_name = null, skip = false, back = false) {
        let current_step = StoreReservation.get('step');
        let current_step_name = StoreReservation.get('step_name');
        let prev_back = StoreReservation.get('back');
        let prev_back_name = StoreReservation.get('back_name');
        let step_log = StoreReservation.get('step_log');
        let step_names = StoreReservation.get('step_names');
        let next_back = null;
        let next_step = null;
        let next_step_name = null;
        let nex_back_name = null;
        if (!back) {
            if (step_log.length) {
                let last_step = step_names[step_names.length - 1];
                if (last_step !== step_name) {
                    next_step = step;
                    next_step_name = step_name;
                    if (skip) {
                        next_back = prev_back;
                        nex_back_name = prev_back_name
                    } else {
                        step_log.push(current_step);
                        step_names.push(current_step_name);
                        next_back = current_step;
                        nex_back_name = current_step_name;
                    }
                } else {
                    return null;
                }
            } else {
                next_step = step;
                next_step_name = step_name;
                if (skip) {
                    next_back = prev_back;
                    nex_back_name = prev_back_name;
                } else {
                    step_log.push(current_step);
                    step_names.push(current_step_name);
                    next_back = current_step;
                    nex_back_name = current_step_name;
                }
            }
        } else {
            if (!prev_back) {
                $('#CreateReservationFancyTemplate--Close').trigger('click')
            }
            next_step = prev_back;
            next_step_name = prev_back_name;
            if (step_log.length) {
                if (prev_back) {
                    let previous_index = step_names.findIndex(function (element) {
                        return element === prev_back_name;
                    });
                    if (previous_index >= 1) {
                        next_back = step_log[previous_index - 1];
                        nex_back_name = step_names[previous_index - 1];
                    } else {
                        next_back = null;
                        nex_back_name = null;
                    }
                } else {
                    next_back = null;
                    nex_back_name = null;
                }

                step_log.splice(step_log.length - 1, 1);
                step_names.splice(step_names.length - 1, 1);
            } else {
                next_back = null;
                nex_back_name = null;
            }

        }

        StoreReservation.set('step', next_step);
        StoreReservation.set('step_name', next_step_name);
        StoreReservation.set('step_log', step_log);
        StoreReservation.set('step_names', step_names);
        StoreReservation.set('back', next_back);
        StoreReservation.set('back_name', nex_back_name);
    },
    getPositionsSelecteds() {
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');
        if (Array.isArray(map_objectsSelected)) {
            return map_objectsSelected.length;
        }
        return 1;
    },
    /**
     *
     * @param combo
     * @param neccesaryCredits
     * @param user_Credits
     * @returns {boolean}
     */
    comboIsFiltered(combo, neccesaryCredits, user_Credits) {

        if (combo && neccesaryCredits && neccesaryCredits.length) {
            //Datos que entrega
            let comboCreditId = combo.credits_id;
            let comboCredits = combo.credits;

            //Buscamos creditos necesarios
            let creditosNecesarios = neccesaryCredits.length;
            let creditoComparable = neccesaryCredits.filter(function (credito) {
                return credito.id === comboCreditId;
            }).slice().shift();

            if (creditoComparable && creditoComparable.services && creditoComparable.services.length) {
                //Hay datos de servicio
                let servicio = creditoComparable.services.slice().shift();
                creditosNecesarios = parseInt(servicio.pivot.credits) * StoreReservation.getPositionsSelecteds();
            }

            //Buscamos creditos que tenga el usuario
            let creditosUser = user_Credits.filter(function (creditoUser) {
                return creditoUser.credits_id === comboCreditId;
            }).length;

            let esValido = (creditosUser + comboCredits) >= creditosNecesarios;

            if (!esValido) {
                //No se alcanza la cantidad necesaria
                return true;
            }
        }
        return false;
    },
    /**
     *
     * @param property
     * @param value
     * @param cb
     */
    set(property, value, cb) {
        this[property] = value ? value : null;
        this.TriggerChange(cb, property);
    },
    /**
     * Change Cart
     * @param cart
     * @param cb
     */
    changeCart(cart, cb) {
        StoreReservation.cart = cart;
        this.TriggerChange(cb, 'cart');
    },
    /**
     * Change Combo
     * @param combo
     * @param cb
     */
    changeCombo(combo, cb) {
        let currentCombo = StoreReservation.get('combo');
        if (combo && currentCombo && combo.id === currentCombo.id) {
            combo = null;
        }

        StoreReservation.combo = combo;
        StoreReservation.membership = null;
        this.TriggerChange(cb, 'combo');
    },
    /**
     *
     * @param membership
     * @param cb
     */
    changeMembership(membership, cb) {
        let currentMembership = StoreReservation.get('membership');
        if (membership && currentMembership && membership.id === currentMembership.id) {
            membership = null;
        }

        StoreReservation.combo = null;
        StoreReservation.membership = membership;
        this.TriggerChange(cb, 'membership');
    },
    /**
     *
     * @param number
     * @param c
     * @param d
     * @param t
     * @returns {string}
     */
    moneyFormat(number, c, d, t) {
        var n = number,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    },
    /**
     *
     * @returns {boolean|boolean}
     */
    isGeustInfoRequired() {
        var brand = StoreReservation.getBrand();
        var extra_fields = brand.extra_fields;
        if (extra_fields && typeof extra_fields === 'object') {
            return !!extra_fields['require_guest_info'] && extra_fields['require_guest_info'] === 'on';
        }

        return false;
    },
    /**
     *
     * @param property
     * @param defaultOptions
     * @returns {*}
     */
    get(property, defaultOptions = null) {
        return !!this[property] ? this[property] : defaultOptions;
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback) {
        this.listeners.push(callback);
    },
    /**
     *
     * @param segment
     * @param callback
     */
    addSegmentedListener(segment, callback) {
        if (segment && callback) {
            if (segment.length) {
                //array
                segment.forEach(function (singleSegment) {
                    StoreReservation.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreReservation.subscribeToSegment(segment, callback)
            }
        }
    },
    /**
     *
     * @param segment
     * @param callback
     */
    subscribeToSegment(segment, callback) {
        if (!this.segmentedListeners.hasOwnProperty(segment)) {
            this.segmentedListeners[segment] = [];
        }
        this.segmentedListeners[segment].push(callback);
    },
    /**
     * Lanza el eventos
     * @constructor
     */
    TriggerChange(cb, segment) {
        //Listeners
        let listeners = this.listeners;
        if (listeners.length) {
            listeners.forEach(function (callback) {
                callback()
            })
        }
        //SegmentedListeners
        let segmentedListeners = this.segmentedListeners;

        if (segmentedListeners) {
            if (segmentedListeners.hasOwnProperty(segment)) {
                segmentedListeners[segment].forEach(function (callback) {
                    callback()
                })
            }
        }
        //Callback
        if (cb) {
            cb();
        }
    },
    // getBrandTermsLink() {
    //     let location = this.location;
    //     return location.brand.terms_conditions_link;
    // }
};

export default StoreReservation;
