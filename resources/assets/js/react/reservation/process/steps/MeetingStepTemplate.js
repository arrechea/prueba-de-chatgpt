import React from 'react'
import StoreReservation from "../StoreReservation";
import MapMeetingTemplate from "../meetingStep/MapMeetingTemplate";
import QuantityMeeting from "../meetingStep/QuantityMeeting";
import OverbookingMeeting from "../meetingStep/OverbookingMeeting";
import WaitlistMeeting from "../meetingStep/WaitlistMeeting";
import DetectUserStepTemplate from "./DetectUserStepTemplate";

export default class MeetingStepTemplate extends React.Component {

    constructor() {
        super();
        let meeting = StoreReservation.get('meeting');
        if (!meeting) {
            //No se quiere reservar meeting
            StoreReservation.setStep(<DetectUserStepTemplate/>, 'DetectUserStepTemplate', true);
        }
    }

    render() {
        let meeting = StoreReservation.get('meeting');

        let childrenObject = (
            <div>
                MeetingStep
            </div>
        );
        if (meeting.is_valid_for_check_overbooking) {
            childrenObject = <OverbookingMeeting meeting={meeting}/>
        } else if (meeting.is_valid_for_waitlist) {
            childrenObject = <WaitlistMeeting meeting={meeting}/>
        } else if (meeting.details === 'quantity') {
            //Meeting tipo quantity
            childrenObject = <QuantityMeeting meeting={meeting}/>
        } else if (meeting.details === 'map') {
            childrenObject = <MapMeetingTemplate meeting={meeting}/>
        }

        return childrenObject
    }
}
