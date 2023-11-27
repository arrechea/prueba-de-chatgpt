import React from 'react'
// import $ from 'jquery'
import StoreReservation from "./StoreReservation";
import WaiverStepTemplate from "./steps/WaiverStepTemplate";
import BackButton from "./navigation/BackButton";

export default class AppReservationTemplate extends React.Component {

    /**
     *
     */
    constructor() {
        super();
        this.state = {
            //Fijos
            user: StoreReservation.get('user'),
            user_Credits: StoreReservation.get('user_Credits'),
            user_ValidCredits: StoreReservation.get('user_ValidCredits'),
            user_ValidMembership: StoreReservation.get('user_ValidMembership'),
            location: StoreReservation.get('location'),
            // admin: null,
            admin: StoreReservation.get('admin'),
            meeting: StoreReservation.get('meeting'),
            combosSelection: StoreReservation.get('combosSelection'),
            membershipSelection: StoreReservation.get('membershipSelection'),

            //Variables
            isProcessing: StoreReservation.get('isProcessing'),
            combo: StoreReservation.get('combo'),
            membership: StoreReservation.get('membership'),
            step: <WaiverStepTemplate/>,
            back: null,
        };

        StoreReservation.addListener(this.ListenerStore.bind(this));
    }

    /**
     * Listener Store
     * @constructor
     */
    ListenerStore() {
        this.setState({
            user: StoreReservation.get('user'),
            location: StoreReservation.get('location'),
            admin: StoreReservation.get('admin'),
            meeting: StoreReservation.get('meeting'),
            combo: StoreReservation.get('combo'),
            membership: StoreReservation.get('membership'),
            step: StoreReservation.get('step'),
            isProcessing: StoreReservation.get('isProcessing'),
        });
    }

    printProcessingMessage() {
        let {isProcessing} = this.state;
        let lang = StoreReservation.get('lang');
        if (isProcessing) {
            return (
                <div className={'Payment--Loading--Message'}>
                    {lang['IsProcessingMessage']}
                </div>
            );
        }

        return null;
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        const {isProcessing, step} = this.state;
        const {widget} = this.props;

        return (
            <div className={(isProcessing ? ' is-processing' : '')}>
                {this.printProcessingMessage()}
                <BackButton/>
                <div
                    className={(widget ? 'AppReservation' : '') + ' ReservationBody--flex'}
                    style={{pointerEvents: isProcessing ? 'none' : 'auto'}}>
                    {step}
                </div>
            </div>
        );
    }
}
