import React from 'react'
import StoreReservation from "../StoreReservation";

export default class TermsInput extends React.Component {
    constructor() {
        super();
        this.state = {
            button: StoreReservation.get('confirmPaymentButton'),
            enableCheckboxTerms: StoreReservation.brandHasTermLink(),
            isSubscribable: false,
            isRecurrent: false,
            canSubscribe: false
        };

        StoreReservation.addSegmentedListener(['combo', 'membership', 'isRecurrent', 'canSubscribe', 'subscribe', 'set_payment', 'checkedTerms'], this.subscribableListener.bind(this));
    }

    /**
     *
     * @returns {*}
     */
    getBrandTermsLink() {
        return StoreReservation.getBrandTermsLink();
    }

    ChangeTerms() {
        let checkedTerms = StoreReservation.isCheckedTerms();
        StoreReservation.set('checkedTerms', !checkedTerms);
    }

    /**
     *
     * @returns {boolean}
     */
    isGiftCardCorrect() {
        if (
            StoreReservation.isGiftCard === true
            &&
            (
                StoreReservation.isCorrectGiftCardCode !== true
                ||
                StoreReservation.isPendingToCheckGiftCardCode === true
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     *
     * @returns {boolean}
     */
    isCheckedTerms() {
        return StoreReservation.isCheckedTerms();
    }

    subscribableListener() {
        let isSubscribable = this.productSubscribable();
        let canSubscribe = StoreReservation.get('canSubscribe');
        let isRecurrent = StoreReservation.get('isRecurrent');
        let subscribe = StoreReservation.get('subscribe');
        let set_payment = StoreReservation.get('set_payment');

        this.setState({
            isSubscribable: isSubscribable,
            canSubscribe: canSubscribe,
            isRecurrent: isRecurrent,
            set_payment: set_payment,
            subscribe: subscribe,
        });
    }

    productSubscribable() {
        let product = null;
        let combo = StoreReservation.get('combo');
        let membership = StoreReservation.get('membership');
        if (combo) {
            product = combo;
        } else if (membership) {
            product = membership;
        }

        if (product == null) {
            return false;
        }

        return Boolean(product.subscribable === 1)
    }

    isSubscribable(cart) {
        return StoreReservation.isCartSubscribable(cart);
    }

    setSubscribe() {
        let subscribe = this.state.subscribe;
        let newValue = !subscribe;
        if (!!window.StoreGafaPayments) {
            window.StoreGafaPayments.set('recurringPayment', newValue);
        }
        StoreReservation.set('subscribe', newValue);
    }

    setPayment() {
        let set_payment = this.refs.set_payment.checked;
        StoreReservation.set('set_payment', set_payment);
        this.setState({
            'set_payment': set_payment,
        });
    }

    render() {
        let lang = StoreReservation.get('lang');
        let activeTerms = StoreReservation.hasTerms();

        return (
            <div>
                <div className="gs-terms">
                    {
                        StoreReservation.isPosibleToSubscribe() ?
                            (
                                <div className={'gs-terms__item'}>
                                    <input
                                        type={'checkbox'}
                                        name={'subscribe'}
                                        ref={'subscribe'}
                                        onChange={this.setSubscribe.bind(this)}
                                        checked={StoreReservation.isUserWantSubscribe()}
                                    />
                                    <a>{lang['Subscribe']}</a>
                                </div>
                            )
                            :
                            null
                    }
                    {
                        activeTerms ?
                            (
                                <div className="gs-terms__item">
                                    <input
                                        type="checkbox"
                                        onChange={this.ChangeTerms}
                                        checked={StoreReservation.isCheckedTerms()}
                                    />
                                    <a href={this.getBrandTermsLink()} target="_blank">{lang['payment.terms']}</a>
                                </div>
                            )
                            :
                            null
                    }
                </div>
            </div>
        )
    }
}
