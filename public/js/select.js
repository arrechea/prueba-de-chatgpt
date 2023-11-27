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
    let initializeSelect = function () {
        if (!!$.prototype.select2) {
            $('.select2').each(function (i, sel) {
                sel = $(sel);
                let url = sel.data('url');
                let placeholder = sel.data('placeholder');
                let dat = sel.data('name');
                let callback = sel.data('callback');
                let id = dat + '_id';
                let name = dat + '_name';
                let success = sel.data('success');

                if (typeof url === 'undefined') {
                    sel.select2({
                        placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
                    }).on('select2:select', function (item) {
                        if (typeof dat !== 'undefined') {
                            $('#' + id).val(item.params.data.id);
                            $('#' + name).val(item.params.data.text);
                        }
                    });
                } else {
                    sel.select2({
                        minimumInputLength: 1,
                        placeholder: typeof placeholder !== 'undefined' ? placeholder : '',
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
        } else {
            setTimeout(initializeSelect, 20)
        }
    };

    initializeSelect();
});
