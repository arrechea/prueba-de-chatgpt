import React from 'react'
import StoreReservation from "../StoreReservation";
import moment from 'moment'
import MeetingThankyou from "./MeetingThankyou";

export default class ReservationThankyou extends React.Component {
    static defaultProps() {
        return {
            reservation: null,
            purchase: null,
        }
    }

    /**
     *
     * @param lang
     * @param reservations
     */
    printReservation(lang, reservations) {

        if (!reservations) {
            return null;
        }
        let firstReservation = reservations[0];
        
        return (
            <MeetingThankyou date={firstReservation.meeting_start} staff={firstReservation.staff}
                             firstReservation={firstReservation} lang={lang} reservations={reservations}/>
        );
    }

    /**
     *
     * @param lang
     * @param purchase
     */
    printCombosOrMemberships(lang, purchase) {
        if (!purchase) {
            return null;
        }
        let locale = lang['locale'];
        let items = purchase.items;
        let respuesta = null;

        if (items.length > 0) {
            items.forEach(function (item) {
                if (
                    item.buyed_type === "App\\Models\\Combos\\Combos"
                    ||
                    item.buyed_type === "App\\Models\\Membership\\Membership"
                ) {
                    let expiracion = item.buyed.expiration_days;
                    //2019-06-13 17:03:20.000000
                    let date = moment(item.buyed.created_at);
                    let expirationDate = date.add(expiracion, 'days');

                    respuesta = (
                        <div className="ReservationThankyou--marketing">
                            <div className="ReservationThankyou--marketing--name">
                                {item.item_name}
                            </div>
                            <div className="ReservationThankyou--marketing--expiration">
                                {lang['validity']} {expiracion} {lang['days']}
                            </div>
                            <div className="ReservationThankyou--marketing--expirationText">
                                {lang['expirationText']}
                            </div>
                            <div className="ReservationThankyou--marketing--expirationDate">
                                {expirationDate.format('dddd Do')}
                            </div>
                            <div className="ReservationThankyou--marketing--expirationDate">
                                {expirationDate.format('MMMM')}
                            </div>
                        </div>
                    );
                }
            });
        }
        return respuesta;
    }

    /**
     *
     * @returns {*}
     */
    render() {
        let reservation = this.props.reservation;
        let purchase = this.props.purchase;
        let lang = StoreReservation.get('lang');
        //set Time
        let locale = lang['locale'];
        moment.locale(locale);

        if (!reservation && !purchase) {
            return null;
        }
        return (
            <div className="gs-buySummary">
                {this.printReservation(lang, reservation)}
                {/*{this.printCombosOrMemberships(lang, purchase)}*/}
            </div>
        )
    }
}
