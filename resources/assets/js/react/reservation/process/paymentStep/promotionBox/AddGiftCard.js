import React from 'react'
import StoreReservation from "../../StoreReservation";
import IconCheckout from "../../ui/iconCheckout"
import $ from 'jquery'

export default class content extends React.Component {
    /**
     *
     * @param props
     */
    constructor(props) {
        super(props);
        this.state = {
            hasDiscountCode: StoreReservation.get('hasDiscountCode'),
            discountCode: StoreReservation.get('discountCode'),
            isValidDiscountCode: StoreReservation.get('isValidDiscountCode'),
            isPendingToCheckDiscountCode: StoreReservation.get('isPendingToCheckDiscountCode')
        };
        this.timerCheckCode = null;

        StoreReservation.addSegmentedListener([
            'hasDiscountCode',
            'discountCode',
            'isValidDiscountCode',
            'isPendingToCheckDiscountCode',
        ], this.ListenerStore.bind(this));

        //this.props.discount(200);
        this.checkNewCode = this.checkNewCode.bind(this);

    }

    /**
     * Listener Store
     * @constructor
     */
    ListenerStore() {
        this.setState({
            hasDiscountCode: StoreReservation.get('hasDiscountCode'),
            discountCode: StoreReservation.get('discountCode'),
            isValidDiscountCode: StoreReservation.get('isValidDiscountCode'),
            isPendingToCheckDiscountCode: StoreReservation.get('isPendingToCheckDiscountCode'),
        });
    }

    /**
     *
     */
    changeCheckbox() {
        let {hasDiscountCode} = this.state;
        StoreReservation.set('hasDiscountCode', !hasDiscountCode);


    }

    checkNewCode() {
        let component = this;
        let newCode = component.refs.inputCode.value;

        if (newCode === '') {
            StoreReservation.isPendingToCheckDiscountCode = false;
            StoreReservation.set('isValidDiscountCode', false);
            return null;
        }

        let url = StoreReservation.urlCheckDiscountCode;
        url = url.replace('_|_', newCode);
        let combo = this.props.cart.filter(cartItem => cartItem.type === "combo" );
        let membership = this.props.cart.filter(cartItem => cartItem.type === "membership" );
        let product = this.props.cart.filter(cartItem => cartItem.type === "product" );

        product.forEach(element => {
            let data = {
                product: element ? element.id : null
            };

            $.ajax({
                type: "GET",
                url: url,
                headers: StoreReservation.getHeaders(),
                data: data,
                success: function (discountObject) {
                    StoreReservation.applyDiscountCode(discountObject);
                    //this.props.discount(StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true)));
                    component.props.discount(StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true)));
                },
                error: function (error) {
                    console.log(error);//todo revisar error
                    StoreReservation.isPendingToCheckDiscountCode = false;
                    StoreReservation.set('isValidDiscountCode', false);
                },
                dataType: 'json'
            });
        });

        combo.forEach(element => {
            let data = {
                combo: element ? element.id : null
            };

            $.ajax({
                type: "GET",
                url: url,
                headers: StoreReservation.getHeaders(),
                data: data,
                success: function (discountObject) {
                    StoreReservation.applyDiscountCode(discountObject);
                    //this.props.discount(StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true)));
                    component.props.discount(StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true)));
                },
                error: function (error) {
                    console.log(error);//todo revisar error
                    StoreReservation.isPendingToCheckDiscountCode = false;
                    StoreReservation.set('isValidDiscountCode', false);
                },
                dataType: 'json'
            });
        });

        membership.forEach(element => {
            let data = {
                membership: element ? element.id : null
            };

            $.ajax({
                type: "GET",
                url: url,
                headers: StoreReservation.getHeaders(),
                data: data,
                success: function (discountObject) {
                    StoreReservation.applyDiscountCode(discountObject);
                    component.props.discount(StoreReservation.discountInTotalForDiscountCode(StoreReservation.getTotalAmount(true)));
                },
                error: function (error) {
                    console.log(error);//todo revisar error
                    StoreReservation.isPendingToCheckDiscountCode = false;
                    StoreReservation.set('isValidDiscountCode', false);
                },
                dataType: 'json'
            });
        });

    }

    /**
     *
     */
    changeDiscountCode() {
        let component = this;
        let newCode = component.refs.inputCode.value;

        //reset timer
        let timer = component.timerCheckCode;
        if (timer) {
            clearTimeout(timer);
        }

        StoreReservation.discountCode = newCode;
        StoreReservation.set('isPendingToCheckDiscountCode', true, () => {
            component.timerCheckCode = setTimeout(component.checkNewCode.bind(component), 2000)
        });
    }

    render() {
        let lang = StoreReservation.get('lang');
        let {
            hasDiscountCode,
            discountCode,
            isValidDiscountCode,
            isPendingToCheckDiscountCode,
        } = this.state;
        let images = StoreReservation.get('images');
        let cssChecked = hasDiscountCode ? 'PromotionBox--content__checked' : '';

        return (
            <div className={`gs-PromotionBox__container ${cssChecked}`}>
                <div className="gs-PromotionBox__header">
                    <input
                        type="checkbox"
                        checked={hasDiscountCode === true}
                        onChange={this.changeCheckbox.bind(this)}
                        id="promotion_discount_code"
                    />
                    <IconCheckout />
                    <label htmlFor="promotion_discount_code">{lang['discount_code']}</label>
                </div>

                {hasDiscountCode ? (
                    <div className="gs-PromotionBox__body">
                        <input
                            type="text" value={discountCode ? discountCode : ''}
                            onChange={this.changeDiscountCode.bind(this)}
                            className={`PromotionBox--content--code`}
                            ref="inputCode"
                        />
                    </div>
                ) : null}

                {hasDiscountCode ? (
                    <div className="gs-PromotionBox__footer">
                        <div className="PromotionBox--content--correctOrNot">
                            {
                                isPendingToCheckDiscountCode ?
                                    <span
                                        className="PromotionBox--content--correctOrNot--checking">{lang['code.checking']}</span> :
                                    (isValidDiscountCode ?
                                            <span
                                                className="PromotionBox--content--correctOrNot--valid">{lang['code.valid']}</span> :
                                            (discountCode ?
                                                <span
                                                    className="PromotionBox--content--correctOrNot--invalid">{lang['code.invalid']}</span>
                                                : '')
                                    )
                            }
                        </div>
                    </div>
                ) : null}

            </div>
        )
    }
}
