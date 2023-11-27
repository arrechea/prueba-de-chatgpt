$(document).ready(function () {
    $('#services_form_button').on('click', function () {
        let form = $('#services_form').serializeArray();

        $.ajax({
            type: 'POST',
            url: window.Services.url,
            data: form
        })
    })
});
