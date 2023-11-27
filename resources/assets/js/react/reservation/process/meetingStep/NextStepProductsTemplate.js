import React from 'react'
import StoreReservation from "../StoreReservation";
import CheckValidCreditsStepTemplate from "../steps/CheckValidCreditsStepTemplate";
import FunctionHelper from "../../../../helpers/FunctionHelper";

export default class NextStepProductsTemplate extends React.Component {
    /**
     *
     */
    constructor() {
        super();
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');

        this.state = {
            'canGoNextStep': Array.isArray(map_objectsSelected) && map_objectsSelected.length > 0
        };
        //Listener
        StoreReservation.addSegmentedListener([
            'map_objectsSelected',
            'invited_data'
        ], this.ListenersChanges.bind(this))
    }

    /**
     *
     */
    ListenersChanges() {
        let validationsErros = this.checkValidInvites();
        this.setState({
            'canGoNextStep': !validationsErros,
            'errors': validationsErros,
        })
    }

    checkValidInvites() {
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');
        let respuesta = null;
        let lang = StoreReservation.get('lang');

        if (Array.isArray(map_objectsSelected)) {
            if (map_objectsSelected.length < 1) {
                respuesta = lang['confirm.error.notPosition'];
            } else if (map_objectsSelected.length > 1) {
                if (StoreReservation.isGeustInfoRequired()) {
                    let invitedData = StoreReservation.get('invited_data');
                    if (!!invitedData) {
                        let helper = new FunctionHelper();
                        map_objectsSelected.forEach((position, index) => {
                            if (index > 0) {
                                let info = invitedData[index];
                                if (
                                    !info
                                    ||
                                    !info.name
                                    ||
                                    !info.email
                                    ||
                                    !helper.validateEmail(info.email)
                                ) {
                                    respuesta = lang['confirm.error.notInvitedInfo'];
                                }
                            }
                        })
                    } else {
                        respuesta = lang['confirm.error.notInvitedInfo'];
                    }
                }
            }
        } else {
            respuesta = lang['confirm.error.notPosition'];
        }
        return respuesta;
    }

    /**
     * Go to Payment methods
     */
    goToProducts() {
        StoreReservation.setStep(<CheckValidCreditsStepTemplate/>, 'CheckValidCreditsStepTemplate');
    }

    /**
     *
     * @returns {*}
     */
    render() {
        let lang = StoreReservation.get('lang');
        let {
            canGoNextStep,
        } = this.state;
        if (!canGoNextStep) {
            return (
                <div className="touchpoint">
                    <button
                        className="gs-checkOut"
                        type="button"
                        disabled={true}
                    >
                        {lang['meeting.button.advance']}
                    </button>
                </div>
            );
        }

        return (
            <div className="touchpoint">
                <button className="gs-checkOut"
                        type="button"
                        onClick={this.goToProducts.bind(this)}
                >
                    {lang['meeting.button.advance']}
                </button>
            </div>
        );
    }
}
