import React from 'react';
import StoreReservation from "../../StoreReservation";
import {formatMoney} from "../../../../../helpers/FormatUtils"

// import "./checkout.css";

export default class CartSummary extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            location: StoreReservation.get('location'),
            activeResume: false,
        };
    }

    render() {
        let total = 0;
        for (let item of this.props.cart) {
            total += item.price_final * item.amount;
        }
        let currency = StoreReservation.get('currency');
        return (
            <div className="gs-summary__cart gs-cart">
                {(total != 0 || this.props.cart.length > 0) ?
                    this.props.cart.map((cart, index) => {
                        return (
                            <div className="gs-cartItem" key={index}>
                                <div key={cart.id} className="gs-cartItem__container">
                                    <div className="gs-cartItem__header">
                                        <h4>{cart.name}</h4>
                                        <span className="gs-price"> {currency.prefijo} {formatMoney((cart.price_final * cart.amount), 0)}</span>
                                    </div>
                                    <div className="gs-cartItem__footer">
                                        <ul>
                                            <li>
                                                <span className="gs-amount">Cantidad: {cart.amount}</span>
                                            </li>
                                            <li>
                                                <button className="gs-link" onClick={() => this.props.handleCartDelete(cart.id)}>Eliminar</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        )
                    })
                :
                    <p>No hay productos</p>
                }
            </div>
        )
    }
}
