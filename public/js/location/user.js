$(document).ready(function () {
    $('.location-user-button').on('click', function (e) {
        userButtonOnClick(e, $(this).data('user_id'),
            $(this).data('search-email'),
            $(this).data('return-id-input'),
            $(this).data('success-function'),
            $(this).data('alternate-modal-id'),
            $(this).data('on-created'),
            $(this).data('edit-modal-id')
        );
    });
});

function userButtonOnClick(e, user_id = null, email = null, return_id_input = null, success_function, alternate_modal_id = null, on_created = null, edit_modal_id = null) {
    $(document).ready(function () {
        user_id = user_id !== null ? user_id : '';
        let email_url = email !== null && email !== '' ? '?email=' + email : '';
        let modal_id = alternate_modal_id !== null ? alternate_modal_id : 'LocationUser--editModal';
        let modal = $(`#${modal_id}`);
        let url = user_id ? (modal.data('url') + '/' + user_id) : modal.data('url') + email_url;
        if (email_url !== '' && !user_id) {
            if (on_created) {
                url += `&on_created=${on_created}`;
            }
            if (edit_modal_id) {
                url += `&edit_modal_id=${edit_modal_id}`;
            }
        }
        modal.attr('data-href', url);
        modal.data('href', url);
        modal.attr('data-return-id-input', return_id_input);
        modal.data('return-id-input', return_id_input);
        modal.attr('data-success-function', success_function);
        modal.data('success-function', success_function);
        modal.modal('open');
    });
}

function UserListModalOpen(url, modal_id) {
    $(document).ready(function () {
        let modal = $('#' + modal_id);
        modal.data('href', url);
        modal.attr('data-href', url);
        modal.modal('open');
    });
}
