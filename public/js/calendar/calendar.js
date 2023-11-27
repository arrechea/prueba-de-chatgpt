window.CalendarSystem = function (calendarSelector) {
    let object = {
        eventsurl: '',
        calendarSelector: calendarSelector ? calendarSelector : ".calendar",
        locale: 'es',
        start: null,
        end: null,
        dataForSource: {},
        click: function () {
        },
        dbClick: function () {
        },
        repeatButtonText: '',
        repeatUrl: null,
        repeatSuccessMessage: '',
        repeatErrorMessage: '',
        slotDuration: '00:30:00',
        slotLabelFormat: 'H:mm',
        timeFormat: 'H:mm',
        repeatConfirmMessage: '',
    };

    return {
        /**
         *
         * @param dataForSource
         */
        setDataForSource: function (dataForSource) {
            object.dataForSource = dataForSource;
        },
        /**
         *
         * @param url
         */
        setEventUrl: function (url) {
            object.eventsurl = url;
        },
        /**
         *
         * @param locale
         */
        setLocale: function (locale) {
            object.locale = locale;
        },
        /**
         *
         * @param newFunction
         */
        setClick: function (newFunction) {
            object.click = newFunction;
        },
        /**
         *
         * @param newFunction
         */
        setDbClick: function (newFunction) {
            object.dbClick = newFunction;
        },
        /**
         *
         * @param start
         */
        setStart: function (start) {
            object.start = start;
        },
        /**
         *
         * @param end
         */
        setEnd: function (end) {
            object.end = end;
        },
        /**
         *
         * @param visible
         */
        setRepeatButtonText: function (text) {

            object.repeatButtonText = text;
        },

        setRepeatUrl: function (url) {
            object.repeatUrl = url;
        },
        /**
         *
         * @param message
         */
        setRepeatSuccess: function (message) {
            object.repeatSuccessMessage = message;
        },
        /**
         *
         * @param error
         */
        setRepeatError: function (error) {
            object.repeatErrorMessage = error;
        },

        setRepeatConfirmMessage: function (message) {
            object.repeatConfirmMessage = message;
        },

        setSlotDuration: function (duration) {
            object.slotDuration = duration;
        },

        setSlotLabelFormat: function (formating) {
            object.slotLabelFormat = formating;
        },

        setTimeFormat: function (formating) {
            object.timeFormat = formating;
        },

        render: function () {
            let eventsurl = object.eventsurl;

            let calendarSelector = object.calendarSelector;

            let options = {
                // timezone: window.brand_timezone ? window.brand_timezone : window.system_timezone,
                defaultView: 'agendaWeek',
                header: {
                    left: 'prev,next today',
                    right: ''
                },
                firstDay: new Date().getDay(),
                timeFormat: object.timeFormat,
                columnHeaderFormat: 'dddd \n D/MM/YYYY',
                contentHeight: 'auto',
                nowIndicator: true,
                editable: false,
                eventLimit: true,
                locale: object.locale,
                selectable: true,
                selectConstraint: {
                    start: '00:00',
                    end: '24:00'
                },
                eventSources: [{
                    url: eventsurl,
                    type: 'GET',
                    data: object.dataForSource,
                    success: function () {
                        $(window).trigger('resize')
                    }
                }],
                select: object.click,
                eventRender: object.dbClick,
                slotLabelFormat: object.slotLabelFormat,
                slotDuration: '00:15:00',
                eventAfterAllRender: function (view) {
                    if (object.repeatUrl !== null) {
                        $('.fc-day-header').each(function () {
                            if ($(this).find('.repeat-day').length === 0) {
                                let date_string = $(this).data('date');
                                let date_format = moment(date_string);
                                date_format.subtract(1, 'week');
                                let date = date_format.format('YYYY-MM-DD');
                                $(this).css('position', 'relative');
                                let repeat_button = $('<a>', {
                                    id: `repeat-button-${date}`,
                                    class: 'repeat-day',
                                    text: `${object.repeatButtonText}`,
                                });
                                repeat_button.data('date', date);
                                $(this).append('<br>');
                                $(this).append(repeat_button);

                                $(`#repeat-button-${date}`).on('click', function (e) {
                                    e.preventDefault();
                                    let date = $(this).data('date');
                                    let message = object.repeatConfirmMessage;
                                    let modal = $('#calendar-repeat-modal');
                                    message = message.replace(':date', date);
                                    modal.find('#repeat-message').html(message);
                                    modal.data('date', date);
                                    modal.attr('data-date', date);
                                    modal.modal('open');
                                });
                            }
                        });
                    }
                },
                now: window.brand_time ? window.brand_time : window.system_time,
                selectLongPressDelay: 500
            };
            if (object.start) {
                options.minTime = object.start;
            }
            if (object.end) {
                options.maxTime = object.end;
            }
            try {
                //Lang
                options.allDayText = window.Calendar.lang.all_day;
                options.buttonText = {
                    today: window.Calendar.lang.today
                };
            } catch (e) {

            }

            let calendar = $(calendarSelector).fullCalendar(options);

            function fixHeight(data) {
                calendar.fullCalendar('option', 'minTime', '00:00:00');
                calendar.fullCalendar('option', 'maxTime', '24:00:00');
                let start_dates = data.map(d => new Date(d.start));
                let end_dates = data.map(d => new Date(d.end));
                if (start_dates.length > 0 && end_dates.length > 0) {
                    let min = new Date(Math.min.apply(null, start_dates));
                    let max = new Date(Math.max.apply(null, end_dates));
                    calendar.fullCalendar('option', 'minTime', min.getHours() + ':00:00');
                    calendar.fullCalendar('option', 'maxTime', (max.getHours() + 1) + ':00:00');
                }
            }

            return calendar;
        }
    }
};
