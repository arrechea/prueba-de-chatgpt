import React from 'react'
import StoreReservation from "../StoreReservation";
import IconLeftArrow from "../ui/iconLeftArrow";

export default class BackButton extends React.Component {
    constructor() {
        super();
    }

    goBack() {
        StoreReservation.setStep(null, null, false, true);
    }

    printText() {
        let back = StoreReservation.get('back');
        let lang = StoreReservation.get('lang');
        return back === null ? lang['close'] : lang['back'];
    }

    render() {

        let step_name = StoreReservation.get('step_name');
        if (step_name === 'BuySystemStep' || step_name === 'ConfirmBuyStep') {
            return null;
        }

        return (<div className="gs-button is-return" type="button" onClick={this.goBack.bind(this)}>
            <IconLeftArrow/>
            {this.printText()}
        </div>);
    }
}
