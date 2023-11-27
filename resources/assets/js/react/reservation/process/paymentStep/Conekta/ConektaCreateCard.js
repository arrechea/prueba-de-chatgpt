import React from 'react'
import StoreReservation from "../../StoreReservation";
import $ from 'jquery'
import BuySystemStep from "../../steps/BuySystemStep";
import IconCheckout from "../../ui/iconCheckout";
import StoreageCreditCard from "../../paymentStep/Conekta/StorageCreditCard";

export default class ConektaCreateCard extends React.Component {
    constructor() {
        super();
        this.state = {
            card_type: StoreageCreditCard.getOr('cardType', null),
            length: StoreageCreditCard.getOr('numberLength', '100%')
        };

        this.onChangeStore = this.onChangeStore.bind(this);
        this.printCardImage = this.printCardImage.bind(this);
        this.checkCardNumber = this.checkCardNumber.bind(this);
        this.changeNumber = this.changeNumber.bind(this);
    }

    /**
     *
     */
    getConfig() {
        return this.props.config;
    }

    /**
     *
     * @param token
     */
    conektaSuccessResponseHandler(token) {
        let component = this;
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();
        let payment = component.props.payment;

        let saveCard = $(component.refs.save).is(':checked');
        let phone = component.refs.phone.value;

        let address = isOnlyForVirtualProducts ? null : component.refs.address.value;
        let external_number = isOnlyForVirtualProducts ? null : component.refs.external_number.value;
        let internal_number = isOnlyForVirtualProducts ? null : component.refs.internal_number.value;
        let postal_code = isOnlyForVirtualProducts ? null : component.refs.postal_code.value;
        let country = isOnlyForVirtualProducts ? null : component.refs.countries_id.value;

        let paymentInfo = {
            card: token,
            saveCard: saveCard,
            phone: phone,
            address: address,
            external_number: external_number,
            internal_number: internal_number,
            postal_code: postal_code,
            countries_id: country,
        };
        component.setState({
            loading: false,
        }, () => {
            StoreReservation.setPaymentInfo(payment, paymentInfo, () => {
                StoreReservation.isProcessing = false;
                StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep');
            })
        })
    }

    /**
     *
     * @param response
     */
    conektaErrorResponseHandler(response) {
        let component = this;

        component.setState({
            loading: false,
        }, () => {
            let $errors = $('.Conekta-errors');
            $errors.text(response.message_to_purchaser);
        })
    }

    /**
     *
     * @returns {*}
     */
    getPublicKey() {
        let config = this.getConfig();
        let enviroment = this.getEnviroment();

        if (config) {
            let sandbox = config.development_public_api_key;
            let production = config.production_public_api_key;

            if (enviroment === 'development' && !sandbox) {
                return null;
            }
            if (enviroment === 'production' && !production) {
                return null;
            }

            return enviroment === 'production' ? production : sandbox;
        }

        return null;
    }

    /**
     *
     * @returns {string}
     */
    getEnviroment() {
        let config = this.getConfig();
        let response = 'development';

        if (config) {
            let type = config.type;
            switch (type) {
                case 'production':
                    response = 'production';
                    break;
            }
        }

        return response;
    }

    /**
     *
     * @returns {boolean}
     */
    isOnlyForVirtualProducts() {
        let config = this.getConfig();
        let response = false;

        if (config) {
            let type = config.only_virtual_products;
            switch (type) {
                case 'on':
                    response = true;
                    break;
            }
        }

        return response;
    }

    /**
     *
     */
    createCard() {
        if (StoreReservation.isProcessing) {
            return null;
        }
        let component = this;
        let lang = StoreReservation.get('lang');
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();

        let cardName = component.refs.cardName.value;

        let phone = component.refs.phone.value;

        let address = isOnlyForVirtualProducts ? null : component.refs.address.value;
        let external_number = isOnlyForVirtualProducts ? null : component.refs.external_number.value;
        // let internal_number = isOnlyForVirtualProducts ? null : component.refs.internal_number.value;
        let postal_code = isOnlyForVirtualProducts ? null : component.refs.postal_code.value;
        let country = isOnlyForVirtualProducts ? null : component.refs.countries_id.value;

        let cardNumber = component.refs.cardNumber.value;
        let cardCvc = component.refs.cardCvc.value;
        let cardExpMonth = component.refs.cardExpMonth.value;
        let cardExpYear = component.refs.cardExpYear.value;

        /*
         Principal options
         */
        if (
            !cardName ||
            !phone ||
            !cardNumber ||
            !cardCvc ||
            !cardExpMonth ||
            !cardExpYear
        ) {
            let $errors = $('.Conekta-errors');
            $errors.text(lang['error.conekta.completeForm']);
            StoreReservation.set('isProcessing', false);
            return;
        }
        /*
         No only for virtual products
         */
        if (!isOnlyForVirtualProducts) {
            if (
                !address ||
                !external_number ||
                // !internal_number ||
                !postal_code ||
                !country
            ) {
                let $errors = $('.Conekta-errors');
                $errors.text(lang['error.conekta.completeForm']);
                return;
            }
        }

        let publicKey = component.getPublicKey();
        window.Conekta.setPublicKey(publicKey);

        component.setState({
            loading: true,
        }, () => {
            window.Conekta.Token.create(
                component.refs.form,
                component.conektaSuccessResponseHandler.bind(component),
                component.conektaErrorResponseHandler.bind(component)
            );
        });
    }


