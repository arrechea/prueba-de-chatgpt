import React from 'react'
import moment from 'moment-timezone'
import AddToCalendar from 'react-add-to-calendar';
import CustomScroll from 'react-custom-scroll';
import "react-custom-scroll/dist/customScroll.css"
import 'moment/locale/es';
import StoreReservation from "../StoreReservation";

export default class MeetingThankyou extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            event: {
                title: '',
                description: '',
                location: '',
                startTime: '',
                endTime: '',
                isMap: false,
                map_objectsSelected: null,
            }
        };
    }

    static defaultProps() {
        return {
            meetings: null,
            staff: null,
            lang: null,
            date: null,
            map_objectsSelected: null,
        }
    }


    /**
     *
     * @returns {XML}
     */
    render() {
        let location = StoreReservation.get('location');
        let location_name = location ? location.name : '';
        let firstReservation = this.props.firstReservation;
        let staff = this.props.staff;
        let lang = this.props.lang;
        let map_objectsSelected = StoreReservation.getReservationsSelected();
        let reservations = this.props.reservations;
        let meeting = this.props.meeting;
        let locale = lang['locale'];
        moment.locale(locale);

        let date = this.props.date;

        date = moment(date);

        let fmt = "YYYY-MM-DD HH:mm:ss";
        let zone = "America/Mexico_City";
        let event = {};

        if (firstReservation) {
            let start_date_moment = moment.tz(firstReservation.meeting_start, zone);
            let end_date_moment = moment.tz(firstReservation.meetings.end_date, zone);

            let start_date = start_date_moment.utc().format(fmt);
            let end_date = end_date_moment.utc().format(fmt);

            event = {
                title: !!firstReservation.meetings.service ? firstReservation.meetings.service.name : '',
                description: [!!firstReservation.meetings.description ? firstReservation.meetings.description : '', staff ? `Instructor:${staff.name} ${staff.lastname}` : ''],
                location: !!firstReservation.room ? firstReservation.room.name : '',
                startTime: !!start_date ? start_date : '',
                endTime: !!end_date ? end_date : '',
            };
        }

        return (
            <div>

                <table className="gs-table">
                    <tbody>
                    {map_objectsSelected.length > 1
                        ? <tr className={'summary__section'}>
                            <td>
                                <h3>Anfitrión</h3>
                            </td>
                        </tr>
                        : null
                    }
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.hour']}</th>
                        <td>{date.format('h:mm a')} </td>
                    </tr>
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.date']}{reservations && reservations.length > 1 && 's'}</th>
                        <td>
                            <div>{moment(this.props.date).format('D [de] MMMM [de] YYYY')}</div>
                        </td>
                    </tr>
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.service']}</th>
                        <td>{firstReservation && !!firstReservation.meetings.service ?
                            firstReservation.meetings.service.name
                            :
                            meeting.service.name}
                        </td>
                    </tr>
                    {meeting && meeting.description && meeting.description !== '' ? (
                        <tr className="gs-table__item is-meeting">
                            <th>{lang['confirm.notes']}</th>
                            <td>{meeting.description}</td>
                        </tr>
                    ) : null}
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.staff']}</th>
                        <td>{staff ? `${staff.name} ${staff.lastname}` : ''}</td>
                    </tr>
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.location']}</th>
                        <td>{location_name}</td>
                    </tr>
                    {
                        Array.isArray(map_objectsSelected) ?
                            map_objectsSelected.map((object, i) => {
                                if (!object) {
                                    return null;
                                }

                                if (i === 0) {
                                    return (
                                        <tr
                                            className="gs-table__item is-meeting"
                                            key={`MeetingThankyou--position--${object.id}`}
                                        >
                                            <th>{lang['confirm.position_number']}</th>
                                            <td>
                                                {
                                                    !!object.position_text ?
                                                        object.position_text
                                                        :
                                                        object.position_number
                                                }
                                            </td>
                                        </tr>
                                    )
                                }
                            })
                            :
                            null
                    }
                    {map_objectsSelected.length > 1
                        ? <tr className={'summary__section'}>
                            <td>
                                <h3>Invitados</h3>
                            </td>
                        </tr>
                        : null
                    }
                    {firstReservation && map_objectsSelected.length === 1 ?
                        (map_objectsSelected.map((object, i) => {
                                return i >= 1 ? (
                                    <tr className="gs-table__item is-meeting"
                                        key={`MeetingThankyou--positionFinishi--${object.id}`}
                                    >
                                        <th>{lang['confirm.position_number']}</th>
                                        <td>
                                            {object.position_number}
                                        </td>
                                    </tr>
                                ) : null;
                            })
                        ) : null
                    }

                    {map_objectsSelected.length > 1
                        ?
                        <tr className="invitedPeople">
                            <td>
                                <CustomScroll heightRelativeToParent="100%">
                                    {
                                        Array.isArray(map_objectsSelected)
                                            ? map_objectsSelected.map((object, i) => {
                                                if (!object) {
                                                    return null;
                                                }
                                                if (i >= 1) {
                                                    let invited_data = StoreReservation.get('invited_data');
                                                    let data = typeof invited_data === "object" && invited_data[i] ? invited_data[i] : {};
                                                    let name = data['name'];
                                                    let email = data['email'];
                                                    return i !== 0 ? (
                                                        <div
                                                            className="invitedPeople__item"
                                                            key={`MeetingThankyou--position--${object.id}`}
                                                        >
                                                            <tr className={'gs-table__item'}>
                                                                <th className="this-invitedLabel">
                                                                    {lang['confirm.position_number']}
                                                                </th>
                                                                <td className="this-invitedPosition">
                                                                    {object.position_text}
                                                                </td>
                                                            </tr>
                                                            {name ? (<tr className={'gs-table__item'}>
                                                                <th className="this-invitedLabel">
                                                                    {lang['invited.name']}
                                                                </th>
                                                                <td className="this-invitedName">
                                                                    {name}
                                                                </td>
                                                            </tr>) : null}
                                                            {email ? (<tr className={'gs-table__item'}>
                                                                <th className="this-invitedLabel">
                                                                    {lang['invited.email']}
                                                                </th>
                                                                <td className="this-invitedEmail">
                                                                    {email}
                                                                </td>
                                                            </tr>) : null}
                                                        </div>
                                                    ) : null
                                                }
                                            })
                                            : null
                                    }
                                </CustomScroll>
                            </td>
                        </tr>

                        : null
                    }
                    </tbody>
                </table>
                <AddToCalendar className={'addevent'} event={event}/>
            </div>
        )
    }
}
