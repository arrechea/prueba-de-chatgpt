import React from 'react'
import CheckValidCreditsStepTemplate from "../steps/CheckValidCreditsStepTemplate";
import StoreReservation from "../StoreReservation";
import MeetingThankyou from "../thankyouStep/MeetingThankyou";
import IconCheckout from "./../ui/iconCheckout";

export default class QuantityMeeting extends React.Component {

    goToBuySystem() {
        StoreReservation.setStep(<CheckValidCreditsStepTemplate/>, 'CheckValidCreditsStepTemplate');
    }

    render() {
        let lang = StoreReservation.get('lang');
        let images = StoreReservation.get('images');
        let location = StoreReservation.get('location');
        let user = StoreReservation.get('user');
        let {meeting} = this.props;
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');

        let companyNameTemplo = location.company.slug.toUpperCase().includes("EL-T3MPLO");
        let serviceNameAthlete = meeting.service.name.toUpperCase().includes("ATHL3TE");

        let cart = StoreReservation.getCart(lang);
        let giftCard = StoreReservation.isGiftCard && StoreReservation.isCorrectGiftCardCode ? StoreReservation.giftCode : null;

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
                        <div className="gs-title">{lang['confirm.sure']}</div>
                        <div className="gs-subtitle">{user.first_name} {user.last_name}</div>
                    </div>
                    <div className="ConfirmBuyStep--info">
                        <div className="ConfirmBuyStep--meeting">
                            {meeting ? (
                                    <div className="ThankyouStep--reservation">
                                        <MeetingThankyou date={meeting.start_date} staff={meeting.staff}
                                                         meeting={meeting} lang={translateConfirm}
                                                         map_objectsSelected={map_objectsSelected}/>
                                    </div>
                                )
                                : ''}
                        </div>
                    </div>
                    <div className="AppReservation--steps">
                        <button className="gs-checkOut" type="button"
                                onClick={this.goToBuySystem.bind(this)}>
                            {lang['Confirm']}
                        </button>
                    </div>
                </div>
                {companyNameTemplo
                    ? <div className="gs-thankyou__terms">
                        <p>*Si no te presentas y no cancelas por lo menos 12 horas antes de la clase, se te hará un
                            cargo por no presentarte de $150.</p>
                        {serviceNameAthlete ? <p>*Tiempo de tolerancia Athl3te 90: 10 minutos.</p> : null}
                        {serviceNameAthlete ? <p>*Tiempo de tolerancia Athl3te 60: 5 minutos.</p> : null}
                    </div>
                    : null
                }
            </div>
        )
    }
}
