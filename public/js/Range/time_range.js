$(document).ready(function () {
    let timepickers = $('.start-end-timepicker');
    let key_bind = {
        keydown: function (e) {
            return false;
        }
    };

    let formats = {
        '12': {
            momentFormat: 'hh:mmA',
            timeFormat: 'h:iA'
        },
        '24': {
            momentFormat: 'HH:mm',
            timeFormat: 'H:i'
        }
    };

    timepickers.each(function () {
        let format = $(this).data('format') ? $(this).data('format') : '24';
        let start = $(this).find('.time-start');
        let end = $(this).find('.time-end');
        let alert = $('.start-end-timepicker-alert');
        let hours_alert = $('.start-end-timepicker-out-of-hours-alert');
        let initial_alert = $('.start-end-timepicker-init-time-alert');
        let opening_time = $(this).data('opening-time') && $(this).data('opening-time') !== '' ? $(this).data('opening-time') : '00:00';
        let closing_time = $(this).data('closing-time') && $(this).data('closing-time') !== '' ? $(this).data('closing-time') : '23:59';
        let time_format = formats[format].timeFormat;
        let moment_format = formats[format].momentFormat;
        let step = $(this).data('step') && $(this).data('step') !== '' ? $(this).data('step') : '30';
        let init_time = $(this).data('init-time') && $(this).data('init-time') !== '' ? moment() : null;
        if (init_time !== null) {
            let remainder = step - (init_time.minute() % step);
            init_time = moment(init_time).add(remainder, 'minutes');
        }

        start = start.timepicker({
            timeFormat: time_format,
            maxTime: closing_time,
            minTime: init_time !== null ? init_time.format(moment_format) : opening_time,
            step: step,
            typeaheadHighlight: false,
            selectOnBlur: false,
        });
        end = end.timepicker({
            timeFormat: time_format,
            maxTime: closing_time,
            minTime: init_time !== null ? init_time.format(moment_format) : opening_time,
            step: step,
            typeaheadHighlight: false,
            selectOnBlur: false,
        });

        if (start.val() !== '' && end.val() !== '') {
            let start_time = moment(start.val(), moment_format);
            let end_time = moment(end.val(), moment_format);
            if (start_time >= end_time) {
                alert.show()
            }
            else {
                alert.hide()
            }
        }

        checkTime();


        if (start.val() !== '') {
            let start_time = moment(start.val(), moment_format);
            end_min_time(start_time);
        }

        start.on('changeTime', function () {
            let start_time = moment(start.val(), moment_format);
            if (end.val() !== '') {
                let end_time = moment(end.val(), moment_format);
                if (start_time >= end_time) {
                    alert.show();
                } else {
                    alert.hide();
                }
            }

            end_min_time(start_time);
            checkTime();
        });

        end.on('changeTime', function () {
            let end_time = moment(end.val(), moment_format);
            if (start.val() !== '') {
                let start_time = moment(start.val(), moment_format);
                if (end_time <= start_time) {
                    alert.show();
                } else {
                    alert.hide();
                }
            }
            checkTime();
        });

        function checkTime() {
            let opening = moment(opening_time, 'HH:mm');
            let closing = moment(closing_time, 'HH:mm');
            if (start.val() !== '') {
                let start_time = moment(start.val(), moment_format);
                if (start_time < opening || start_time > closing) {
                    hours_alert.show();
                    return;
                } else if (start.val() >= opening && start.val() <= closing) {
                    hours_alert.hide();
                }
            }
            if (end.val() !== '') {
                let end_time = moment(end.val(), moment_format);
                if (end_time < opening || end_time > closing) {
                    hours_alert.show();
                } else {
                    hours_alert.hide();
                }
            }
        }

        function end_min_time(start_time) {
            let remainder = step - (start_time.minute() % step);
            let min_time = moment(start_time).add(remainder, 'minutes');
            let end_string = min_time.format('HH:mm');
            end.timepicker('option', 'minTime', opening_time != null && opening_time > end_string ? opening_time : end_string);
        }
    });
});
