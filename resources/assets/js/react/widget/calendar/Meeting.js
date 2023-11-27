import React from 'react';
import moment from "moment";
import Button from "../common/Button";
import StoreWidget from "../StoreWidget";
import ReservationProcess from "../reservation/ReservationProcess";

export class Meeting extends React.Component {
    /**
     *
     * @param meeting
     * @returns {boolean}
     * @private
     */
    static _yaPaso(meeting) {
        return meeting.passed === true;
    }

    /**
     *
     * @private
     */
    _handlePress() {
        let meeting = this.props.meeting;
        let brand = StoreWidget.current_brand.slug;
        let location = StoreWidget.current_location.slug;

        if (Meeting._yaPaso(meeting)) {
            return alert('Lo sentimos, el meeting ya ha pasado.');
        }
        StoreWidget.goToStep((
            <ReservationProcess
                brand={brand}
                location={location}
                meeting={meeting}
            />
        ))
    }

    render() {
        let {
            meeting
        } = this.props;
        let {
            staff
        } = meeting;

        return (
            <div
                className="WidgetBUQ--calendar--Meeting"
            >
                <div>
                    <div className="WidgetBUQ--calendar--Meeting--staff" style={{
                        backgroundImage: `url(${staff.picture_movil_list})`
                    }}/>
                </div>
                <div className="WidgetBUQ--calendar--Meeting--data">
                    <div
                        className="WidgetBUQ--calendar--Meeting--data1">{moment(meeting.start_date).format('HH:mm a').toUpperCase()}</div>
                    <div className="WidgetBUQ--calendar--Meeting--data2">{meeting.staff.name}</div>
                    <div className="WidgetBUQ--calendar--Meeting--data3">{meeting.service.name}</div>
                </div>
                <div className="WidgetBUQ--calendar--Meeting--calification"/>
                <div className="WidgetBUQ--calendar--Meeting--button">
                    <Button
                        text="Buq"
                        onClick={this._handlePress.bind(this)}
                        type="small"
                        background={StoreWidget.color}
                        color={'white'}
                    />
                </div>
            </div>
        );
    }
}
