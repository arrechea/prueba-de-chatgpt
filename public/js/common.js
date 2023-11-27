/*
 Common JS
 */
(function () {
    /*
     Selectores
     */
    $(document).ready(function () {
        $('select:not(".select")').material_select();

        /**
         * Creación de select2. Si el select tiene un atributo 'data-url' se
         * crea una función ajax que llame a la url definida. De otra forma,
         * crea un select2 simple. 'data-placeholder' proporciona el texto
         * placeholder, 'data-name' proporciona el nombre para el formulario
         * de los inputs ocultos que guardan el nombre y el valor del selector
         * (principalmente usado para proporcionar la información previa de
         * la página)
         */
        $('.select2').each(function (i, sel) {
            sel = $(sel);
            let url = sel.data('url');
            let placeholder = sel.data('placeholder');
            let dat = sel.data('name');
            let callback = sel.data('callback');
            let id = dat + '_id';
            let name = dat + '_name';
            let success = sel.data('success');
            let allowClear = sel.data('allow-clear') ? Boolean(sel.data('allow-clear')) : false;

            if (typeof url === 'undefined') {
                sel.select2({
                    placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
                    allowClear: allowClear,
                }).on('select2:select', function (item) {
                    if (typeof dat !== 'undefined') {
                        $('#' + id).val(item.params.data.id);
                        $('#' + name).val(item.params.data.text);
                    }
                });
            }
            else {
                sel.select2({
                    minimumInputLength: 1,
                    placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
                    allowClear: allowClear,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {results: data}
                        },
                        success: function (data) {
                            if (success) {
                                let y = eval(success);
                                if (typeof y === 'function') {
                                    y(data);
                                }
                            }
                        }
                    }
                }).on('select2:select', function (item) {
                    if (typeof id !== 'undefined') {
                        $('#' + id).val(item.params.data.id);
                        $('#' + name).val(item.params.data.text);
                    }
                    if (callback) {
                        let x = eval(callback);
                        if (typeof x === 'function') {
                            x()
                        }
                    }
                });
            }
        });
    });

})();

$(document).ready(function () {
    /*
     Imagenes form
     */
    initPhotoInputs();
});

function initPhotoInputs() {
    $(".uploadPhoto--input").on('change', function () {
        var input = this;
        var padre = $(this).closest('.uploadPhoto');
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                let file = input.files[0];
                let image = padre.find('.uploadPhoto--image');
                image.attr('src', e.target.result);
                let delete_div = padre.closest('.file-field').siblings('.image-delete-button-div');
                delete_div.find('.image-delete-button').show();
                image[0].onload = function () {
                    imageSize(this, padre, file.size)
                };
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            $(input).siblings('.uploadPhoto--size').hide();
        }
    });

    $('.uploadPhoto').each(function (i, div) {
        let uploader = $(div);
        let input = uploader.find('.uploadPhoto--input');
        let image = uploader.find('.uploadPhoto--image');
        let hide = image.attr('src') === '';
        let size = uploader.find('.uploadPhoto--size');
        if (!size.length) {
            image.after('<p class="uploadPhoto--size" hidden></p>')
        }
        if (image[0].complete) {
            imageSize(image[0], uploader);
        } else {
            image[0].onload = function () {
                imageSize(image[0], uploader);
            };
        }

        let delete_div = $('<div/>', {
            style: 'max-width: 180px;margin-top: 10px',
            class: 'image-delete-button-div'
        });

        let delete_button = $('<a/>', {
            name: 'delete_button_' + input.attr('name'),
            id: 'delete_button_' + input.attr('name'),
            style: 'margin: auto;display: block;',
            click: function (e) {
                image.attr('src', '');
                input.val('');
                delete_switch.prop('checked', true);
                $(this).hide();
                input.trigger('change');
            }
            ,
            html: '<i class="material-icons">close</i>',
            class: 'btn btn-small image-delete-button',
        });
        let delete_switch = $('<input/>', {
            name: '_delete-' + input.attr('name'),
            type: 'checkbox',
            hidden: true,
        });

        if (hide) {
            delete_button.hide();
        }

        delete_div.append(delete_switch, delete_button);
        uploader.closest('.file-field').before(delete_div);
    });

    function imageSize(img, padre, file_size = '') {
        if (file_size !== '') {
            printSize(file_size)
        } else {
            if ($(img).attr('src') !== '') {
                $.ajax({
                    type: "GET",
                    url: window.imageRoutes.size + '?url=' + img.src,
                    success: function (file_size) {
                        printSize(file_size);
                    },
                    error: function (error) {
                        console.error(error);
                    },
                    dataType: 'json'
                });
            }
        }

        function printSize(file_size = '') {
            let size = padre.find('.uploadPhoto--size');
            size.show();
            file_size = parseSize(file_size);
            let size_text = window.imageLang.size + file_size;
            let dimensions_text = window.imageLang.dimensions + `${img.naturalWidth} X ${img.naturalHeight}`;
            let html_text = file_size !== '' ? `${size_text}<br>${dimensions_text}` : dimensions_text;
            size.html(html_text);
        }
    }

    function parseSize(file_size) {
        if (file_size !== '') {
            let int_size = parseInt(file_size);
            let unit = 'B';
            if (int_size >= 1024 && int_size < 1048576) {
                int_size = int_size / 1024;
                unit = 'KB';
            } else if (int_size >= 1048576) {
                int_size = int_size / 1048576;
                unit = 'MB'
            }
            int_size = Math.round((int_size) * 10) / 10;
            return int_size + unit;
        }
        return '';
    }
}

