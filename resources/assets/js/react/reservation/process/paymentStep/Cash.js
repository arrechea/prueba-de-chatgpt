import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";
import ConfirmBuyStep from "../steps/ConfirmBuyStep";
import IconInfo from "../ui/iconInfo";

// var GenericPayment;

// if (!window.GafaPayElements)
// console.log('window.GafaPayElements', window.GafaPayElements);
// const {GenericPayment} = window.GafaPayElements;

export default class Cash extends PaymentHandle {
    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return true;
    }

    constructor(props) {
        super(props);
        this.state = {
            instance: null
        };
    }

    goToBuySystem() {
        // StoreReservation.set('step', <ConfirmBuyStep/>);
        this.state.instance.handlePay();
        // console.log('this.state.instance', this.state.instance, this.state.instance.handlePay());
    }

    handleGafaPayErrAction({err, message}) {
        console.log('Conekta.handleGafaPayErrAction', err);
        // alert(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.');
        // Materialize.toast(typeof message !== "undefined" ? message : 'Ocurrió un error durante el pago.', 4000);
    };

    handleGafaPaySuccessAction({message}) {
        console.log('onGafaPaySuccessAction', message);
        // Materialize.toast(message, 4000);
        StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep');
    };

    /**
     * Montaje del boton
     */
    componentDidMount() {
        let component = this;
        let lang = StoreReservation.get('lang');
        const {gafapay_id} = this.props.payment;
        const lineItems = StoreReservation.get('product', []).map(item => {
            return {name: item.name, unitPrice: item.price_final, quantity: item.amount}
        });
        const {email, phone, first_name, last_name} = StoreReservation.get('user');

        const {GenericPayment} = window.GafaPayElements;

        this.setState({
            instance: new GenericPayment({
                order: {
                    customerName: `${first_name} ${last_name}`,
                    customerEmail: email,
                    customerPhone: phone,
                    lineItems
                },
                onStartPayAction: () => console.log('onStartPayAction'),
                onGafaPaySuccessAction: this.handleGafaPaySuccessAction,
                onGafaPayErrAction: this.handleGafaPayErrAction,
                paymentId: gafapay_id
            })
        });

        // console.log('componentDidMount', this.props.payment);

        StoreReservation.set('confirmPaymentButton', (
            <div>
                <button className="gs-checkOut" type="button"
                        onClick={component.goToBuySystem.bind(component)}>
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
        let lang = StoreReservation.get('lang');

        return (
            <div className="PaymentSelection--Conekta">
                <div className="Conekta__Message">
                    <IconInfo/>
                    <h3>
                        {lang['cash.text']}
                    </h3>
                </div>
            </div>
        )
    }
}
