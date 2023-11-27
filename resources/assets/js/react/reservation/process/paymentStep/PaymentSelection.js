import React from 'react'
import StoreReservation from "../StoreReservation";
import Cash from "./Cash";
import Conekta from "./Conekta";
import Paypal from "./Paypal";
import Courtesy from "./Courtesy";
import {Tab} from "../../../common/tabs/Tab";
import {Tabs} from "../../../common/tabs/Tabs";
import Stripe from "./stripe";
import SrPago from "./SrPago";
import Terminal from "./Terminal";
import Generic from "./Generic";

export default class PaymentSelection extends React.Component {
    constructor() {
        super();
        this.state = {
            actualPayment: StoreReservation.get('payment'),
            payments: StoreReservation.get('paymentSelection'),
            firstPayment: false,
        };
    }

    /**
     *
     */
    UNSAFE_componentWillMount() {
        StoreReservation.initPayments();
        this.setState({
            actualPayment: StoreReservation.get('payment')
        });

        this.setState({
            firstPayment: (this.props.cartBool && this.props.total === 0 )
        });


        if (this.props.cartBool && this.props.total === 0) {
            let {payments} = this.state;
            let payment = payments.find(obj => {
                return obj.slug === "courtesy"
            });

            if (payment) {
                this.setState({
                    payments: [payment]
                });
                this.changePayment(payment);
            }
        }

    }

    /**
     *
     * @param payment
     */
    changePayment(payment) {
        let slug = payment.slug;
        let isValid = false;
        switch (slug) {
            case 'conekta':
                isValid = Conekta.isValidNow();
                break;
            case 'cash':
                isValid = Cash.isValidNow();
                break;
            case 'courtesy':
                isValid = Courtesy.isValidNow();
                break;
            case 'paypal':
                isValid = Paypal.isValidNow();
                break;
            case 'stripe':
                isValid = Stripe.isValidNow();
                break;
            case 'srpago':
                isValid = SrPago.isValidNow();
                break;
            case 'terminal':
                isValid = Terminal.isValidNow();
                break;
            default:
                isValid = true;
                break;
        }

        if (isValid) {
            StoreReservation.set('payment', payment);
        } else {
            StoreReservation.clearPayment();
        }

        let canSubscribe = this.canSubscribe(slug);
        StoreReservation.set('payment_slug', slug);
        StoreReservation.set('canSubscribe', canSubscribe);

        this.setState({
            actualPayment: payment,
        })
    }

    canSubscribe(slug) {
        return StoreReservation.canPaymentSubscribe(slug);
    }

    changePaymentSystemProperties(props) {
        StoreReservation.subscribe = props.recurringPayment;
        StoreReservation.recurringPayment = props.saveCard;
        StoreReservation.set('subscribe', StoreReservation.subscribe);
    }

    /**
     *
     *
     * @returns {XML}
     */
    render() {
        let {payments} = this.state;
        const {paymentFrequency, generalData} = this.props;
        let termsAndConditions = StoreReservation.isCheckedTerms();
        let hasRecurringPayment = StoreReservation.isPosibleToSubscribe();
        let lang = StoreReservation.get('lang');
        let images = StoreReservation.get('images');
        let actualPayment = this.state.actualPayment;

        if (!payments.length) {
            return (
                <div>{lang['noPayments']}</div>
            )
        }

        let style = this.state.firstPayment ? {overflowY: 'scroll', display: 'none'} : null;

        return (
            <div className="PaymentSelection" style={style}>
                <div className="ThankyouStep--purchase--title">{lang['payment.methodsSelect']}</div>
                <Tabs defaultActiveTabIndex={0} selectTab={this.changePayment.bind(this)}>
                    {payments.map((payment, index) => {
                        let slug = payment.slug;
                        let paymentName = !!lang[slug] ? lang[slug] : lang['noIdentify'];
                        let object = null;
                        let paymentImage = null;
                        switch (slug) {
                            case 'conekta':
                                object = <Conekta
                                    payment={payment}
                                    termsAndConditions={termsAndConditions}
                                    hasRecurringPayment={hasRecurringPayment}
                                    generalData={generalData}
                                    paymentFrequency={paymentFrequency}
                                    changePaymentSystemProperties={this.changePaymentSystemProperties.bind(this)}
                                />;
                                paymentImage = images['card'];
                                break;
                            case 'cash':
                                object = <Cash payment={payment}/>;
                                paymentImage = images['cash'];
                                break;
                            case 'paypal':
                                object = <Paypal payment={payment} termsAndConditions={termsAndConditions}/>;
                                paymentImage = images['paypal'];
                                break;
                            case 'courtesy':
                                object = <Courtesy payment={payment}/>;
                                paymentImage = images['gift'];
                                break;
                            case 'stripe':
                                object = <Stripe
                                    payment={payment}
                                    termsAndConditions={termsAndConditions  }
                                    hasRecurringPayment={hasRecurringPayment}
                                    generalData={generalData}
                                    paymentFrequency={paymentFrequency}
                                    changePaymentSystemProperties={this.changePaymentSystemProperties.bind(this)}
                                />;
                                paymentImage = images['card'];
                                break;

                            case 'srpago':
                                object = <SrPago
                                    payment={payment}
                                    termsAndConditions={termsAndConditions}
                                    hasRecurringPayment={hasRecurringPayment}
                                    generalData={generalData}
                                    paymentFrequency={paymentFrequency}
                                />;
                                break;

                            case 'terminal':
                                object = <Terminal payment={payment} termsAndConditions={termsAndConditions}/>;
                                break;
                            default:
                                object = <Generic payment={payment}/>;
                                paymentImage = images['cash'];
                            // object = lang['noIdentify']
                        }
                        let isSelectedNow = actualPayment && actualPayment.id === payment.id;

                        return (
                            <Tab
                                front={generalData.adminProfilesId}
                                text={ paymentName === 'Stripe' || paymentName === 'Conekta' ? lang['cardTab'] : paymentName }
                                image={paymentImage}
                                linkClassName=""
                                id={payment}
                                key={`PaymentSelection--${payment.id}`}
                            >
                                {object}
                            </Tab>
                        );
                    })}
                </Tabs>
            </div>
        )
    }
}
