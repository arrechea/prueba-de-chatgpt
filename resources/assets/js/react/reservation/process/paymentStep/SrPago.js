import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";
import BuySystemStep from "../steps/BuySystemStep";


const {SrpagoPayment} = window.GafaPayElements;


export default class SrPago extends PaymentHandle {

    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return true;
    }

    componentDidMount() {
        // let component = this;
        let lang = StoreReservation.get('lang');

        // console.log('conekta', this.props);

        StoreReservation.set('confirmPaymentButton', (
            <div className="AppReservation--steps">
            </div>
        ));
    }

    // handleTest = () => {console.log(test)};

    /**
     *
     * @returns {XML}
     */
    render() {

        const handleGafaPayErrAction = ({err, message}) => {
            console.log('Conekta.handleGafaPayErrAction', err);
            // alert(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.');
            // Materialize.toast(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.', 4000);
        };


        const handleGafaPaySuccessAction = ({subscriptionId, recurringPayment, message}) => {
            console.log('Srpago.handleGafaPaySuccessAction', message);
            StoreReservation.set('subscribe', recurringPayment);
            StoreReservation.set('set_payment', recurringPayment);

            // Materialize.toast(message, 4000);
            StoreReservation.setStep(<BuySystemStep subscriptionId={subscriptionId}/>, 'BuySystemStep');
        };

        // let lang = StoreReservation.get('lang');
        const lineItems = StoreReservation.get('product', []).map(item => {
            return {
                name: item.name,
                unitPrice: item.price_final,
                quantity: item.amount,
                productType: item.product_type,
                productId: item.id
            }
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');
        return (
            <div className="PaymentSelection--Gafapay">
                <SrpagoPayment
                    order={{
                        customerName: `${first_name} ${last_name}`,
                        customerEmail: email,
                        customerPhone: phone,
                        lineItems
                    }}
                    onStartPayAction={() => console.log('onStartPayAction')}
                    onGafaPaySuccessAction={handleGafaPaySuccessAction}
                    onGafaPayErrAction={handleGafaPayErrAction}
                    termsAndConditions={this.props.termsAndConditions}
                    hasRecurringPayment={this.props.hasRecurringPayment}
                    paymentFrequency={this.props.paymentFrequency}
                    generalData={this.props.generalData}
                />
            </div>
        )
    }
}
