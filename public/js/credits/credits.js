$(document).ready(function () {
    $('#services_form_button').on('click', function () {
        let form = $('#services_form').serializeArray();

        $.ajax({
            type: 'POST',
            url: window.Services.url,
            data: form
        })
    });

    $('[name="level"]').on('change', function () {
        var selected_value = $("input[name='level']:checked").val();
        if (selected_value === 'location') {
            $('.location-selector').removeClass('active').addClass('active');
        } else {
            $('.location-selector').removeClass('active');
        }
    });
});
