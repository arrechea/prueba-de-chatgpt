$(document).ready(function () {
    checkEnabled();

    $('#can_subscribe').on('change', function () {
        checkEnabled();
    });

    function checkEnabled() {
        if ($('#can_subscribe')[0].checked) {
            $('#number_of_tries').prop('disabled', false);
        } else {
            $('#number_of_tries').prop('disabled', true);
        }
    }
});
