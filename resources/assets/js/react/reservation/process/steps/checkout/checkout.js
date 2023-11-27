import React, {Component} from 'react';
import StoreReservation from "../../StoreReservation";
import CartSummary from "./CartSummary";
import {formatMoney} from "../../../../../helpers/FormatUtils";
import IconArrowDown from "../../ui/iconArrowDown";

class CheckOut extends Component {
    constructor(props) {
        super(props);
        this.state = {
            location: StoreReservation.get('location'),
            activeResume: false,
        };

        this.toggleClass = this.toggleClass.bind(this);
    }

    toggleClass() {
        this.setState({
            activeResume: !this.state.activeResume
        });
    }

    render() {
        let total = 0;
        for (let item of this.props.cart) {
            total += item.price_final * item.amount;
        }
        let currency = StoreReservation.get('currency');
        return (
            <div className={'check-out-container productSelection__summary ' + (this.state.activeResume ? 'summaryTab-open' : 'summaryTab-close')}>
                <div className="gs-summary">
                    <div className="gs-summary__header" onClick={this.toggleClass}>
                        <h3 className="CreateReservationFancy--title">{this.state.location.brand.name}</h3>
                        <span className="gs-summary__notifications">{this.props.cart.length}</span>
                        <span className="gs-summary__tabPrice"> {currency.prefijo} {formatMoney(total, 0)}.00</span>
                        {/* <h5>{this.state.location.name}</h5> */}
                        <IconArrowDown />
                    </div>
                    <hr className="gs-summary__divider"/>
                    <div className="gs-summary__body">
                        <CartSummary cart={this.props.cart} handleCartDelete={this.props.handleCartDelete} />
                    </div>

                            <div className="gs-summary__footer">
                                <h5>{currency.prefijo} {formatMoney(total, 0)}.00</h5>
                            </div>


                </div>
            </div>
        )
    }
}

export default CheckOut;
