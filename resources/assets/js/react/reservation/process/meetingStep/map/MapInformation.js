import React from 'react'
import ReactDOM from "react-dom";
import StoreReservation from "../../StoreReservation";
import moment from 'moment-timezone';

export default class MapInformation extends React.Component {

    static get defaultProps() {
        return {
            meeting: null,
        };
    }

    printLocationAddress() {
        let location = StoreReservation.get('location');
        if (location) {
            return (
                <div className={'gs-location-information'}>
                    <div className={'gs-location-name'}>{location.name} </div>
                    <div
                        className={'gs-location-address gs-subtitle'}>{location.street} {!!location.number ? `#${location.number}` : ''}{!!location.suburb ? `, ${location.suburb}` : ''}</div>
                </div>
            )
        }

        return null;
    }

    render() {
        let {meeting} = this.props;


        if (!meeting) {
            return null;
        }

        let start_string = meeting.start_date;
        let start_print = '';
        try {
            let start = moment(start_string);
            start_print = start.format('DD MMMM YYYY hh:mma')
        } catch (e) {
            console.error(e);
            return null;
        }

        return (
            <div className={'gs-reservation-meeting-information'}>
                {this.printLocationAddress()}
                <div className={'gs-meeting-start gs-subtitle'}>
                    {start_print}
                </div>
            </div>
        )
    }
}
