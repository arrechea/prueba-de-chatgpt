$(document).ready(function () {
    $('.user-gift-card').on('click', function (e) {
        AssignGiftCard(e, $(this).data('user_id'), $(this).data('search-email'), $(this).data('return-id-input'), $(this).data('success-function'));

    });

    function AssignGiftCard(e, user_id = null, email = null, return_id_input = null, success_function) {
        $(document).ready(function () {
            user_id = user_id !== null ? user_id : '';
            let email_url = email !== null && email !== '' ? '?email=' + email : '';
            let modal = $('#assign_modal');
            let url = user_id ? (modal.data('url') + '/' + user_id) : modal.data('url') + email_url;
            modal.attr('data-href', url);
            modal.data('href', url);
            modal.attr('data-return-id-input', return_id_input);
            modal.data('return-id-input', return_id_input);
            modal.attr('data-success-function', success_function);
            modal.data('success-function', success_function);
            modal.modal('open');
        });
    }
});
