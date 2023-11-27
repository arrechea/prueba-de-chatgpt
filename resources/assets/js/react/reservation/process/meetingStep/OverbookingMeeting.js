import React from 'react'
import CheckValidCreditsStepTemplate from "../steps/CheckValidCreditsStepTemplate";
import StoreReservation from "../StoreReservation";
import MeetingThankyou from "../thankyouStep/MeetingThankyou";
import IconCheckout from "./../ui/iconCheckout";

export default class OverbookingMeeting extends React.Component {
    static get defaultProps() {
        return {
            meeting: null,
        }
    }

    goToCheckCredits() {
        StoreReservation.setStep(<CheckValidCreditsStepTemplate/>, 'CheckValidCreditsStepTemplate')
    }

    render() {
        let {meeting} = this.props;
        let lang = StoreReservation.get('lang');
        let translateConfirm = Object.assign({}, lang);
        translateConfirm.reservation = lang['confirm.reservation'];
        translateConfirm.locale = lang['confirm.locale'];

        return (
            <div className="gs-thankyou is-meetingSummary">
                <div className="gs-thankyou__icon">
                    <IconCheckout/>
                </div>
                <div className="gs-thankyou__status">
                    <div className="gs-thankyou__header">
                        <div className="gs-title">{lang['overbooking.title']}</div>
                        <div className="gs-subtitle">{lang['overbooking.question']}</div>
                        <p>{lang['overbooking.explain']}</p>
                    </div>

                    <div className="ThankyouStep--reservation">
                        <MeetingThankyou date={meeting.start_date} staff={meeting.staff}
                                         meeting={meeting} lang={translateConfirm}/>
                    </div>

                    <div className="AppReservation--steps">
                        <button className="gs-checkOut" type="button"
                                onClick={this.goToCheckCredits.bind(this)}>
                            {lang['overbooking.button.advance']}
                        </button>
                    </div>
                </div>
            </div>
        );
    }
}
