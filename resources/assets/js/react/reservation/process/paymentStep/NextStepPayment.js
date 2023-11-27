import React from 'react'
import StoreReservation from "../StoreReservation";
import ConfirmBuyStep from "../steps/ConfirmBuyStep";

export default class NextStepPayment extends React.Component {
    constructor() {
        super();
        this.state = {
            errors: null
        }
    }

    /**
     *
     */
    getConfig() {
        return this.props.config;
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
     * @returns {{canGoNextStep: boolean}}
     */
    static get defaultProps() {
        return {
            canGoNextStep: false,
            card: null,
            payment: null,
            references: null,
            showFormNewCard: null,
            config: null,
        }
    }

    /**
     * Go to Payment methods
     */
    goToBuySystem() {
        let user = StoreReservation.get('user');
        let lang = StoreReservation.get('lang');

        let paymentInfo = {
            card: {
                id: this.props.card.id
            },
            sourceCard: true,
        };
        let payment = this.props.payment;
        if (this.userHasCompleteForm()) {
            //El usuario completo el formulario
            if (this.showAdressInput(user)) {
                //Solo si el formulario se hace presente añadimos la info del usuario en el request
                let phone = this.refs.phone ? this.refs.phone.value : null;
                let address = this.refs.address ? this.refs.address.value : null;
                let external_number = this.refs.external_number ? this.refs.external_number.value : null;
                let internal_number = this.refs.internal_number ? this.refs.internal_number.value : null;
                let postal_code = this.refs.postal_code ? this.refs.postal_code.value : null;
                let country = this.refs.countries_id ? this.refs.countries_id.value : null;

                paymentInfo.phone = phone;
                paymentInfo.address = address;
                paymentInfo.external_number = external_number;
                paymentInfo.internal_number = internal_number;
                paymentInfo.postal_code = postal_code;
                paymentInfo.countries_id = country;
            }
            StoreReservation.setPaymentInfo(payment, paymentInfo, () => {
                StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep');
            });
        } else {
            //Error por falta de info
            this.setState({
                errors: lang['conekta.confirmInfo.error']
            })
        }
    }

    /**
     *
     * @returns {boolean}
     */
    userHasCompleteForm() {
        let user = StoreReservation.get('user');
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();

        if (this.showAdressInput(user)) {

            let phone = this.refs.phone ? this.refs.phone.value : null;
            let address = this.refs.address ? this.refs.address.value : null;
            let external_number = this.refs.external_number ? this.refs.external_number.value : null;
            // let internal_number = this.refs.internal_number? this.refs.internal_number.value : null;
            let postal_code = this.refs.postal_code ? this.refs.postal_code.value : null;
            let country = this.refs.countries_id ? this.refs.countries_id.value : null;

            if (isOnlyForVirtualProducts) {
                //Solo importa telefono
                if (!phone) {
                    return false;
                }
            } else {
                //Importa telefono y campos de shipping
                if (
                    !address ||
                    !phone ||
                    !external_number ||
                    // !internal_number ||
                    !postal_code ||
                    !country
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     *
     * @param user
     * @returns {boolean}
     */
    showAdressInput(user) {
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();
        let showFormNewCard = this.props.showFormNewCard;
        let cardSelected = this.props.card;

        if (
            showFormNewCard
            ||
            !cardSelected
        ) {
            return false;
        }
        if (isOnlyForVirtualProducts) {
            //Solo importa telefono
            return !user.phone;
        }
        //Importa telefono y campos de shipping
        return !user.phone
            || !user.address
            || !user.external_number
            // || !user.internal_number
            || !user.postal_code
            || !user.countries_id
            ;
    }

    /**
     *
     * @returns {*}
     */
    render() {
        if (!this.props.canGoNextStep) {
            return null;
        }
        let lang = StoreReservation.get('lang');
        let errors = this.state.errors;
        let user = StoreReservation.get('user');
        let showAdressInput = this.showAdressInput(user);
        let countries = StoreReservation.get('countries');
        let isOnlyForVirtualProducts = this.isOnlyForVirtualProducts();

        return (
            <div>
                {
                    showAdressInput ?
                        (
                            <div className="PaymentSelection--Conekta PaymentSelection--extra">
                                <div>
                                    {lang['conekta.confirmInfo']}
                                </div>
                                {
                                    !isOnlyForVirtualProducts ?
                                        (
                                            <div className="Conekta--noVirtual">
                                                <div className="Conekta--address">
                                                    <label>
                                                        <span>{lang['conekta.address']}</span>
                                                        <input type="text" defaultValue={user.address} ref="address"/>
                                                    </label>
                                                </div>
                                                <div className="Conekta--external_number">
                                                    <label>
                                                        <span>{lang['conekta.external_number']}</span>
                                                        <input type="text" defaultValue={user.external_number}
                                                               ref="external_number"/>
                                                    </label>
                                                </div>
                                                <div className="Conekta--internal_number">
                                                    <label>
                                                        <span>{lang['conekta.internal_number']}</span>
                                                        <input type="text" defaultValue={user.internal_number}
                                                               ref="internal_number"/>
                                                    </label>
                                                </div>

                                                <div className="Conekta--postal_code">
                                                    <label>
                                                        <span>{lang['conekta.postal_code']}</span>
                                                        <input type="text" defaultValue={user.postal_code}
                                                               ref="postal_code"/>
                                                    </label>
                                                </div>
                                                <div className="Conekta--countries_id">
                                                    <label>
                                                        <span>{lang['conekta.countries_id']}</span>
                                                        <select ref="countries_id" defaultValue={user.countries_id}>
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
                                        :
                                        null
                                }

                                <div className="Conekta--phone">
                                    <label>
                                        <span>{lang['conekta.phone']}</span>
                                        <input type="tel" defaultValue={user.phone} ref="phone"/>
                                    </label>
                                </div>
                            </div>
                        )
                        :
                        null
                }
                <div>
                    <div>
                        <button className="gs-checkOut" type="button"
                                onClick={this.goToBuySystem.bind(this)}>
                            {lang['goToBuySystem']}
                        </button>
                        {
                            errors ?
                                (
                                    <div className="" ref="">{this.state.errors}</div>
                                )
                                :
                                null
                        }
                    </div>
                </div>
            </div>
        );
    }
}
