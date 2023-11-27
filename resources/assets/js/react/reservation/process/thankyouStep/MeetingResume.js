import React from 'react'
import moment from 'moment-timezone'
import AddToCalendar from 'react-add-to-calendar';
import CustomScroll from 'react-custom-scroll';
import "react-custom-scroll/dist/customScroll.css"
import 'moment/locale/es';
import InvitedData from "./InvitedData";
import StoreReservation from "../StoreReservation";
import IconArrowDown from "../ui/iconArrowDown";
import IconUser from "../ui/iconUser";
import IconUsers from "../ui/iconUsers";

export default class MeetingResume extends React.Component {
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
            <div className="gs-summary">
                <div className="gs-summary__header" onClick={this.props.toggleClass}>
                    {/* <h3 className="CreateReservationFancy--title">{this.state.location.company.name}</h3> */}
                    <h3 className="CreateReservationFancy--title">{this.props.meeting.brand.name}</h3>
                    {/* <h5>{this.state.location.name}</h5> */}
                    <div className="gs-summary__meetingNotifications">
                        {map_objectsSelected.length > 0 ?
                            <div>
                                <span className="gs-summary__notifications">1</span>
                                <IconUser/>
                            </div>
                            : null
                        }
                        {map_objectsSelected.length > 1 ?

                            <div>
                                <span className="gs-summary__notifications">{map_objectsSelected.length - 1}</span>
                                <IconUsers/>
                            </div>

                            : null
                        }
                    </div>
                    <IconArrowDown/>
                </div>

                <div className="gs-summary__divider"></div>
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
                    {meeting && !!meeting.description && meeting.description !== '' ? (
                        <tr className="gs-table__item is-meeting">
                            <th>{lang['confirm.notes']}</th>
                            <td>{meeting.description}</td>
                        </tr>
                    ) : ''}
                    <tr className="gs-table__item is-meeting">
                        <th>{lang['confirm.staff']}</th>
                        <td>{staff ? `${staff.name} ${staff.lastname}` : ''}</td>
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
                    {firstReservation ? (
                        map_objectsSelected.length > 1 ? (map_objectsSelected.map((object, i) => {
                                    return i >= 1 ? (
                                        <tr
                                            className="gs-table__item is-meeting"
                                            key={`MeetingThankyou--positionFinishi--${object.id}`}
                                        >
                                            <th>{lang['confirm.position_number']}</th>
                                            <td>
                                                {object.position_number}
                                            </td>
                                        </tr>
                                    ) : null;
                                })
                            )
                            : null
                    ) : (
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
                                                    return (
                                                        <div
                                                            className="invitedPeople__item gs-table__item is-meeting"
                                                            key={`MeetingThankyou--position--${object.id}`}
                                                        >
                                                            {
                                                                i !== 0 && this.props.isMap ? (
                                                                    <InvitedData
                                                                        lang={lang}
                                                                        index={i}
                                                                        position={object.position_number}

                                                                    />
                                                                ) : null
                                                            }
                                                        </div>
                                                    )
                                                }
                                            })
                                            : null
                                    }
                                </CustomScroll>
                            </td>
                        </tr>
                    )}

                    </tbody>
                </table>
                <AddToCalendar className={'addevent'} event={event}/>
            </div>
        )
    }
}
