import React from 'react'
import CheckValidCreditsStepTemplate from "../steps/CheckValidCreditsStepTemplate";
import StoreReservation from "../StoreReservation";
import MeetingThankyou from "../thankyouStep/MeetingThankyou";
import IconCheckout from "./../ui/iconCheckout";

export default class WaitlistMeeting extends React.Component {
    static get defaultProps() {
        return {
            meeting: null,
        }
    }

    goToCheckCredits() {
        StoreReservation.setStep(<CheckValidCreditsStepTemplate/>, 'CheckValidCreditsStepTemplate');
    }

    render() {
        let {meeting} = this.props;
        let waitlistSize = parseInt(meeting.waitlist_size);

        let lang = StoreReservation.get('lang');

        let translateConfirm = Object.assign({}, lang);
        translateConfirm.reservation = lang['confirm.reservation'];
        translateConfirm.locale = lang['confirm.locale'];
        let waitlistSecondaryText = waitlistSize === 1 ? translateConfirm['waitlist.freeSpace'] : translateConfirm['waitlist.freeSpaces'];

        return (
            <div className="gs-thankyou is-meetingSummary">
                <div className="gs-thankyou__icon">
                    <IconCheckout/>
                </div>
                <div className="gs-thankyou__status">
                    <div className="gs-thankyou__header">
                        <div className="gs-title">{lang['waitlist.title']}</div>
                        <div className="gs-subtitle">{lang['waitlist.question']}</div>
                        <div className="gs-subtitle">{waitlistSize} {waitlistSecondaryText}</div>
                        <p>{lang['waitlist.explain']}</p>
                    </div>

                    <div className="ThankyouStep--reservation">
                        <MeetingThankyou date={meeting.start_date} staff={meeting.staff}
                                         meeting={meeting} lang={translateConfirm}/>
                    </div>

                    <div className="AppReservation--steps">
                        <button className="gs-checkOut" type="button"
                                onClick={this.goToCheckCredits.bind(this)}>
                            {lang['waitlist.button.advance']}
                        </button>
                    </div>
                </div>
            </div>
        );
    }
}
