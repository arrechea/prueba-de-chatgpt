import React, {Component} from 'react';
import SelectPaymentStep from "../../steps/SelectPaymentStep";
import StoreReservation from "../../StoreReservation";

class Touchpoint extends Component {
    constructor(props) {
        super(props);
        this.state = {
            location: StoreReservation.get('location'),
        };

        this.cartChange = this.cartChange.bind(this);
    }

    cartChange(cart) {
        StoreReservation.setStep(<SelectPaymentStep cartItems={this.props.cart}/>, 'SelectPaymentStep');
        StoreReservation.changeCart(cart);
    }

    render() {
        let total = 0;
        for (let item of this.props.cart) {
            total += item.price_final * item.amount;
        }
        return (
            <div className="touchpoint">
                <button className="gs-checkOut" disabled={(total != 0 || this.props.cart.length > 0) ? false : true}
                        onClick={() => this.cartChange(this.props.cart)}>FORMA DE PAGO
                </button>
            </div>
        )
    }
}

export default Touchpoint;
