import React from 'react'
import PaymentHandle from "./PaymentHandle";
import StoreReservation from "../StoreReservation";
import ConfirmBuyStep from "../steps/ConfirmBuyStep";
import IconInfo from "../ui/iconInfo";

const {GenericPayment} = window.GafaPayElements;

// const instance =

export default class Generic extends PaymentHandle {
    /**
     *
     * @returns {boolean}
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
        StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep');
    }

    componentDidMount() {
        let component = this;
        let lang = StoreReservation.get('lang');

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
        let {slug} = this.props.payment;
        let lang = StoreReservation.get('lang');
        return (
            <div className="PaymentSelection--Conekta">
                <div className="Conekta__Message">
                    <IconInfo/>
                    <h3>
                        {lang[slug]}
                    </h3>
                </div>
            </div>
        )
    }
}
