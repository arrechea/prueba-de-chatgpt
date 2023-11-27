import React from 'react'
import StoreReservation from "../StoreReservation";
import BuySystemStep from "./BuySystemStep";
import SelectPaymentStep from "./SelectPaymentStep";

/**
 * @deprecated
 */
export default class ConfirmBuyStep extends React.Component {
    /**
     * Redirigimos directamente para saltarnos el paso
     */
    componentDidMount() {
        StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep', true);
    }

    goToBuySystem() {
        StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep');
    }

    goToPaymentStep() {
        StoreReservation.setStep(<SelectPaymentStep/>, 'SelectPaymentStep');
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        // StoreReservation.setStep(<BuySystemStep/>, 'BuySystemStep', true);
        let lang = StoreReservation.get('lang');
        let images = StoreReservation.get('images');
        let user = StoreReservation.get('user');
        let meeting = StoreReservation.get('meeting');
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');

        let cart = StoreReservation.getCart(lang);
        let totalAPagar = StoreReservation.getTotalAmount();
        let giftCard = StoreReservation.isGiftCard && StoreReservation.isCorrectGiftCardCode ? StoreReservation.giftCode : null;

        let translateConfirm = Object.assign({}, lang);
        translateConfirm.reservation = lang['confirm.reservation'];
        translateConfirm.locale = lang['confirm.locale'];

        return null;

        // return (
        //     <div className="ConfirmBuyStep">
        //         <div className="AppReservation--steps--prev">
        //             {
        //                 totalAPagar > 0 ?
        //                     <div className="AppReservation--prevTop" type="button"
        //                          onClick={this.goToPaymentStep.bind(this)}>
        //                         <img src={images['previous']} alt="" className="AppReservation--prevTop--arrow"/>
        //                         {lang['goToPaymentMethod.return']}
        //                     </div>
        //                     : ''
        //             }
        //         </div>
        //         <div className="CreateReservationFancy--title">{lang['confirm.sure']}</div>
        //         <div className="ConfirmBuyStep--user">
        //             <div className="ConfirmBuyStep--user--name">{user.first_name} {user.last_name}</div>
        //         </div>
        //         <div className="ConfirmBuyStep--info">
        //             <div className="ConfirmBuyStep--purchase">
        //                 <PurchaseThankyou purchase={cart} giftCard={giftCard}/>
        //             </div>
        //             <div className="ConfirmBuyStep--meeting">
        //                 {meeting ? (
        //                         <div className="ThankyouStep--reservation">
        //                             <MeetingThankyou date={meeting.start_date} staff={meeting.staff}
        //                                              meeting={meeting} lang={translateConfirm}
        //                                              map_objectsSelected={map_objectsSelected}/>
        //                         </div>
        //                     )
        //                     : ''}
        //             </div>
        //         </div>
        //         <div className="AppReservation--steps">
        //             <button className="AppReservation--button AppReservation--button--next" type="button"
        //                     onClick={this.goToBuySystem.bind(this)}>
        //                 {lang['Confirm']}
        //             </button>
        //         </div>
        //     </div>
        // )
    }
}
