<style>
    .gympass--service-locations-container:not(.active) {
        margin: 15px;
        display: none;
    }

    .gympass--service-locations-container.active {
        display: block;
    }
</style>

<script>
    jQuery(document).ready(function ($) {
        $('input[name="gympass_active"]').on('change', function () {
            let $_this = $(this);
            let container = $('.gympass--service-locations-container');
            if ($_this.is(':checked')) {
                container.removeClass('active').addClass('active');
            } else {
                container.removeClass('active');
            }
        });
    })
</script>
