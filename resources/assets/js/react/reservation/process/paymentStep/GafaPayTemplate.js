import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";

var { ConektaPayment} = GafaPayElements;


export default class GafaPayTemplate extends PaymentHandle {

    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return true;
    }

    componentDidMount() {
        // this.gafaPayRemoveEventListener();
        // this.gafaPayAddEventListener();
        console.log(GafaPayElements)
    }

    // componentWillUnmount() {
    //     // this.gafaPayRemoveEventListener();
    // }
    //
    // onGafapaySuccess = () => {
    //     console.log('onGafapaySuccess')
    // };
    //
    // onGafapayLoadedForm = () => {
    //     console.log('onGafapayLoadedForm')
    // };
    //
    // onGafapayError = () => {
    //     console.log('onGafapayLoadedForm')
    // };
    //
    // onGafapayDeleteCardSuccess = () => {
    //     console.log('onGafapayDeleteCardSuccess')
    // };
    //
    // onGafapayStartingPay = () => {
    //     console.log('onGafapayStartingPay');
    // };
    //
    // onGafapayEndingPay = () => {
    //     console.log('onGafapayEndingPay');
    // };
    //
    // gafaPayAddEventListener = () => {
    //     document.addEventListener(window.EVENT_PAY_SUCCESS, this.onGafapaySuccess, false);
    //     document.addEventListener(window.EVENT_PAY_ERROR, this.onGafapayError, false);
    //     document.addEventListener(window.EVENT_DELETE_CARD_SUCCESS, this.onGafapayDeleteCardSuccess, false);
    //     document.addEventListener(window.EVENT_STARTING_PAY, this.onGafapayStartingPay, false);
    //     document.addEventListener(window.EVENT_ENDDING_PAY, this.onGafapayEndingPay, false);
    // };
    //
    // gafaPayRemoveEventListener = () => {
    //     document.removeEventListener(window.EVENT_PAY_SUCCESS, this.onGafapaySuccess, false);
    //     document.removeEventListener(window.EVENT_PAY_ERROR, this.onGafapayError, false);
    //     document.removeEventListener(window.EVENT_DELETE_CARD_SUCCESS, this.onGafapayDeleteCardSuccess, false);
    //     document.removeEventListener(window.EVENT_STARTING_PAY, this.onGafapayStartingPay, false);
    //     document.removeEventListener(window.EVENT_ENDDING_PAY, this.onGafapayEndingPay, false);
    // };

    /**
     *
     * @returns {XML}
     */
    render() {
        // let lang = StoreReservation.get('lang');
        const lineItems = StoreReservation.get('product', []).map(item => {
            return {name: item.name, unitPrice: item.price_final, quantity: item.amount}
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');
        return (
            <div className="PaymentSelection--Gafapay">
                <ConektaPayment
                    order={{
                        customerName: `${first_name} ${last_name}`,
                        customerEmail: email,
                        customerPhone: phone,
                        lineItems: [{"name": "paquete test", "unitPrice": 300, "quantity": 1}]
                    }}
                    onStartPayAction={() => console.log('onStartPayAction')}
                    onGafaPaySuccessAction={() => console.log('onGafaPaySuccessAction')}
                    onGafaPayErrAction={(err) => console.log('onGafaPayErrAction', err)}
                />
            </div>
        )
    }
}