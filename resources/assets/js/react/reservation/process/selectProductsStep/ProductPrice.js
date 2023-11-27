import React from 'react'
import StoreReservation from "../StoreReservation";
export default class ProductPrice extends React.Component {
    static get defaultProps() {
        return {
            currency: {
                prefijo: "$",
            },
            product: {}
        }
    }

    formatPrices(price) {
        let producto = this.props.product;
        let lang = StoreReservation.get('lang');
        // let currency = this.props.currency;
        let currency = StoreReservation.get('currency');
        let formatedPrice = StoreReservation.moneyFormat(price);
        let precioText = lang['free'];

        if (price > 0) {
            let priceExploded = formatedPrice.split('.');

            precioText = [
                <span className="CombosSelector--combo--price--prefix" key={`Product--price--${producto.id}--prefix`}>{currency.prefijo}</span>,
                <span className="CombosSelector--combo--price--number" key={`Product--price--${producto.id}--number`}>{priceExploded[0]}</span>,
                <span className="CombosSelector--combo--price--decimals" key={`Product--price--${producto.id}--decimals`}>.{priceExploded[1]}</span>,
            ];
        }

        return precioText;
    }

    /**
     *
     * @returns {Array}
     */
    render() {
        let currency = this.props.currency;
        let producto = this.props.product;

        //productProperties
        let discountNumber = producto.discount_number;
        let hasDiscount = producto.has_discount;

        //generales
        let respuesta = [];

        //classes
        let firstPriceClass = hasDiscount ?
            'CombosSelector--combo--price CombosSelector--combo--price__withDiscount' :
            'CombosSelector--combo--price';
        let secondPriceClass = 'CombosSelector--combo--price';
        if (producto.price <= 0) {
            firstPriceClass += ' CombosSelector--combo--price--free';
        }
        if (producto.price_final <= 0) {
            secondPriceClass += ' CombosSelector--combo--price--free';
        }

        //PriceText
        let precioText = this.formatPrices(producto.price);
        let precioFinalText = this.formatPrices(producto.price_final);

        respuesta.push(
            <div
                className={firstPriceClass}
                key={`Product--price--${producto.id}`}>{precioText}</div>
        );
        if (hasDiscount) {
            let discountType = producto.discount_type;
            //Price
            respuesta.push(
                <div
                    className={secondPriceClass}
                    key={`Product--price--final--${producto.id}`}>{precioFinalText}</div>
            );
            //Budget
            switch (discountType) {
                case 'price':
                    respuesta.push(
                        <div
                            className="CombosSelector--combo--budget"
                            key={`Product--price--budget--${producto.id}`}>
                            -{currency.prefijo}{discountNumber}</div>
                    );
                    break;
                case 'percent':
                    respuesta.push(
                        <div
                            className="CombosSelector--combo--budget"
                            key={`Product--price--budget--${producto.id}`}>-{discountNumber}%</div>
                    );
                    break;
            }
        }

        return respuesta;
    }
}
