import React from 'react'
import StoreReservation from "../StoreReservation";
import ConfirmBuyStep from "./ConfirmBuyStep";
import DetectUserStepTemplate from "./DetectUserStepTemplate";

export default class CheckValidCreditsStepTemplate extends React.Component {
    /**
     *
     */
    constructor() {
        super();
        let validCredits = StoreReservation.get('user_ValidCredits');
        let user_ValidMembership = StoreReservation.get('user_ValidMembership');
        let meeting = StoreReservation.get('meeting');
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');

        if (!meeting) {
            //No se quiere reservar meeting
            StoreReservation.setStep(<DetectUserStepTemplate/>, 'DetectUserStepTemplate', true);
        } else {
            if (user_ValidMembership.length > 0) {
                //Membresia valida
                StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep', true);
            } else {
                if (validCredits.length <= 0 || (this.calculateSumCredits(validCredits) < map_objectsSelected.length)) {
                    //No tiene creditos validos
                    StoreReservation.setStep(<DetectUserStepTemplate/>, 'DetectUserStepTemplate', true);
                } else {
                    //Tiene creditos validos entonces pasa a reservar
                    StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep', true);
                }
            }
        }
    }

    calculateSumCredits(validCredits) {
        let response = 0;
        if (Array.isArray(validCredits)) {
            validCredits.forEach((credit) => {
                response += parseInt(credit.total);
            })
        }
        return response;
    }

    /**
     *
     * @returns {null}
     */
    render() {
        return null;
    }
}