    componentWillReceiveProps(nextProps) {
        if (nextProps.show) {
            this.printButton();
        }
    }

    componentDidMount() {
        if (this.props.show) {
            this.printButton();
        }
    }

    printButton() {
        let component = this;
        let lang = StoreReservation.get('lang');

        StoreReservation.set('confirmPaymentButton', (
            <div>
                <div>
                    <button className="gs-checkOut" type="button"
                            onClick={component.createCard.bind(component)}>
                        {lang['goToBuySystem']}
                    </button>
                </div>
                <span className="Conekta-errors"/>
            </div>
        ));
    }

    onChangeStore(slug, e) {
        if (typeof this.refs[slug] !== 'undefined') {
            StoreageCreditCard[slug] = this.refs[slug].value;
        }
    };

    printCardImage() {
        let card_type = this.state.card_type;
        if (card_type !== null) {
            let img = window.CreditCardImages[card_type];
            return (<img src={img} style={{
                width: '35px',
                position: 'absolute',
                bottom: '5px',
                marginLeft: '5px',
                right: 0,
            }}/>);
        } else {
            return '';
        }
    }

    checkCardNumber() {
        let card_type = null;
        let card_number = this.refs.cardNumber;
        if (typeof card_number !== 'undefined') {
            let number = card_number.value;
            if (number !== null && number !== '') {
                let two_digits = parseInt(number.substring(0, 2));
                let one_digit = number.substring(0, 1);
                let length = number.length;
                if (length >= 4) {
                    if ((two_digits === 34 || two_digits === 37) && length <= 15) {
                        card_type = 'american_express';
                    } else if ((two_digits >= 51 && two_digits <= 55) && length <= 16) {
                        card_type = 'master_card';
                    } else if (one_digit === '4' && (length <= 16)) {
                        card_type = 'visa';
                    }
                }
            }
        }

        this.setState({
            card_type: card_type,
            length: card_type !== null ? '86%' : '100%'
        });

        StoreageCreditCard.cardType = card_type;
        StoreageCreditCard.numberLength = card_type !== null ? '86%' : '100%';
    }

    changeNumber(e) {
        this.onChangeStore('cardNumber', e);
        this.checkCardNumber();
    }

