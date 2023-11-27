import React from 'react'
import StoreReservation from "../StoreReservation";

export default class TotalsContainer extends React.Component {
    static defaultProps() {
        return {
            purchase: null,
        }
    }

    render() {
        let purchase = this.props.purchase;
        let lang = StoreReservation.get('lang');
        let currency = StoreReservation.get('currency');

        let calcTotal = 0;

        purchase.items.forEach(function(element) {
          calcTotal += element.price_final * element.amount;
        });

        let withoutDiscountPrice = StoreReservation.moneyFormat(calcTotal - purchase.discount);
        let totalPrice = StoreReservation.moneyFormat(calcTotal);

        return (
            <div className="gs-totalPurchase">
                <div className="gs-totalPurchase__content">
                    {
                        purchase.discount > 0 ?
                            (
                                <h6 className="gs-totalPurchase__subtotal">
                                    {currency.prefijo} {totalPrice}
                                </h6>
                            )
                            : null
                    }
                    <h5 className="gs-totalPurchase__total">
                        {currency.prefijo} {withoutDiscountPrice}
                    </h5>
                </div>
            </div>
        );
    }
}
