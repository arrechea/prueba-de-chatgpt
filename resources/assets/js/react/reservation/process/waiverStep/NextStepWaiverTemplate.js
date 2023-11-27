import React from 'react'
import StoreReservation from "../StoreReservation";
import PreMeetingStepTemplate from "../steps/PreMeetingStepTemplate";

export default class NextStepWaiverTemplate extends React.Component {
    /**
     *
     * @returns {{canGoNextStep: boolean}}
     */
    static get defaultProps() {
        return {
            canGoNextStep: false,
            saveSignature: () => {
            }
        }
    }

    /**
     * Go to Payment methods
     */
    goToPreMeeting() {
        this.props.saveSignature();
        StoreReservation.setStep(<PreMeetingStepTemplate/>, 'PreMeetingStepTemplate', true);
    }

    /**
     *
     * @returns {*}
     */
    render() {
        if (!this.props.canGoNextStep) {
            return null;
        }
        let lang = StoreReservation.get('lang');

        return (
            <button className="AppReservation--button AppReservation--button--next" type="button"
                    onClick={this.goToPreMeeting.bind(this)}>
                {lang['continue']}
            </button>
        );
    }
}
