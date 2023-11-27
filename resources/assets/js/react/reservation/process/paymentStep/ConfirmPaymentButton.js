import React from 'react'
import StoreReservation from "../StoreReservation";

export default class ConfirmPaymentButton extends React.Component {
    constructor() {
        super();
        let enableTermsInBrand = this.CheckTermsAndConditionaInitialState();
        this.state = {
            button: StoreReservation.get('confirmPaymentButton'),
            checkedTerms: !enableTermsInBrand,
            isSubscribable: false,
            isRecurrent: false,
            canSubscribe: false
        };

        StoreReservation.addSegmentedListener([
            'confirmPaymentButton',
            'payment',
            'isGiftCard',
            'isCorrectGiftCardCode',
            'isPendingToCheckGiftCardCode',
            'giftCode'
        ], this.ListenerStore.bind(this));
    }

    /**
     * Listener Store
     * @constructor
     */
    ListenerStore() {
        this.setState({
            button: StoreReservation.get('confirmPaymentButton'),
        });
    }

    /**
     *
     * @returns {*}
     */
    getBrandTermsLink() {
        return StoreReservation.getBrandTermsLink();
    }

    /**
     *
     * @returns {boolean}
     * @constructor
     */
    CheckTermsAndConditionaInitialState() {
        let link = this.getBrandTermsLink();
        return link !== '' && !!link;
    }

    getDisabledButton() {
        let lang = StoreReservation.get('lang');

        return (
            <div className="AppReservation--steps">
                <button
                    className="gs-checkOut disabled"
                    type="button"
                    onClick={() => {
                        if (!StoreReservation.isCheckedTerms()) {
                            return alert(lang['payment.terms.error']);
                        }
                    }}
                >
                    {lang['goToBuySystem']}
                </button>
            </div>
        )
    }

    /**
     *
     * @returns {boolean}
     */
    isFormCorrect() {
        //terms
        if (!StoreReservation.isCheckedTerms()) {
            return false;
        }
        //gift
        return this.isGiftCardCorrect();
    }

    /**
     *
     * @returns {boolean}
     */
    isGiftCardCorrect() {
        return !(StoreReservation.isGiftCard === true
        &&
        (
            StoreReservation.isCorrectGiftCardCode !== true
            ||
            StoreReservation.isPendingToCheckGiftCardCode === true
        ));
    }

    render() {
        let component = this;
        let button = component.state.button;

        if (!button) {
            return this.getDisabledButton();
        }

        return (
            <div>
                {this.isFormCorrect() ? this.state.button : this.getDisabledButton()}
            </div>
        )
    }
}
