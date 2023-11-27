import React from 'react'
import StoreWidget from "../StoreWidget";
import StoreReservation from "../../reservation/process/StoreReservation";
import moment from "moment";
import {Calendar} from "../calendar/Calendar";

export default class LocationSingle extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            meetings: [],
            services: [],
            current_service: null,
            loading: true,
        }
    }

    componentDidMount() {
        StoreWidget.set('backholder', 'title');
        this._refreshMeetings();
    }

    UNSAFE_componentWillReceiveProps() {
        this._refreshMeetings();
    }

    /**
     *
     * @private
     */
    _refreshMeetings() {
        let current_location = this.props.location;
        let current_brand = current_location.brand;

        let brandSlug = current_brand.slug;
        let locationId = current_location.id;
        let component = this;

        if (brandSlug && locationId) {
            component.setState({
                loading: true,
            }, () => {
                let calendar_days = current_location.calendar_days;//integer
                let date_start = component.getDateStart();//nullable
                let date_end = null;

                if (!date_start) {
                    date_start = moment().format('YYYY-MM-DD 00:00:00');
                    date_end = moment().add(calendar_days, 'days').format('YYYY-MM-DD 00:00:00');
                } else {
                    date_end = moment(date_start).add(calendar_days, 'days').format('YYYY-MM-DD 00:00:00');
                }
                global.GafaFitSDK.GetlocationMeetingList(brandSlug, locationId, {
                    start: date_start,
                    end: date_end,
                    only_actives: 'true',
                    reducePopulation: 'true',
                }, (err, data) => {
                    if (err) {
                        component.setState({
                            meetings: []
                        }, () => {
                            alert(err.error)
                        });
                        return null
                    }
                    let services = this._getServices(data);

                    component.setState({
                        current_service: null,//todo aqui deberiamos retocar cuando hagamos los filtros
                        meetings: data,
                        loading: false,
                        services: services,
                    }, () => {
                        StoreWidget.current_location = StoreReservation.location = current_location;
                        StoreWidget.current_brand = current_brand;
                    });
                });
            });
        }
    }

    /**
     *
     * @param meetings
     * @returns {Array}
     * @private
     */
    _getServices(meetings) {
        let servicios = {};
        let serviciosArray = [];
        if (meetings.length) {
            meetings.map((meeting) => {
                let service = meeting.service;
                if (!!service && !service.parend_id) {
                    servicios[service.id] = service.name;
                }
            });
            for (let id in servicios) {
                if (servicios.hasOwnProperty(id)) {
                    serviciosArray.push({
                        'id': id,
                        'name': servicios[id]
                    })
                }
            }
        }
        return serviciosArray;
    }

    /**
     *
     * @param service
     * @private
     */
    _changeService(service) {
        this.setState({
            current_service: service
        });
    }

    _getFilteredMeetings(meetings, current_service) {
        return meetings.filter((meeting) => {
            return !!meeting.service && !!current_service
                && parseInt(meeting.service.id) === parseInt(current_service.id);
        });
    }

    getDateStart() {
        let {
            date_start,//fecha inicio del calendario
        } = this.props.location;

        let now = moment().format('YYYY-MM-DD 00:00:00');
        if (now > date_start) {
            date_start = now;
        }
        return date_start;
    }

    render() {
        let {
            location
        } = this.props;
        let {
            calendar_days,//dias que va a mostrar el calendario
            // date_start,//fecha inicio del calendario
            // start_time,//horario apertura
            // end_time,//horario cierre
        } = location;
        let {
            meetings,
            loading,
            services,
            current_service
        } = this.state;
        //Filter meetings by filter
        // meetings = this._getFilteredMeetings(meetings, current_service); //todo se deberia reactivar cuando se hagan los filtros

        let lang = StoreWidget.lang;
        let images = StoreWidget.images;

        return (
            <div
                className="WidgetBUQ--LocationSingle"
            >
                <div className="WidgetBUQ--LocationSingle--title">{lang['widget.locationSingle.title']}</div>
                <Calendar
                    meetings={meetings}
                    calendar_days={calendar_days}
                    date_start={this.getDateStart()}
                    visible_days={4}
                    hidde_passed={true}
                />
            </div>
        )
    }
}
