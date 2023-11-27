$(document).ready(function () {
    let datepickers = $('.start-end-datepicker');
    let date_options = {
        selectYears: 150,
        selectMonths: true,
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Borrar',
        close: 'Cerrar',
        // Setter
        onStart: function () {
            let valor = this.get('value');
            if (valor !== '') {
                let date = new Date(valor);
                this.set('select', [date.getFullYear(), date.getMonth(), date.getDate()])
            }
        }
    };

    datepickers.each(function () {
        let start = $(this).find('.time-start');
        let end = $(this).find('.time-end');
        let alert = $(this).find('.start-end-datepicker-alert');
        start = start.pickadate(date_options);
        end = end.pickadate(date_options);

        if (start.val() !== '' && end.val() !== '') {
            if (start.val() >= end.val()) {
                alert.show()
            } else {
                alert.hide()
            }
        }

        if (start.val() !== '') {
            let start_time = moment(start.val() + 'T00:00:00');
            let end_time = start_time.add(1, 'days');
            end.pickadate('picker').set('min', end_time.format('YYYY-MM-DD'));
        }

        start.on('change', function () {
            if (end.val() !== '') {
                if ($(this).val() >= end.val()) {
                    alert.show();
                } else {
                    alert.hide();
                }
            }

            let start_time = moment($(this).val() + 'T00:00:00');
            let end_time = start_time.add(1, 'days');
            end.pickadate('picker').set('min', end_time.format('YYYY-MM-DD'));
        });

        end.on('change', function () {
            if (start.val() !== '') {
                if ($(this).val() <= start.val()) {
                    alert.show();
                } else {
                    alert.hide();
                }
            }
        });
    })
});
