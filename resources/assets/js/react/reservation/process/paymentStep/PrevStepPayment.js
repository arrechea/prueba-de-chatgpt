import React from 'react'
import StoreReservation from "../StoreReservation";
import SelectProductsStepTemplate from "../steps/SelectProductsStepTemplate";
import IconLeftArrow from '../ui/iconLeftArrow';

export default class PrevStepPayment extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            location: StoreReservation.get('location'),
            admin: StoreReservation.get('admin'),
        };
    }

    /**
     * Go to Payment methods
     */
    goToProducts() {
        StoreReservation.set('step', <SelectProductsStepTemplate cartItems={this.props.cartItems}/>);
    }

    /**
     *
     * @returns {*}
     */
    render() {
        const {location, admin} = this.state;
        let lang = StoreReservation.get('lang');
        let images = StoreReservation.get('images');
        let CompanyName = location.company.name.toUpperCase();
        let back = StoreReservation.get('back');
        if (back) {
            return null;
        }

        return (
            <div className="gs-button is-return" type="button" onClick={this.goToProducts.bind(this)}>
                <IconLeftArrow/>
                {
                    admin != null
                        ? lang['goToProducts.return.admin']
                        : lang['goToProducts.return']
                }
            </div>
        );
    }
}
