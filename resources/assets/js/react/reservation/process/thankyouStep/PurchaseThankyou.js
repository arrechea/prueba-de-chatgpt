import React from 'react'
import StoreReservation from "../StoreReservation";
export default class PurchaseThankyou extends React.Component {
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

    itemType(amount, item) {
        if (item == 'combo' && amount <= 1) {
            return 'paquete';
        }

        if (item == 'membership' && amount <= 1) {
            return 'membresía';
        }

        if (item == 'combo' && amount > 1) {
            return 'paquetes';
        }

        if (item == 'membership' && amount > 1) {
            return 'membresías';
        }

        return '';
    }

    render() {
        let {
            purchase,
            giftCard,
            purchaseData
        } = this.props;
        let lang = StoreReservation.get('lang');
        let currency = StoreReservation.get('currency');
        let discountCode = StoreReservation.getDiscountCode();

        if (!purchase || !purchase.items.length) {
            return null;
        }

        return (
            <div className="gs-buySummary">
                <div className="gs-title">{lang['payment.resume']}</div>
                {purchaseData && !!purchaseData.id ? (
                    <div className="gs-title2">{lang['payment.purchase']} #{purchaseData.id}</div>
                ) : null}
                <div className="gs-buySummary__table">
                    <table className="gs-table" cellSpacing={0}>
                        <tbody>
                        {purchase.items.map((item) => {
                            let item_price_final = StoreReservation.moneyFormat(item.price_final * item.amount);

                            return (
                                <tr className="gs-table__item" key={`gs-table__item--${item.id}`}>
                                    <td className="gs-table__description">
                                        <div className="gs-description">
                                            <div className="gs-description__body">
                                                {/* {item.amount} {item.type} - {item.name} */}
                                                {item.amount} {this.itemType(item.amount, item.type)} - {item.name}
                                            </div>
                                            <div className="gs-description__footer">
                                                <div className="gs-expiration">
                                                    {!!item.expiration_days ?
                                                        `${lang['combosSelector.expiration'] } ${item.expiration_days} ${lang.days}` :
                                                        ''}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="gs-table__price">
                                        {currency.prefijo} {item_price_final}
                                    </td>
                                </tr>
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
                        </tbody>
                    </table>
                </div>
            </div>
        )
    }
}
