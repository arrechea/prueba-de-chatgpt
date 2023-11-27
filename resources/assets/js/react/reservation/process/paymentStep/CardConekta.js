import React from 'react'
import StoreReservation from "../StoreReservation";

export default class CardConekta extends React.Component {
    static get defaultProps() {
        return {
            card: null,
            isSelected: false,
            changeCard: () => {
            }
        }
    }

    /**
     *
     * @param brand
     * @param isSelected
     * @returns {*}
     */
    printBrand(brand, isSelected) {
        if (!brand) {
            return null;
        }
        let brandToLower = brand.toLowerCase();
        let images = StoreReservation.get('images');
        let image = '';
        switch (brandToLower) {
            case 'american_express':
                image = isSelected ? images['amex_color'] : images['amex'];
                break;
            case 'mc':
                image = isSelected ? images['master_color'] : images['master'];
                break;
            case 'visa':
                image = isSelected ? images['visa_color'] : images['visa'];
                break;
        }

        //Text
        if (!image) {
            return (
                <div className="Conekta--cardList--card--brand">
                    {brand}
                </div>
            )
        }
        //Image
        return (
            <div className="Conekta--cardList--card--brand">
                <img src={image} alt=""/>
            </div>
        )
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let card = this.props.card;
        let isSelected = this.props.isSelected;
        let isRecurrent = this.props.isRecurrent;

        return (
            <div
                className={`Conekta--cardList--card ${(isSelected ? 'Conekta--cardList--card__selected' : '')} ${isRecurrent ? 'recurrent' : ''}`}
                onClick={this.props.changeCard}>
                {this.printBrand(card.brand, isSelected)}
                <div className="Conekta--cardList--card--last4">
                    < span className = "Conekta--cardList--card--last4-x" >&bull;&bull;&bull;</span> {card.last4}
                </div>
            </div>
        )
    }
}
