import React from 'react'
import PaymentHandle from "./PaymentHandle";
import ConektaCreateCard from "./Conekta/ConektaCreateCard";
import StoreReservation from "../StoreReservation";
import NextStepPayment from "./NextStepPayment";
import CardConekta from "./CardConekta";

export default class Conekta extends PaymentHandle {
    constructor() {
        super();
        this.state = {
            loading: true,
            newTargetForm: false,
            card: null,
        }
    }

    /**
     *
     */
    componentWillMount() {
        this.checkConektaGlobal(this.preSelectCards);
    }

    /**
     *
     */
    preSelectCards() {
        let paymentSelected = StoreReservation.get('payment');

        if (paymentSelected && paymentSelected.slug === 'conekta') {
            let cards = this.getUserPaymentInfo();
            if (cards && cards.length) {
                this.changeCard(cards[0])
            } else {
                this.seeCardForm();
            }
        }
    }

    /**
     *
     */
    checkConektaGlobal(cb) {
        let component = this;
        if (!!window.Conekta) {
            let cards = this.getUserPaymentInfo();
            let props = {
                loading: false
            };
            if (!cards.length) {
                props.newTargetForm = true;
            }
            component.setState(props, cb)
        } else {
            setTimeout(function () {
                component.checkConektaGlobal();
            }, 1000)
        }
    }

    /**
     *
     * @param card
     */
    changeCard(card, isRecurrent = false) {
        let component = this;
        component.setState({
            card: card,
            newTargetForm: false,
        }, function () {
            component.nextStepButton();
        });

        StoreReservation.set('isRecurrent', isRecurrent);
    }

    seeCardForm() {
        let component = this;
        component.setState({
            card: null,
            newTargetForm: true,
        });

        StoreReservation.set('isRecurrent', false);
    }

    /**
     *
     */
    nextStepButton() {
        let config = this.getConfig();
        let payment = this.props.payment;
        let showFormNewCard = this.state.newTargetForm;
        let cardSelected = this.state.card;

        StoreReservation.set('confirmPaymentButton', (
            <NextStepPayment canGoNextStep={!!cardSelected} card={cardSelected} payment={payment}
                             showFormNewCard={showFormNewCard} config={config}/>
        ));
    }


    render() {
        let lang = StoreReservation.get('lang');
        if (this.state.loading) {
            return lang['error.ConektaNotLoad'];
        }

        let config = this.getConfig();
        let payment = this.props.payment;
        let cards = this.getUserPaymentInfo();
        let showFormNewCard = this.state.newTargetForm;
        let cardSelected = this.state.card;
        let images = StoreReservation.get('images');

        return (
            <div className="Conekta">
                <div className="PaymentSelection--Conekta">
                    {lang['conekta.info']}
                </div>
                <div className="Conekta--cardList">
                    <div
                        className={`Conekta--cardList--card Conekta--cardList--card__new ${(showFormNewCard ? 'Conekta--cardList--card__selected' : '')}`}
                        onClick={this.seeCardForm.bind(this)}>
                        {lang['conekta.newCard']}
                        <div className="Conekta--cardList--card__new--arrow">
                            <img src={images['previous']} alt=""/>
                        </div>
                    </div>
                    <div className="Conekta--cardList--content">
                        {cards.map((card) => {
                            let isSelected = !!cardSelected && card.id === cardSelected.id;
                            let recurrent_payment = StoreReservation.get('recurrent_payment');
                            let payment_data = recurrent_payment ? JSON.parse(recurrent_payment.payment_data) : null;
                            let card_id = (
                                payment_data !== null &&
                                typeof payment_data.card !== "undefined" &&
                                typeof payment_data.card.id !== "undefined"
                            )
                                ? payment_data.card.id
                                : '';
                            let isRecurrent = card_id === card.id;

                            return <CardConekta key={`Conekta--cardList--card--${card.id}`} card={card}
                                                isRecurrent={isRecurrent}
                                                isSelected={isSelected}
                                                changeCard={this.changeCard.bind(this, card, isRecurrent)}/>;
                        })}
                    </div>
                </div>
                <ConektaCreateCard config={config} payment={payment} show={showFormNewCard}/>
            </div>
        )
    }
}
