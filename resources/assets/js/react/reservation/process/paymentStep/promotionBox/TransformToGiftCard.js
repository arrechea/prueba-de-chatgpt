import React from 'react'
import StoreReservation from "../../StoreReservation";
import ProcessingImage from "../../common/ProcessingImage";
import IconCheckout from "../../ui/iconCheckout";
import $ from 'jquery'

export default class TransformToGiftCard extends React.Component {
    constructor() {
        super();
        this.state = {
            isGiftCard: StoreReservation.get('isGiftCard'),
            giftCode: StoreReservation.get('giftCode'),
            isManualGiftCode: StoreReservation.get('isManualGiftCode'),
            isCorrectGiftCardCode: StoreReservation.get('isCorrectGiftCardCode'),
            isPendingToCheckGiftCardCode: StoreReservation.get('isPendingToCheckGiftCardCode'),
            loading: false,
        };
        this.timerCheckCode = null;

        StoreReservation.addSegmentedListener([
            'isGiftCard',
            'giftCode',
            'isManualGiftCode',
            'isCorrectGiftCardCode',
            'isPendingToCheckGiftCardCode'
        ], this.ListenerStore.bind(this));
    }

    /**
     * Listener Store
     * @constructor
     */
    ListenerStore() {
        this.setState({
            isGiftCard: StoreReservation.get('isGiftCard'),
            giftCode: StoreReservation.get('giftCode'),
            isManualGiftCode: StoreReservation.get('isManualGiftCode'),
            isCorrectGiftCardCode: StoreReservation.get('isCorrectGiftCardCode'),
            isPendingToCheckGiftCardCode: StoreReservation.get('isPendingToCheckGiftCardCode'),
        });
    }

    /**
     *
     * @param cb
     */
    generateCode(cb) {
        let component = this;
        let lang = StoreReservation.get('lang');

        component.setState({
            loading: true,
        }, () => {
            let url = StoreReservation.urlGenerateCode;

            $.ajax({
                type: "GET",
                url: url,
                headers: StoreReservation.getHeaders(),
                success: function (response) {
                    component.setState({
                        loading: false,
                    }, () => {
                        cb(response);
                    });
                },
                error: function (response) {
                    component.setState({
                        loading: false,
                    }, () => {
                        alert(lang['error.giftCard.error']);
                    });
                },
                dataType: 'json'
            });
        });
    }

    /**
     *
     */
    checkNewCode() {
        let lang = StoreReservation.get('lang');

        let component = this;
        let newCode = component.refs.inputCode.value;

        let url = StoreReservation.urlCheckGiftCode;
        url = url.replace('_|_', newCode);

        $.ajax({
            type: "GET",
            url: url,
            headers: StoreReservation.getHeaders(),
            success: function () {
                StoreReservation.isPendingToCheckGiftCardCode = false;
                StoreReservation.set('isCorrectGiftCardCode', true);
            },
            error: function () {
                StoreReservation.isPendingToCheckGiftCardCode = false;
                StoreReservation.set('isCorrectGiftCardCode', false);
            },
            dataType: 'json'
        });
    }

    /**
     *
     */
    changeGiftCode() {
        let component = this;
        let newCode = component.refs.inputCode.value;

        //reset timer
        let timer = component.timerCheckCode;
        if (timer) {
            clearTimeout(timer);
        }

        StoreReservation.giftCode = newCode;
        StoreReservation.isManualGiftCode = true;
        StoreReservation.set('isPendingToCheckGiftCardCode', true, () => {
            component.timerCheckCode = setTimeout(component.checkNewCode.bind(component), 2000)
        });
    }

    /**
     *
     */
    setNewCodeInStore() {
        let component = this;

        StoreReservation.isGiftCard = true;
        StoreReservation.set('isPendingToCheckGiftCardCode', true, () => {
            component.generateCode((newCode) => {
                //reset with this code
                StoreReservation.isManualGiftCode = false;
                StoreReservation.isCorrectGiftCardCode = true;
                StoreReservation.isPendingToCheckGiftCardCode = false;
                StoreReservation.set('giftCode', newCode)
            });
        });
    }

    /**
     * Change giftCard or not giftCard
     */
    changeCheckbox() {
        let {isGiftCard} = this.state;
        let newState = !isGiftCard;
        if (newState === true) {
            //we need to generate code
            this.setNewCodeInStore();
        } else {
            //we need to set reset options
            StoreReservation.resetGiftCardOptions();
        }
    }

    render() {
        if (StoreReservation.hasMeeting()) {
            return null;
        }
        let lang = StoreReservation.get('lang');
        let {
            isGiftCard,
            giftCode,
            isManualGiftCode,
            loading,
            isCorrectGiftCardCode,
            isPendingToCheckGiftCardCode
        } = this.state;
        let inputExtraClass = isManualGiftCode ? '' : 'PromotionBox--content--code__gray';
        let images = StoreReservation.get('images');
        let cssChecked = isGiftCard === true ? 'PromotionBox--content__checked' : '';

        return (
            <div className={`gs-PromotionBox__container ${cssChecked}`}>
                <div className="gs-PromotionBox__header">
                    <input
                        type="checkbox"
                        checked={isGiftCard === true}
                        onChange={this.changeCheckbox.bind(this)}
                        id="promotion_gift_card"
                    />
                    <IconCheckout />
                    <label htmlFor="promotion_gift_card">{lang['giftcard.convert']}</label>
                </div>
                {loading ? (
                    <ProcessingImage/>
                ) : null}

                {isGiftCard && !loading ? (
                    <div className="gs-PromotionBox__body">
                        <input
                            type="text" value={giftCode ? giftCode : ''}
                            onChange={this.changeGiftCode.bind(this)}
                            className={`PromotionBox--content--code ${inputExtraClass}`}
                            ref="inputCode"
                        />
                    </div>
                ) : null}

                {isGiftCard && !loading ? (
                    <div className="gs-PromotionBox__footer">
                        <div className="PromotionBox__correctOrNot">
                            {
                                isPendingToCheckGiftCardCode ?
                                    <span className="PromotionBox--content--correctOrNot--checking">{lang['code.checking']}</span> :
                                    (isCorrectGiftCardCode ?
                                        <span className="PromotionBox--content--correctOrNot--valid">{lang['code.valid']}</span> :
                                        <span className="PromotionBox--content--correctOrNot--invalid">{lang['code.invalid']}</span>
                                    )
                            }
                        </div>

                        <div className="PromotionBox--content--redoCode"
                                onClick={this.setNewCodeInStore.bind(this)}>
                            <img src={images['reload']} alt=""/>
                        </div>
                    </div>
                ) : null}
            </div>
        )
    }
}
