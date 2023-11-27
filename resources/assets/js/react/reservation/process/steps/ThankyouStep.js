import React from 'react'
import StoreReservation from "../StoreReservation";
import PurchaseThankyou from "../thankyouStep/PurchaseThankyou";
import ReservationThankyou from "../thankyouStep/ReservationThankyou";
import IconCheckout from "./../ui/iconCheckout";
import {formatMoney} from "../../../../helpers/FormatUtils"

export default class ThankyouStep extends React.Component {
    componentDidMount() {
        let event = new CustomEvent("buq__reservation_complete", {
            'detail': {
                reservation: this.props.reservation,
            }
        });
        document.dispatchEvent(event);
    }

    /**
     *
     * @returns {{test: boolean, reservation: {}, purchase: {}}}
     */
    static get defaultProps() {
        return {
            reservation: null,
            purchase: null,
        }
    }

    getUserSubtitle() {
        let lang = StoreReservation.get('lang');
        let meeting = StoreReservation.get('meeting');

        if (meeting) {
            if (meeting.is_valid_for_check_overbooking) {
                return <div className="ConfirmBuyStep--user--subtitle">{lang['overbooking.thankyou']}</div>;
            } else if (meeting.is_valid_for_waitlist) {
                return <div className="ConfirmBuyStep--user--subtitle">{lang['waitlist.thankyou']}</div>;
            }
        }
        return null;
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let lang = StoreReservation.get('lang');
        let images = StoreReservation.get('images');
        let user = StoreReservation.get('user');
        let cart = StoreReservation.getCart(lang);
        let giftCard = StoreReservation.isGiftCard && StoreReservation.isCorrectGiftCardCode ? StoreReservation.giftCode : null;
        // let userSubtitle = this.getUserSubtitle();
        let thanks_slug = cart.items.length ? 'thanks' : 'thanks_reservation';
        let currency = StoreReservation.get('currency');
        let total = (this.props.purchase != null) ?
            <div className="totalAmount"><span>{currency.prefijo} {formatMoney(this.props.purchase.total)}</span>
            </div> : "";

        return (
            <div className="gs-thankyou">
                <div className="gs-thankyou__icon">
                    <IconCheckout/>
                </div>
                <div className="gs-thankyou__status">
                    <div className="gs-orderSummary">
                        <div className="gs-title">{lang[thanks_slug]}</div>
                        <div className="gs-orderSummary__order">
                            <PurchaseThankyou purchase={cart} giftCard={giftCard} purchaseData={this.props.purchase}/>
                        </div>
                        <div className="gs-orderSummary__orderMeeting">
                            <ReservationThankyou reservation={this.props.reservation} purchase={this.props.purchase}/>
                        </div>
                        <div className="gs-orderSummary__total">
                            {total}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
