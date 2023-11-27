import React from 'react'
import StoreReservation from "../StoreReservation";
import SelectProductsStepTemplate from "../steps/SelectProductsStepTemplate";

export default class NextStepUserCreditsTemplate extends React.Component {
    /**
     * Go to Payment methods
     */
    goToProducts() {
        StoreReservation.setStep(<SelectProductsStepTemplate/>, 'SelectProductsStepTemplate');
    }

    /**
     *
     * @returns {*}
     */
    render() {
        let lang = StoreReservation.get('lang');

        return (
            <button className="AppReservation--button AppReservation--button--next" type="button"
                    onClick={this.goToProducts.bind(this)}>
                {lang['goToProducts']}
            </button>
        );
    }
}
