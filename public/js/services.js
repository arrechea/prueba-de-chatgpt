(function () {
    $(document).ready(function () {
        // checkHeight(-1);

        $(window).resize(function () {
            checkHeight(-1);
        });

        $(document).on('click', '.remove-child-service', function () {
            let li = $(this).closest('li');
            let token = $('input[name="_token"]').val();
            let data = [
                {name: 'id', value: li.find('div').find('input').val()},
                {name: '_token', value: token}
            ];
            let url = li.data('url');
            $.post(url, data).done(function () {
                li.remove();
                checkHeight(-1);
            });
        });

        $(document).on('click', '.edit-special-text', function () {
            let href = $(this).data('href');
            let url = $(this).data('url');
            let mod = $('#edit_special_text');
            mod.attr('data-href', href);
            mod.attr('data-url', url);
            mod.data('href', href);
            mod.data('url', url);
            mod.modal('open');
        });

        $('.save-button').on('click', function () {
            let modal = $(this).closest('.create-modal');
            let form = $('#formaCreacion');
            let data = new FormData(form[0]);
            let list_id = modal.data('list');
            let list = $('#' + list_id);
            let url = list.data('url');
            if (data.has('brands_id') && data.has('companies_id') && data.has('parent_id')
                && data.has('_token') && data.has('name')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function (dat) {
                    let modal_edit = form.attr('id') === 'edit_special_text';

                    if (modal_edit) {
                        let lbl = list.find('.text-id[value="' + dat.id + '"]').closest('div').prev('label');
                        lbl.text(dat.name);
                    } else {
                        let active = dat.status !== 'active' ? '<i class="material-icons">not_interested</i>' : '';
                        let li =
                            '<li class="collection-item service-list-item"\n' +
                            '    data-url="' + dat.delete_url + '" data-order="' + dat.order + '">\n' +
                            '    <div>\n' +
                            '        <label>' + dat.name + '</label>\n' +
                            '        ' + active + '\n' +
                            '        <div class="secondary-content">\n' +
                            '            <input hidden value="' + dat.id + '">\n' +
                            '            <a class="remove-child-service btn btn-flat">\n' +
                            '                <i class="material-icons">remove</i>\n' +
                            '            </a>\n' +
                            '            <a class="btn btn-flat" target="_blank"\n' +
                            '               href="' + dat.edit_url + '">\n' +
                            '                <i class="material-icons">mode_edit</i>\n' +
                            '            </a>\n' +
                            '        </div>\n' +
                            '    </div>\n' +
                            '</li>';
                        list.append(li);
                        checkHeight(-1);

                        $('#service-list li').sort(function (a, b) {

                            return $(a).data('order') > $(b).data('order')
                        }).appendTo('#service-list')
                    }
                });
            }
        })
    });
})();


function checkHeight(s, added = null) {
    let list_height = parseInt($('#service-list').css('height').replace('px', ''));
    let added_height = 0;
    if (added) {
        added_height = added;
    } else {
        added_height = list_height > 150 ? list_height - 150 : 0;
    }

    let service_panel = $('#service-edit-panel');
    let panel_height = parseInt(service_panel.css('height').replace('px', ''));
    let new_height = s * (panel_height + added_height);

    service_panel.css('cssText', 'height: ' + new_height + 'px !important');
}
