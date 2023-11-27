import React from 'react'
import StoreReservation from "../StoreReservation";
import {formatMoney} from "../../../../helpers/FormatUtils"

export default class PurchaseSummary extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            disconunt: null
        };

    }

    static defaultProps() {
        return {
            purchase: null,
            giftCard: null,
        }


    }

    render() {
        let {
            purchase,
            giftCard
        } = this.props;
        let lang = StoreReservation.get('lang');
        let currency = StoreReservation.get('currency');
        let discountCode = StoreReservation.getDiscountCode();

        if (!purchase || !purchase.items.length) {
            return null;
        }

        return (
            <div className="gs-buySummary">
                <div className="gs-summary__cart gs-cart">
                    {purchase.items.map((item, index) => {
                        let item_price_final = StoreReservation.moneyFormat(item.price_final * item.amount);

                        return (
                            <div className="gs-cartItem" key={index}>
                                <div key={item.id} className="gs-cartItem__container">
                                    <div className="gs-cartItem__header">
                                        <h4>{item.name}</h4>
                                        <span
                                            className="gs-price"> {currency.prefijo} {formatMoney((item.price_final * item.amount), 0)}</span>
                                    </div>
                                    <div className="gs-cartItem__footer">
                                        <ul>
                                            <li>
                                                <span className="gs-amount">Cantidad: {item.amount}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        )
                    })}
                    {
                        discountCode ? (
                            <tr className="PurchaseThankyou--items" key={`PurchaseThankyou--items--discountCode`}>
                                <td className="PurchaseThankyou--items--quantity">
                                    {lang['discount_code']}
                                    <div className="PurchaseThankyou--items--data">
                                        <div className="PurchaseThankyou--items--data--expiration">
                                            {discountCode.code}
                                        </div>
                                    </div>
                                </td>
                                <td className="PurchaseThankyou--items--price">
                                    -{currency.prefijo} {StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true))}
                                </td>
                            </tr>
                        ) : null
                    }
                    {
                        giftCard ? (
                            <tr className="PurchaseThankyou--items" key={`PurchaseThankyou--items--giftCard`}>
                                <td className="PurchaseThankyou--items--quantity">
                                    {`1 ${lang['confirm.giftcard']}`}
                                    <div className="PurchaseThankyou--items--data">
                                        <div className="PurchaseThankyou--items--data--expiration">
                                            {giftCard}
                                        </div>
                                    </div>
                                </td>
                                <td className="PurchaseThankyou--items--price"/>
                            </tr>
                        ) : null
                    }
                </div>
            </div>
        )
    }
}
