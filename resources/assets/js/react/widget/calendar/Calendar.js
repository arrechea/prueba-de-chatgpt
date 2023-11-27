import React from 'react';
import moment from "moment";
import {Meeting} from "./Meeting";

import 'owl.carousel';
import 'owl.carousel/dist/assets/owl.carousel.min.css';
import 'owl.carousel/dist/assets/owl.theme.default.min.css';

export class Calendar extends React.Component {
    static get defaultProps() {
        return {
            meetings: [],
            hidde_passed: false,
            date_start: null,
            calendar_days: 7,
            visible_days: 7,
            current_day: null,
        };
    }

    constructor(p) {
        super(p);
        this._calendarBlocks = [];
        this._slider = null;
        this.state = {
            _date_start: null,
            _end_date: null,
            _calendar_days: null,
            _visible_days: null,
            _meetings: [],
            _calendarBlocks: null,
            _hidde_passed: null,
        }
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
        if (this._calendarBlocks.length) {
            this._slider = $(".WidgetBUQ--calendar--slider").owlCarousel({
                items: 1,
                center: true,
                dots: false,
                nav: false,
            });
        }
    }

    _init() {
        let {
            meetings,
            date_start,
            calendar_days,
            visible_days,
            hidde_passed,
        } = this.props;

        this._date_start = date_start ? moment(date_start) : moment();
        this._end_date = moment(date_start).add(calendar_days, 'days');
        this._calendar_days = calendar_days;
        this._visible_days = visible_days;
        this._meetings = meetings;
        this._hidde_passed = hidde_passed;
        this._calendarBlocks = this._prepareMeetings(meetings);
    }

    /**
     *
     * @private
     */
    _prepareMeetings(meetings) {
        let startDate = this._date_start.startOf('day');//filtro de inicio
        let endDate = this._end_date.startOf('day');//filtro fin
        let visibleDays = this._visible_days;//agrupación de meetings
        let hidePassed = this._hidde_passed;//ocultar finalizados

        let blockStart = startDate.clone();//variable que marca el fin de un bloque
        let blockEnd = blockStart.clone().add((visibleDays - 1), 'days');//variable que marca el fin de un bloque

        let calendarBlock = [];
        let dayBlock = {};

        meetings.forEach((meeting) => {
            //Filtrar meetings para no mostrar anteriores ni posteriores. Ocultar los meetings ya acabados
            if (hidePassed && (meeting.passed === 'true' || meeting.passed === true)) {
                return;
            }
            let inicioMeeting = moment(meeting.start_date);
            let dia = inicioMeeting.clone().startOf('day');
            let formatDay = dia.format('YYYY-MM-DD');

            let diferenciaInicio = inicioMeeting.diff(startDate, 'minutes');
            if (diferenciaInicio < 0) {
                //anterior a fecha de inicio
                return;
            } else {
                let diferenciaFin = inicioMeeting.diff(endDate, 'minutes');
                if (diferenciaFin > 0) {
                    //posterior fecha de fin
                    return;
                }
            }

            if (dia.diff(blockEnd.clone().add(1, 'days'), 'minutes') >= 0) {
                //nuevo dia!
                calendarBlock.push({
                    start: blockStart.clone(),
                    end: blockEnd.clone(),
                    days: dayBlock,
                });
                dayBlock = {};
                blockStart = blockEnd.clone().add(1, 'days');//variable que marca el fin de un bloque
                blockEnd = blockStart.clone().add((visibleDays - 1), 'days');//variable que marca el fin de un bloque
            }
            if (!dayBlock.hasOwnProperty(formatDay)) {
                dayBlock[formatDay] = {
                    day: dia,
                    meetings: [],
                }
            }
            dayBlock[formatDay].meetings.push(meeting);
        });

        if (Object.keys(dayBlock).length > 0) {
            //Sobran
            calendarBlock.push({
                start: blockStart.clone(),
                end: blockEnd.clone(),
                days: dayBlock,
            });
        }

        return calendarBlock;
    }

    /**
     *
     * @param dia
     * @private
     */
    _changeCurrentDay(dia) {
        this.setState({
            current_day: dia,
        })
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

    /**
     *
     * @returns {XML}
     */
    render() {
        this._init();
        let {
            current_day
        } = this.state;

        return (
            <div
                className="WidgetBUQ--calendar"
            >
                <div className="WidgetBUQ--calendar--slider owl-carousel">
                    {
                        this._calendarBlocks.length ?
                            this._calendarBlocks.map((bloque) => {
                                let key = `${bloque.start.format('DD')} al ${bloque.end.format('DD')} de ${bloque.end.format('MMMM')} ${bloque.end.format('YYYY') }`;
                                let days = bloque.hasOwnProperty('days') ? bloque.days : {};

                                return (
                                    <div
                                        className="WidgetBUQ--calendar--week"
                                        key={key}
                                    >
                                        <div
                                            className="WidgetBUQ--calendar--week--title"
                                        >
                                            {this._calendarBlocks.length > 1 ? (
                                                <div>
                                                    <span
                                                        className="WidgetBUQ--calendar--slider--prev"
                                                        onClick={this._prevSlide.bind(this)}
                                                    >{'<'}</span>
                                                    <span>{key.toUpperCase()}</span>
                                                    <span
                                                        className="WidgetBUQ--calendar--slider--next"
                                                        onClick={this._nextSlide.bind(this)}
                                                    >{'>'}</span>
                                                </div>
                                            ) : key.toUpperCase()}
                                        </div>
                                        <div
                                            className="WidgetBUQ--calendar--week--menu"
                                        >
                                            {
                                                Object.keys(days).map((index) => {
                                                    let dia = days[index];
                                                    if (!current_day) {
                                                        current_day = dia;
                                                    }
                                                    let isCurrent = dia.day.toString() === current_day.day.toString();

                                                    return (
                                                        <div
                                                            className={`WidgetBUQ--calendar--week--menu--day ${isCurrent ? 'WidgetBUQ--calendar--current' : ''}`}
                                                            key={dia.day}
                                                            onClick={this._changeCurrentDay.bind(this, dia)}
                                                        >
                                                            <div
                                                                className="WidgetBUQ--calendar--week--menu--day--name">{dia.day.format('dd').toUpperCase()}</div>
                                                            <div
                                                                className="WidgetBUQ--calendar--week--menu--day--number">{dia.day.format('DD')}</div>
                                                        </div>
                                                    )
                                                })
                                            }
                                        </div>
                                    </div>
                                );
                            }) : (
                            <div className="WidgetBUQ--calendar--noMeetings">No hay clases previstas</div>
                        )
                    }
                </div>
                <div className="WidgetBUQ--calendar--meetingList">
                  {
                     current_day ? (
                           current_day.meetings.map((meeting) => {
                              return (
                                 <Meeting
                                       meeting={meeting}
                                       key={`Calendar--block--${meeting.id}`}
                                 />
                              )
                           })
                     ) : null
                  }
                </div>
            </div>
        );
    }
}
