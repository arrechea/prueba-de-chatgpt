$(document).ready(function () {
    let checks = $('.payment_method_checks');
    checks.each(function (index, item) {
        let method = $(item);
        let settings = method.find('.config_settings');
        let front = method.find('.payment_method_front');
        let back = method.find('.payment_method_back');
        method.find('.payment_method_active').change(function () {
            if (this.checked) {
                settings.stop();
                settings.show();
                front.prop('disabled', false);
                back.prop('disabled', false);
            } else {
                settings.stop();
                settings.hide();
                front.prop('disabled', true);
                back.prop('disabled', true);
            }
        })
    });
});
