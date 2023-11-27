import React from 'react'
import StoreReservation from "../StoreReservation";
import PaymentHandle from "./PaymentHandle";
import BuySystemStep from "../steps/BuySystemStep";


const {PaypalPayment} = window.GafaPayElements;


/**
 * Keys cuenta wisquimas test
 *
 * public: 'ARg7Pcp8rzYCpjlU4_ninRdMXI0NkgQ1vLa8mbDlH7f6HP3APEnw9aSQChNGhHbPSt5U4AHmiPF3YXC-',
 * private: 'EDvmzUBu09NUX0HHTFKnMJdDyvgk5IR7zBSPunA9fbH09PoTyRLhlj4UV1C8gHuZRCEvTQhkL6CppBOz'
 */
export default class Paypal extends PaymentHandle {


    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return true;
    }

    componentDidMount() {
        let component = this;
        let lang = StoreReservation.get('lang');

        StoreReservation.set('confirmPaymentButton', (
            <div className="AppReservation--steps">
                {/*<button className="AppReservation--button AppReservation--button--next" type="button"*/}
                {/*onClick={component.goToBuySystem.bind(component)}>*/}
                {/*{lang['goToBuySystem']}*/}
                {/*</button>*/}
            </div>
        ));
    }

    handleGafaPayErrAction({err, message}) {
        console.log('Conekta.handleGafaPayErrAction', err);

        // alert(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.');
        // Materialize.toast(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.', 4000);

    }

    handleGafaPaySuccessAction({message}) {
        console.log('onGafaPaySuccessAction', message);
        StoreReservation.payment_data = message;
        // Materialize.toast(message, 4000);
        StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep');
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let termsAndConditions = StoreReservation.isCheckedTerms();
        let products = StoreReservation.get('product', []);
        let discount = StoreReservation.getDiscountAmount();
        let unitaryDiscount = discount > 0 ? StoreReservation.divition(discount, products.length) : 0;

        const lineItems = products.map(item => {
            return {
                name: item.name,
                unitPrice: parseFloat(item.price_final) - unitaryDiscount,
                quantity: item.amount
            }
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');
        return (
            <div className="PaymentSelection--Gafapay">
                <PaypalPayment
                    order={{
                        customerName: `${first_name} ${last_name}`,
                        customerEmail: email,
                        customerPhone: phone,
                        lineItems
                    }}
                    onStartPayAction={() => console.log('onStartPayAction')}
                    onGafaPaySuccessAction={this.handleGafaPaySuccessAction}
                    onGafaPayErrAction={this.handleGafaPayErrAction}
                    termsAndConditions={termsAndConditions}
                />
            </div>
        )
    }
}
