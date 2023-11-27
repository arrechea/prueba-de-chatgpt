import React from 'react'
import StoreReservation from "../StoreReservation";
import MeetingStepTemplate from "../steps/MeetingStepTemplate";

export default class PrevStepProductsTemplate extends React.Component {
    /**
     * Go to Payment methods
     */
    goToMeetings() {
        StoreReservation.setStep(<MeetingStepTemplate/>, 'MeetingStepTemplate');
    }

    /**
     *
     * @returns {*}
     */
    render() {
        let meeting = StoreReservation.get('meeting');
        if (!!meeting && meeting.details === 'map') {
            let lang = StoreReservation.get('lang');
            let images = StoreReservation.get('images');

            return (
                <div className="AppReservation--prevTop" type="button"
                     onClick={this.goToMeetings.bind(this)}>
                    <img src={images['previous']} alt="" className="AppReservation--prevTop--arrow"/>
                    {lang['goToMeetingsMaps']}
                </div>
            );
        }
        return null;
    }
}
