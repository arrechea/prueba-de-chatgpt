import React from 'react'
import StoreReservation from "../StoreReservation";

export default class PaymentHandle extends React.Component {
    static get defaultProps() {
        return {
            payment: null,
        }
    }

    getConfig() {
        let payment = this.props.payment;
        if (payment) {
            let config = payment.pivot.config;
            try {
                return JSON.parse(config);
            } catch (e) {
                console.error('No payment Config\n', e);
                return null;
            }
        }
        return null;
    }

    /**
     *
     * @returns {*}
     */
    getUserPaymentInfo() {
        let payment = this.props.payment;
        let slug = payment.slug;
        let paymentsInfo = StoreReservation.get('payment_info_userProfile');
        if (paymentsInfo.hasOwnProperty(slug)) {
            return paymentsInfo[slug];
        }
        return null;
    }

    /**
     *
     * @returns {boolean}
     */
    static isValidNow() {
        return false;
    }

    render() {
        return (
            <div>
                Dont Seet Correctly
            </div>
        );
    }
}
