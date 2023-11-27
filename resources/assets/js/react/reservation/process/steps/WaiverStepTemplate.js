import React from 'react'
import StoreReservation from "../StoreReservation";
import PreMeetingStepTemplate from "./PreMeetingStepTemplate";
import NextStepWaiverTemplate from "../waiverStep/NextStepWaiverTemplate";
import ReactSignature from "../common/signature/ReactSignature";

export default class WaiverStepTemplate extends React.Component {
    constructor() {
        super();
        this.state = {
            waiver_text: '',
            canGoNextStep: false,
        };
        this.checkWaiver();
    }

    componentDidMount() {
        this.checkWaiver();
    }

    /**
     *
     */
    checkWaiver() {
        //Check waiver
        let user_waivers = StoreReservation.get('user_waivers');
        let location = StoreReservation.get('location');

        let locationNeedWaiver = this.checkForzeWaiver(location);
        let brandNeedWaiver = this.checkForzeWaiver(location.brand);
        let waiver_text = '';

        if (
            (!locationNeedWaiver && !brandNeedWaiver)
            ||
            this.UserHasWaiverInLocation(user_waivers, location.id)
            ||
            this.UserHasWaiverInBrand(user_waivers, location.brand.id)
        ) {
            //Cambiamos de paso
            StoreReservation.setStep(<PreMeetingStepTemplate/>, 'PreMeetingStepTemplate', true);
            return null;
        }

        //Set waiver text
        if (brandNeedWaiver) {
            //waiverText
            waiver_text = location.brand.waiver_text;
        } else if (locationNeedWaiver) {
            //waiverText
            waiver_text = location.waiver_text;
        }

        this.setState({
            waiver_text: waiver_text
        });
    }

    /**
     *
     * @param user_waivers
     * @param locationId
     */
    UserHasWaiverInLocation(user_waivers, locationId) {
        let response = false;
        if (user_waivers && user_waivers.length) {
            user_waivers.forEach((waiver) => {
                if (waiver.locations_id === locationId) {
                    response = true;
                }
            });
        }
        return response;
    }

    /**
     *
     * @param user_waivers
     * @param brandId
     */
    UserHasWaiverInBrand(user_waivers, brandId) {
        let response = false;
        if (user_waivers && user_waivers.length) {
            user_waivers.forEach((waiver) => {
                if (waiver.locations_id === brandId) {
                    response = true;
                }
            });
        }
        return response;
    }

    /**
     *
     * @param model
     * @returns {*}
     */
    checkForzeWaiver(model) {
        return model.waiver_forze === true;
    }

    /**
     *
     */
    signatureMouseUp() {
        let signature = this.refs.signature;
        let isNotEmpty = !signature.isEmptyCanvas();
        if (isNotEmpty !== this.state.canGoNextStep) {
            this.setState({
                canGoNextStep: isNotEmpty
            })
        }
    }

    /**
     *
     */
    cleanCanvas() {
        let signature = this.refs.signature;
        signature.handleClear();
        this.setState({
            canGoNextStep: false
        })
    }

    /**
     *
     */
    saveSignature() {
        let signature = this.refs.signature;
        let toUrl = signature.toDataURL();
        StoreReservation.set('signature', toUrl);
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let lang = StoreReservation.get('lang');

        return (
            <div className="WaiverStep">
                <div className="CreateReservationFancy--title">{lang['waiver.title']}</div>
                <div className="WaiverStep--content">
                    <div className="WaiverStep--text">
                        {this.state.waiver_text}
                    </div>
                </div>
                <div className="WaiverStep--signature">
                    <div className="CreateReservationFancy--title">{lang['signature']}</div>
                    <ReactSignature ref="signature" handleMouseUp={this.signatureMouseUp.bind(this)}/>
                </div>
                <div className="AppReservation--steps">
                    <button className="AppReservation--button AppReservation--button--prev" type="button"
                            onClick={this.cleanCanvas.bind(this)}>
                        {lang['clean']}
                    </button>
                    <NextStepWaiverTemplate canGoNextStep={this.state.canGoNextStep}
                                            saveSignature={this.saveSignature.bind(this)}/>
                </div>
            </div>
        )
    }
}