    render() {
        if (!this.props.show) {
            return null;
        }
        let lang = StoreReservation.get('lang');
        let user = StoreReservation.get('user');
        let countries = StoreReservation.get('countries');
        let isDevelopment = this.getEnviroment() === 'development';
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();
        let images = StoreReservation.get('images');
        let first_name = user.first_name !== null ? user.first_name : '';
        let last_name = user.last_name !== null ? ' ' + user.last_name : '';

        let cardName = StoreageCreditCard.getOr('cardName', first_name + last_name);
        let address = StoreageCreditCard.getOr('address', user.address);
        let external_number = StoreageCreditCard.getOr('external_number', user.external_number);
        let internal_number = StoreageCreditCard.getOr('internal_number', user.internal_number);
        let postal_code = StoreageCreditCard.getOr('postal_code', user.postal_code);
        let countries_id = StoreageCreditCard.getOr('countries_id', user.countries_id);
        let phone = StoreageCreditCard.getOr('phone', user.phone);

        return (
            <div className={'PaymentSelection--Conekta Conekta__form ' + (isOnlyForVirtualProducts ? 'is-virtual' : '')}
                 ref="form" key="Conekta--form">
                <div className="Conekta--cardName">
                    <label>
                        <span>{lang['conekta.cardName']}</span>
                        <input type="text" size="20" data-conekta="card[name]" ref="cardName"
                               defaultValue={`${cardName}`} onChange={(e) => this.onChangeStore('cardName', e)}/>
                    </label>
                </div>
                <div className="Conekta--cardNumber">
                    <label>
                        <span>{lang['conekta.cardNumber']}</span>
                        <input type="text" size="20" data-conekta="card[number]" ref="cardNumber"
                               defaultValue={StoreageCreditCard.cardNumber}
                               onChange={this.changeNumber}
                               style={{width: this.state.length}}/>
                    </label>
                    {this.printCardImage()}
                </div>
                <div className="Conekta--cardExp">
                    <label className="Conekta--cardExp--month">
                        <span>{lang['conekta.cardExp']}</span>
                    </label>
                    <input type="number" size="2" data-conekta="card[exp_month]" ref="cardExpMonth" min="1"
                           onChange={(e) => this.onChangeStore('cardExpMonth', e)}
                           defaultValue={StoreageCreditCard.cardExpMonth}/>
                    <span>/</span>
                    <input className="Conekta--cardExp--year" type="number" size="4" data-conekta="card[exp_year]"
                           min="1"
                           ref="cardExpYear" onChange={(e) => this.onChangeStore('cardExpYear', e)}
                           defaultValue={StoreageCreditCard.cardExpYear}/>
                </div>
                <div className="Conekta--cardCvc">
                    <label>
                        <span>{lang['conekta.cardCvc']}</span>
                        <input type="number" size="4" data-conekta="card[cvc]" ref="cardCvc" min="1"
                               onChange={(e) => this.onChangeStore('cardCvc', e)}
                               defaultValue={StoreageCreditCard.cardCvc}/>
                    </label>
                </div>
                {
                    isOnlyForVirtualProducts ?
                        '' :
                        (
                            <div className="Conekta--noVirtual">
                                <div className="Conekta--address">
                                    <label>
                                        <span>{lang['conekta.address']}</span>
                                        <input type="text" defaultValue={address} ref="address"
                                               onChange={(e) => this.onChangeStore('address', e)}/>
                                    </label>
                                </div>
                                <div className="Conekta--external_number">
                                    <label>
                                        <span>{lang['conekta.external_number']}</span>
                                        <input type="text" defaultValue={external_number} ref="external_number"
                                               onChange={(e) => this.onChangeStore('external_number', e)}/>
                                    </label>
                                </div>
                                <div className="Conekta--internal_number">
                                    <label>
                                        <span>{lang['conekta.internal_number']}</span>
                                        <input type="text" defaultValue={internal_number} ref="internal_number"
                                               onChange={(e) => this.onChangeStore('internal_number', e)}/>
                                    </label>
                                </div>

                                <div className="Conekta--postal_code">
                                    <label>
                                        <span>{lang['conekta.postal_code']}</span>
                                        <input type="text" defaultValue={postal_code} ref="postal_code"
                                               onChange={(e) => this.onChangeStore('postal_code', e)}/>
                                    </label>
                                </div>

                                <div className="Conekta--countries_id">
                                    <label>
                                        <span>{lang['conekta.countries_id']}</span>
                                        <select ref="countries_id" defaultValue={countries_id}>
                                            {countries.map((country) => {
                                                return (
                                                    <option key={`Conekta--country--${country.id}`}
                                                            value={country.id}>{country.name}</option>
                                                )
                                            })}
                                        </select>
                                    </label>
                                </div>
                            </div>
                        )
                }

                <div className="Conekta--phone">
                    <label>
                        <span>{lang['conekta.phone']}</span>
                        <input type="tel" defaultValue={phone} ref="phone"
                               onChange={(e) => this.onChangeStore('phone', e)}/>
                    </label>
                </div>

                <div className="Conekta--save">
                    <div className="Conekta--save--container">
                        <input id="Conekta--save--card" type="checkbox" name="saveCard" ref="save"/>
                        <span className="PromotionBox--content--checker">
                            <IconCheckout/>
                        </span>
                        <div className="Conekta--save--option">
                            {lang['conekta.yes']}
                            <input type="radio" name="saveCard" ref="save" defaultChecked={true}/>
                        </div>
                        <div className="Conekta--save--option">
                            {lang['conekta.no']}
                            <input type="radio" name="saveCard"/>
                        </div>
                    </div>
                    <label htmlFor="Conekta--save--card">
                        <span>{lang['conekta.saveCard']}</span>
                    </label>
                </div>
            </div>
        )
    }
}
