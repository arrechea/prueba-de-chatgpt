import React from 'react'
import StoreReservation from "../../reservation/process/StoreReservation";
import moment from 'moment-timezone'
import 'owl.carousel';
import 'owl.carousel/dist/assets/owl.carousel.min.css';
import 'owl.carousel/dist/assets/owl.theme.default.min.css';

export default class UserCredits extends React.Component {
    constructor() {
        super();
        this._slider = null;
    }

    /**
     *
     * @param prevProps
     * @param prevState
     */
    componentDidUpdate(prevProps, prevState) {
        this._actualizarSlider();
    }

    /**
     *
     */
    componentDidMount() {
        this._actualizarSlider();
    }

    /**
     *
     * @private
     */
    _actualizarSlider() {
        this._slider = $(".WidgetBUQ--UserCredits").owlCarousel({
            items: 1,
            center: true,
            dots: false,
            nav: false,
        });
    }

    _nextSlide() {
        if (this._slider) {
            this._slider.trigger('next.owl.carousel');
        }
    }

    _prevSlide() {
        if (this._slider) {
            this._slider.trigger('prev.owl.carousel');
        }
    }

    render() {
        let {
            credits
        } = this.props;

        if (!credits || !Array.isArray(credits)) {
            return null;
        } else {
            let lang = StoreReservation.get('lang');
            return (
                <div className="WidgetBUQ--UserCredits owl-carousel">
                    {credits.map((creditos, index) => {
                        let date = moment(creditos.expiration_date);
                        let locale = lang['locale'];
                        moment.locale(lang['locale']);
                        let fmt = "DD/MMM/YYYY";
                        let start_date_moment = date.format(fmt);

                        return (
                            <div
                                key={`WidgetBUQ--UserCredits--${index}`}
                                className="WidgetBUQ--UserCredits--block"
                            >
                                <div className="WidgetBUQ--UserCredits--block--left">
                                    <div className="WidgetBUQ--UserCredits--block--number">
                                        {creditos.total}
                                    </div>
                                </div>
                                <div className="WidgetBUQ--UserCredits--block--right">
                                    <strong>{lang['widget.profile.credits']}</strong>
                                    <br/>
                                    <small>
                                        {lang['widget.profile.expiration'] + ' '}
                                        {start_date_moment}
                                    </small>
                                </div>
                            </div>
                        )
                    })}
                </div>
            )
        }
    }
}
