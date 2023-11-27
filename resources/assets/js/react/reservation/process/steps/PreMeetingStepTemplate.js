import React from 'react'
import MeetingStepTemplate from "./MeetingStepTemplate";
import StoreReservation from "../StoreReservation";
import DetectUserStepTemplate from "./DetectUserStepTemplate";

export default class PreMeetingStepTemplate extends React.Component {
    constructor() {
        super();

        let meeting = StoreReservation.get('meeting');
        let combo = StoreReservation.get('combo');
        let cart = StoreReservation.get('cart');
        let membership = StoreReservation.get('membership');

        let firstState = <DetectUserStepTemplate/>;
        let firstStateName = 'DetectUserStepTemplate';
        if (meeting) {
            firstState = <MeetingStepTemplate meeting={meeting}/>;
            firstStateName = 'MeetingStepTemplate';
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
