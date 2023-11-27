import React from 'react'
import ReactDOM from 'react-dom'
import StoreReservation from "../StoreReservation";
import BuySystemStep from "../steps/BuySystemStep";
import PaymentHandle from "./PaymentHandle";

/**
 * Keys cuenta wisquimas test
 *
 * public: 'ARg7Pcp8rzYCpjlU4_ninRdMXI0NkgQ1vLa8mbDlH7f6HP3APEnw9aSQChNGhHbPSt5U4AHmiPF3YXC-',
 * private: 'EDvmzUBu09NUX0HHTFKnMJdDyvgk5IR7zBSPunA9fbH09PoTyRLhlj4UV1C8gHuZRCEvTQhkL6CppBOz'
 */
export default class Paypal extends PaymentHandle {
    /**
     *
     */
    constructor() {
        super();
        this.state = {
            loading: true
        }
    }

    /**
     *
     */
    componentDidMount() {
        this.checkPaypalGlobal();
    }

    /**
     *
     */
    checkPaypalGlobal() {
        let component = this;
        if (!!window.paypal) {
            component.setState({
                loading: false
            }, function () {
                if (!component.props.payment) {
                    return null;
                }

                let client = component.getClient();
                if (client) {
                    let PayPalButton = paypal.Button.driver('react', {React, ReactDOM});

                    //Paypal se mete cuando se renderiza
                    StoreReservation.set('confirmPaymentButton', (
                        <PayPalButton
                            client={client}
                            commit={true}
                            env={component.getEnviroment()}
                            payment={(data, actions) => component.payment(data, actions)}
                            onAuthorize={(data, actions) => component.onAuthorize(data, actions)}
                        />
                    ));
                }
            })
        } else {
            setTimeout(function () {
                component.checkPaypalGlobal();
            }, 1000)
        }
    }

    /**
     *
     * @returns {{sandbox: string, production: string}}
     */
    getClient() {
        let config = this.getConfig();
        let enviroment = this.getEnviroment();

        if (config) {
            let sandbox = config.development_public_api_key;
            let production = config.production_public_api_key;

            if (enviroment === 'sandbox' && !sandbox) {
                return null;
            }
            if (enviroment === 'production' && !production) {
                return null;
            }
            return {
                sandbox: sandbox,
                production: production,
            }
        }

        return null;
    }

    /**
     * Return sandbox or production
     */
    getEnviroment() {
        let config = this.getConfig();
        let response = 'sandbox';

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
     * @param data
     * @param actions
     */
    payment(data, actions) {
        let currency = StoreReservation.get('currency');
        let total = StoreReservation.getTotalAmount();

        return actions.payment.create({
            transactions: [
                {
                    amount: {total: total, currency: currency.code3}
                }
            ]
        });
    }

    /**
     *
     * @param data
     * @param actions
     */
    onAuthorize(data, actions) {
        let payment = this.props.payment;

        return actions.payment.execute().then(function (paymentData) {
            // Show a success page to the buyer
            StoreReservation.setPaymentInfo(payment, paymentData, () => {
                StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep');
            })
        });
    }

    render() {
        let component = this;

        let lang = StoreReservation.get('lang');

        if (component.state.loading) {
            return lang['error.PaypalNotLoad'];
        }

        if (!component.props.payment) {
            return lang['error.NoPayment'];
        }

        let client = component.getClient();
        if (!client) {
            return lang['error.PaypalClient'];
        }

        return (
            <div className="PaymentSelection--Conekta">
                {lang['paypal.text']}
            </div>
        );
    }
}
