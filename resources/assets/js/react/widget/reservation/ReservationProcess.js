import React from 'react'
import StoreReservation from "../../reservation/process/StoreReservation";
import AppReservationTemplate from "../../reservation/process/AppReservationTemplate";
import StoreWidget from '../StoreWidget'

export default class ReservationProcess extends React.Component {
    /**
     *
     */
    componentDidMount() {
        let {
            brand,
            location,
            meeting
        } = this.props;

        global.GafaFitSDK.GetCreateReservationForm(brand, location, null, null, {
            meetings_id: meeting.id,
            forcejson: 'on',
        }, function (err, data) {
            if (err) {
                console.log(err);
            }
            StoreReservation.loguearConInfo(data, null, () => {
               StoreWidget.set('step', <AppReservationTemplate widget={true}/>);
            });
        });
    }

    /**
     *
     * @returns {XML}
     */
    render() {

        return (
            <div
                className="WidgetBUQ--ReservationProcess"
            >
            </div>
        )
    }
}
