import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";
import BuySystemStep from "../steps/BuySystemStep";


const {ConektaPayment} = window.GafaPayElements;


export default class Conekta extends PaymentHandle {

    /**
     *
     */
    constructor() {
        super();
        this.state = {
            disabled: false,
            button: null
        };
    }

    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return true;
    }


    componentDidMount() {
        StoreReservation.set('confirmPaymentButton', this.botonCompraCorrecto());
    }

    botonCompraCorrecto() {
        let lang = StoreReservation.get('lang');

        return (
            <div className="AppReservation--steps">
                <button className="gs-checkOut" type="button"
                        onClick={() => window._handleConektaPayment()}>
                    {lang['goToBuySystem']}
                </button>
            </div>
        )
    }

    handleStartPayAction() {
        StoreReservation.isProcessing = true;
        let lang = StoreReservation.get('lang');

        StoreReservation.set('confirmPaymentButton', (
            <div className="AppReservation--steps">
                <button className="gs-checkOut is-loading" type="button">
                    {lang['goToBuySystem']}
                </button>
            </div>
        ));
    }

    handleGafaPayErrAction({err, message}) {
        // console.log('Conekta.handleGafaPayErrAction', err);
        alert(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.');
        StoreReservation.isProcessing = false;
        StoreReservation.set('confirmPaymentButton', this.botonCompraCorrecto());
    }

    handleGafaPaySuccessAction({subscriptionId, recurringPayment, message}) {
        StoreReservation.subscribe = recurringPayment;
        StoreReservation.set_payment = recurringPayment;
        StoreReservation.payment_data = message;
        StoreReservation.isProcessing = false;
        StoreReservation.setStep(<BuySystemStep subscriptionId={subscriptionId}/>, 'BuySystemStep');
    }

    divition() {

    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let products = StoreReservation.get('product', []);
        let discount = StoreReservation.getDiscountAmount();
        let unitaryDiscount = discount > 0 ? StoreReservation.divition(discount, products.length) : 0;

        const lineItems = products.map(item => {
            return {
                name: item.name,
                unitPrice: parseFloat(item.price_final) - unitaryDiscount,
                quantity: item.amount,
                product_type: item.product_type,
                product_id: item.id
            }
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');
        // console.log(this.props);
        return (
            <div className="PaymentSelection--Gafapay">
                <ConektaPayment
                    order={{
                        customerName: `${first_name} ${last_name}`,
                        customerEmail: email,
                        customerPhone: phone,
                        lineItems
                    }}
                    onStartPayAction={this.handleStartPayAction}
                    onGafaPaySuccessAction={this.handleGafaPaySuccessAction}
                    onGafaPayErrAction={this.handleGafaPayErrAction.bind(this)}
                    termsAndConditions={this.props.termsAndConditions}
                    hasRecurringPayment={this.props.hasRecurringPayment}
                    paymentFrequency={this.props.paymentFrequency}
                    generalData={this.props.generalData}
                    changePaymentSystemProperties={this.props.changePaymentSystemProperties}
                />
            </div>
        )
    }
}