$(document).ready(function () {
    //el atributo "href" de . debe especificar el ID del modal que quiere ser activado
    InitModals($('.modal'));

    let sels = $('.select2-filter');
    sels.each(function (i, sel) {
        sel = $(sel);
        let url = sel.data('url');
        let placeholder = sel.data('placeholder');
        sel.select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,

                data: function (params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function (data) {
                    let options = [];
                    data.forEach(function (item) {
                        options.push({
                            id: item['id'],
                            text: item['name']
                        })
                    });

                    return {results: options}
                }
            },
            placeholder: placeholder,
            allowClear: true,
        });
    });
});

//-------Calendario de consulta de reservaciones todo poner eventos a las horas
$(function () {
    $('#calendar_reservations').fullCalendar({
        defaultView: 'agendaWeek',
        locale: "es",
        themeSystem: 'jquery-ui',
        slotDuration: '00:15:00',
        minTime: "05:00:00",
        maxTime: "21:00:00",
        selectable: true,
        select: function (startDate, endDate) {
            alert('selected ' + startDate.format() + ' to ' + endDate.format());
        }
    });
});

function InitModals(modals) {
    modals.modal({
        ready: function (modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
            var href = modal.data('href');
            if (typeof href !== 'undefined' && href.length > 0) {
                var contenedor = modal.find(".modal-content");
                var method = modal.data('method');
                contenedor.html(window.getLoadingImage());
                if (method === 'get') {
                    $.get(href, {
                        //"_token": $('[name="csrf-token"]').attr('content')
                    }).done(function (data) {
                        contenedor.html(data);
                    }).fail(function (data) {
                        console.error(data);
                    })
                } else {
                    $.post(href, {
                        "_token": $('[name="csrf-token"]').attr('content')
                    }).done(function (data) {
                        contenedor.html(data);
                    }).fail(function (data) {
                        console.error(data);
                    })
                }
            }
        },
        complete: function (modal) {
            var href = modal.data('href');
            if (typeof href !== 'undefined' && href.length > 0) {
                var contenedor = modal.find(".modal-content");
                contenedor.find('*').remove();
            }
        }
    });
}

/*
 Tabs Con link
 */
(function () {
    $(document).ready(function () {
        $('.tabsWithLinks a').on('click', function () {
            var este = $(this);
            document.location.href = este.attr('href');
        })
    });
})();

/*
 * Opciones de los calendar picker para poner fechas
 */
$(document).ready(function () {
    $('.calendar-date').pickadate({
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
    });
});

/**
 * Show on Unloadl
 */
function gafaFitUnload() {
    // var loader = new SVGLoader(document.getElementById('loader'), {speedIn: 200, easingIn: mina.easeinout});
    // loader.show();
    $('html').addClass("notLoaded");
}

/**
 * Hide
 */
function gafaFitLoad() {
    // window.Initloader.hide();
    setTimeout(function () {
        $('html').removeClass("notLoaded");
        // $('#loader').attr('class', 'loaded');
    }, 500)
}

(function () {
    // window.Initloader = new SVGLoader(document.getElementById('loader'), {speedIn: 400, easingIn: mina.easeinout});
    // Initloader.show();
})();

(function () {
    $('.tooltipped').tooltip({delay: 70});
})();

function displayErrorsToast(e, title_text) {
    let status = e.status;
    let status_text = e.statusText;
    let message = e.responseJSON.message;
    let errors = e.responseJSON.hasOwnProperty('errors') ? e.responseJSON.errors : [];
    let title = `${title_text}<br>` + ` ${status} - ${status_text}<br>`;
    let error_message = (Array.isArray(errors) && errors.length <= 0) ? title + `${message}` : title;
    $.each(errors, function (i, v) {
        error_message += `<br>${v[0]}`;
    });
    Materialize.toast(error_message, 10000, 'toast-error');
}

function inputsAsterisk() {
    let inputs = $('form input[required], select[required],textarea[required]');
    inputs.each(function (index, input) {
        let id = $(input).attr('id');
        let label = $(`label[for="${id}"]`);
        let text = label.text();
        if (!label.find('.red-asterisk').length) {
            label.html(text + '<span class="red-asterisk"> *</span>')
        }
    });
}

$(document).ready(function () {
    inputsAsterisk();
});

function initDeleteButtons(sel) {
    $(sel).each(function (index, el) {
        el = $(el);
        let name = el.data('name');
        if (typeof name !== 'undefined' && name !== null && name !== '') {
            el.on('click', function () {
                $(`#${name}-delete-form`).submit()
            });
        }
    })
}

$(document).ready(function () {
    initDeleteButtons('.model-delete-button');
});
