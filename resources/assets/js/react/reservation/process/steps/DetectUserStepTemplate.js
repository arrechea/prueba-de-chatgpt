import React from 'react'
import SelectProductsStepTemplate from "./SelectProductsStepTemplate";
import StoreReservation from "../StoreReservation";
import SelectPaymentStep from "./SelectPaymentStep";
import LoginStepTemplate from "./LoginStepTemplate";

export default class DetectUserStepTemplate extends React.Component {
    constructor() {
        super();
        let user = StoreReservation.get('user');
        if (!user) {
            StoreReservation.setStep(<LoginStepTemplate
                next={<DetectUserStepTemplate/>}
                next_name={'DetectUserStepTemplate'}
                store={StoreReservation}/>, 'LoginStepTemplate', true);
            return;
        }
        let combo = StoreReservation.get('combo');
        let cart = StoreReservation.get('cart');
        let membership = StoreReservation.get('membership');

        let firstState = <SelectProductsStepTemplate/>;
        let firstStateName = 'SelectProductsStepTemplate';
        if (combo || membership) {
            firstState = <SelectPaymentStep cartItems={cart}/>;
            firstStateName = 'SelectPaymentStep';
        }
        StoreReservation.setStep(firstState, firstStateName, true);
    }

    /**
     *
     * @returns {null}
     */
    render() {
        return null;
    }
}
