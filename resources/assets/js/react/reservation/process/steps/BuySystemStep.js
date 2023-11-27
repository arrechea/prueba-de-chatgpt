import React from 'react'
import ProcessingImage from "../common/ProcessingImage";
import $ from 'jquery'
import StoreReservation from "../StoreReservation";
import ThankyouStep from "./ThankyouStep";
import SelectProductsStepTemplate from "./SelectProductsStepTemplate";

export default class BuySystemStep extends React.Component {
    constructor() {
        super();
        this.state = {
            'processing': true,
            'print': null,
            purchaseData: null
        };
    }

    /**
     *
     */
    componentDidMount() {
        if (StoreReservation.isProcessing) {
            return;
        }
        let component = this;

        StoreReservation.set('isProcessing', true);
        BuySystemStep.sendForm(
            component,
            function (response) {
                StoreReservation.set('isProcessing', false);
                component.setState({
                    'processing': false,
                    'print': <ThankyouStep reservation={response.reservation} purchase={response.purchase}/>
                });
                let event = new CustomEvent("buq__reservation_purchase_complete", {
                    'detail': {
                        reservation: response.reservation,
                    }
                });
                document.dispatchEvent(event);
            },
            function (response) {
                StoreReservation.set('isProcessing', false);
                let errores = [];
                let lang = StoreReservation.get('lang');

                try {

                    let messages = response.responseJSON.errors;

                    for (let errorSlug in messages) {
                        if (messages.hasOwnProperty(errorSlug)) {
                            messages[errorSlug].forEach(function (text, index) {
                                errores.push(
                                    <li key={`BuySystemStep--error-${errorSlug}--${index}`}>
                                        {text}
                                    </li>
                                )
                            });
                        }
                    }
                } catch (e) {
                    errores = <div dangerouslySetInnerHTML={{__html: response.responseText}}/>;
                }

                component.setState({
                    'processing': false,
                    'print': (
                        <div className="BuySystemStep">
                            <div className="CreateReservationFancy--title">{lang['sorry']}</div>
                            <div className="CreateReservationFancy--errors">
                                <ul>
                                    {errores}
                                </ul>
                            </div>
                        </div>
                    )
                })
            }
        );
    }

    static sendForm(component, successCB, errorCB, test) {

        let url = StoreReservation.get('urlReservation');
        let _token = StoreReservation.get('csrf');
        let users_id = StoreReservation.get('user');
        let meetings_id = StoreReservation.get('meeting');
        let meeting_data = StoreReservation.get('meeting_data');
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');
        let memberships_id = StoreReservation.get('membership');
        let combos_id = StoreReservation.get('combo');
        let payments_id = StoreReservation.get('payment');
        let payment_data = StoreReservation.get('payment_data');
        let cart = StoreReservation.get('product');
        let lang = StoreReservation.get('lang');
        let signature = StoreReservation.get('signature');
        let giftCode = StoreReservation.get('giftCode');
        let isGiftCard = StoreReservation.get('isGiftCard');
        let discountCode = StoreReservation.getDiscountCode();
        let invited_data = StoreReservation.get('invited_data');
        //El usuario se subscribe solamente si puede y si selecciona la opcion
        let subscribe = StoreReservation.isUserWantSubscribe();
        // let subscribe = this.props.recurrent_payment;
        let set_payment = StoreReservation.get('set_payment');

        if (users_id) {
            users_id = users_id.id;
        }
        if (meetings_id) {
            meetings_id = meetings_id.id;
        }
        if (memberships_id) {
            memberships_id = memberships_id.id;
        }
        if (combos_id) {
            combos_id = combos_id.id;
        }
        if (payments_id) {
            payments_id = payments_id.id;
        }

        let data = {
            '_token': _token,
            'users_id': users_id,
            'meetings_id': meetings_id,
            'meeting_data': meeting_data,
            'map_objectsSelected': map_objectsSelected,
            'memberships_id': [],
            'memberships_amounts': [],
            'combos_id': [],
            'combos_amounts': [],
            'products_id': [],
            'products_amounts': [],
            'payment_types_id': payments_id,
            'payment_data': payment_data,
            'cart': cart,
            'invited_data': invited_data,
            combo: cart.filter(combo => {
                if (combo.type === 'combo') return combo
            }),
            membership: cart.filter(membership => {
                if (membership.type === 'membership') return membership
            }),
            product: cart.filter(product => {
                if (product.type === 'product') return product
            }),
            'signature': signature,
            'giftCode': isGiftCard === true ? giftCode : null,
            'discountCode': discountCode ? discountCode.code : null,
            'subscribe': subscribe,
            'set_payment': set_payment,
            'subscriptionId': component ? component.props.subscriptionId : null,
            test: !!test,
        };
        if (component) {
            component.setState({
                purchaseData: data
            });
        }
        let headers = StoreReservation.getHeaders();

        data.combo.forEach(element => {

            data.combos_id.push(element.id);
            data.combos_amounts.push(element.amount);

        });

        data.membership.forEach(element => {

            data.memberships_id.push(element.id);
            data.memberships_amounts.push(element.amount);

        });

        data.product.forEach(element => {
            data.products_id.push(element.id);
            data.products_amounts.push(element.amount);
        });

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            headers: headers,
            success: successCB,
            error: errorCB,
            dataType: 'json'
        });
    }

    /**
     *
     */
    goToSelectProduct() {
        StoreReservation.setStep(<SelectProductsStepTemplate/>, 'SelectProductsStepTemplate');
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        if (this.state.processing) {
            return (
                <div className="BuySystemStep">
                    <ProcessingImage/>
                </div>
            )
        }
        return this.state.print;
    }
}
