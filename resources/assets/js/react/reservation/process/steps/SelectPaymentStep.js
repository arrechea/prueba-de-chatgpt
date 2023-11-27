import React from 'react'
import StoreReservation from "../StoreReservation";
import TermsInput from "./TermsInputs";
import PurchaseSummary from "../steps/PurchaseSummary";
import PrevStepPayment from "../paymentStep/PrevStepPayment";
import PaymentSelection from "../paymentStep/PaymentSelection";
import TotalsContainer from "../paymentStep/TotalsContainer";
import ConfirmPaymentButton from "../paymentStep/ConfirmPaymentButton";
import PromotionBox from "../paymentStep/promotionBox/PromotionBox";
import {formatMoney} from "../../../../helpers/FormatUtils";
import IconArrowDown from "../ui/iconArrowDown";
import ConfirmBuyStep from "./ConfirmBuyStep";
import ProcessingImage from "../common/ProcessingImage";
import BuySystemStep from "./BuySystemStep";

export default class SelectPaymentStep extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            location: StoreReservation.get('location'),
            discount: null,
            termsAndConditions: false,
            hasRecurringPayment: false,
            paymentFrequency: (this.props.cartItems.length === 1 && this.props.cartItems[0].amount === 1 && this.props.cartItems[0].hasOwnProperty('expiration_days')
                && this.props.cartItems[0].expiration_days > 0) ? this.props.cartItems[0].expiration_days : null,
            generalData: {
                companiesId: this.props.cartItems[0].companies_id,
                locationsId: StoreReservation.get('location').id,
                adminProfilesId: StoreReservation.get('admin') ? StoreReservation.get('admin').id : null,
                usersProfilesId: StoreReservation.get('user').id,
                usersId: StoreReservation.get('user').users_id
            },
            activeResume: false,
            hideOnDesktop: false,
            loading: true,
            errores: null,
        };
        StoreReservation.addSegmentedListener([
            'isValidDiscountCode',
            'hasDiscountCode',
            'checkedTerms',
        ], this.ListenerStore.bind(this));

        StoreReservation.set('product', this.props.cartItems);
        this.handleDiscount = this.handleDiscount.bind(this);
        this.toggleClass = this.toggleClass.bind(this);
    }

    componentDidMount() {
        let totalAPagar = StoreReservation.getTotalAmount();
        let component = this;
        if (totalAPagar <= 0) {
            StoreReservation.setStep(<ConfirmBuyStep/>, 'ConfirmBuyStep', true);
        } else {
            window.addEventListener("resize", component.resize.bind(component));
            component.resize();

            BuySystemStep.sendForm(null, () => {
                component.setState({
                    loading: false
                });
            }, (response) => {
                let errores = [];
                let lang = StoreReservation.lang;
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
                    'loading': false,
                    'errores': (
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
            }, true)
        }
    }

    ListenerStore() {
        this.forceUpdate();
    }

    toggleClass() {
        this.setState({
            activeResume: !this.state.activeResume
        });
    }

    /**
     *
     */
    UNSAFE_componentWillMount() {
        this.setState({hasRecurringPayment: !!(this.state.paymentFrequency)});
        let totalAPagar = StoreReservation.getTotalAmount();
        if (totalAPagar <= 0) {
            // StoreReservation.set('step', <ConfirmBuyStep cartItems={this.props.cartItems}/>);
        }
        if (StoreReservation.isPosibleToSubscribe()) {
            //Forzamos subscripcion
            StoreReservation.subscribe = true;
        }
    }

    handleDiscount(amount) {
        this.setState({
            discount: amount
        });
    }

    resize() {
        let hideOnDesktop = (window.innerWidth <= 992);
        if (hideOnDesktop !== this.state.hideOnDesktop) {
            this.setState({hideOnDesktop: hideOnDesktop});
        }
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let {hideOnDesktop, loading, errores} = this.state;
        if (loading) {
            return (
                <div className="SelectPaymentStep paymentSelection">
                    <ProcessingImage/>
                </div>
            );
        }
        let lang = StoreReservation.get('lang');

        let cart = StoreReservation.getCart(lang);
        cart.discount = this.state.discount;

        let cartBool = (this.props.cartItems.length > 0);
        let total = 0;
        for (let item of this.props.cartItems) {
            total += item.price_final * item.amount;
        }
        let currency = StoreReservation.get('currency');

        return (
            <div className="SelectPaymentStep paymentSelection">
                <div className="SelectPaymentStep--left paymentSelection__method">
                    <div className="gs-paymentMethod">
                        <div className="gs-paymentMethod__header">
                            <PrevStepPayment cartItems={this.props.cartItems}/>
                            {errores ? null : (
                                <div className="gs-title">{lang['payments.title']}</div>
                            )}
                        </div>

                        <div className="gs-paymentMethod__body">
                            {errores ? errores : (
                                <PaymentSelection
                                    termsAndConditions={this.state.termsAndConditions}
                                    hasRecurringPayment={this.state.hasRecurringPayment}
                                    paymentFrequency={this.state.paymentFrequency}
                                    generalData={this.state.generalData}
                                />
                            )}
                        </div>
                    </div>
                </div>
                {errores ? null : (
                    <div
                        className={'SelectPaymentStep--right paymentSelection__resume ' + (this.state.activeResume ? 'summaryTab-open' : 'summaryTab-close')}
                    >
                        <div className="gs-summary has-checkbox">
                            <div className="gs-summary__header" onClick={this.toggleClass}>
                                <h3 className="CreateReservationFancy--title">{this.state.location.brand.name}</h3>
                                <span className="gs-summary__notifications">{this.props.cartItems.length}</span>
                                <span className="gs-summary__tabPrice"> {currency.prefijo} {formatMoney(total, 0)}.00</span>
                                {/* <h5>{this.state.location.name}</h5> */}
                                <IconArrowDown/>
                            </div>
                            <hr className="gs-summary__divider"/>
                            <div className="gs-summary__body">
                                <PurchaseSummary purchase={cart}/>
                            </div>

                            <div className="gs-summary__terms">
                                <PromotionBox cart={this.props.cartItems} discount={this.handleDiscount}/>
                                <TermsInput/>
                            </div>

                            <div className="gs-summary__footer">
                                <TotalsContainer purchase={cart}/>
                            </div>
                        </div>
                    </div>
                )}
                {errores ? null : (

                    <div
                        className={'touchpoint has-checkbox ' + (this.state.activeResume ? 'summaryTab-open' : 'summaryTab-close')}
                    >
                        <ConfirmPaymentButton/>
                    </div>
                )}
            </div>
        )
    }
}
