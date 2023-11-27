import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";
import BuySystemStep from "../steps/BuySystemStep";


const {StripePayment} = window.GafaPayElements;


export default class Stripe extends PaymentHandle {

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
                        onClick={() => window._handleStripePayment()}>
                    {lang['goToBuySystem']}
                </button>
            </div>
        )
    }

    // handleTest = () => {console.log(test)};
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

    /**
     *
     * @returns {XML}
     */
    render() {

        const handleGafaPayErrAction = ({err, message}) => {
            alert(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.');
            StoreReservation.isProcessing = false;
            StoreReservation.set('confirmPaymentButton', this.botonCompraCorrecto());
        };

        const handleGafaPaySuccessAction = ({subscriptionId, recurringPayment, message}) => {
            StoreReservation.subscribe = recurringPayment;
            StoreReservation.set_payment = recurringPayment;
            StoreReservation.payment_data = message;
            StoreReservation.isProcessing = false;
            // Materialize.toast(message, 4000);
            StoreReservation.setStep(<BuySystemStep subscriptionId={subscriptionId}/>, 'BuySystemStep');
        };

        let products = StoreReservation.get('product', []);
        let discount = StoreReservation.getDiscountAmount();
        let unitaryDiscount = discount > 0 ? StoreReservation.divition(discount, products.length) : 0;

        // let lang = StoreReservation.get('lang');
        const lineItems = products.map(item => {
            return {
                name: item.name,
                unitPrice: parseFloat(item.price_final) - unitaryDiscount,
                quantity: item.amount,
                height: 1,
                length: 1,
                weight: 1,
                width: 1,
                product_type: item.product_type,
                product_id: item.id
            }
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');
        // console.log('gafafit', this.props);
        return (
            <div className="PaymentSelection--Gafapay">
                <StripePayment
                    order={{
                        customerName: `${first_name} ${last_name}`,
                        customerEmail: email,
                        customerPhone: phone,
                        lineItems
                    }}
                    onStartPayAction={this.handleStartPayAction.bind(this)}
                    onGafaPaySuccessAction={handleGafaPaySuccessAction}
                    onGafaPayErrAction={handleGafaPayErrAction}
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
