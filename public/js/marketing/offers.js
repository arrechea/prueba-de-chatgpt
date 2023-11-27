$(document).ready(function () {
    let type = $('[name="type"]:checked').val();
    radioOnChange(type);

    $('input[type=radio][name="type"]').change(function () {
        let type = this.value;
        radioOnChange(type);
    })
});

function radioOnChange(type) {
    let content = $('#marketing_content_type');
    let inputs = '';
    content.empty();

    switch (type) {
        case 'buy_get':
            let prev_buy = $('#prev_buy_get_buy').val();
            let prev_get = $('#prev_buy_get_get').val();
            inputs =
                '<div class="row">' +
                '   <div class="input-field col s8 m3">' +
                '      <label for="buy_get_get">' + window.Offer.lang.buy_get + '</label> ' +
                '      <input type="number" id="buy_get_get" name="buy_get_get" class="input"' +
                '      value="' + prev_get + '">' +
                '   </div>' +
                '   <div class="input-field col s8 m3">' +
                '     <label for="buy_get_buy">' + window.Offer.lang.buy_buy + '</label>' +
                '     <input type="number" id="buy_get_buy" name="buy_get_buy" class="input"' +
                '      value="' + prev_buy + '">' +
                '   </div>' +
                '</div>';
            break;
        case 'discount':
            $('#two_for_one_content').hide();
            $('#discount_content').show();
            let prev_type = $('#prev_discount_type').val();
            let prev_number = $('#prev_discount_number').val();
            inputs =
                '<label class="col s8" style="font-size: small">' + window.Offer.lang.discount_type + '</label>' +
                '<div class="row">' +
                '   <div class="col s8">' +
                '       <div class="switch">' +
                '            <label style="font-size: small">' +
                '                ' + window.Offer.lang.price +
                '                <input type="checkbox" name="discount_type_check" ' +
                (prev_type.trim() === 'percent' ? 'checked="checked"' : '') + '>' +
                '                <span class="lever"></span>\n' +
                '                ' + window.Offer.lang.percent +
                '            </label>' +
                '        </div>' +
                '   </div>' +
                '</div>' +
                '<div class="row">' +
                '   <div class="input-field col s8 m6">' +
                '      <label for="discount_number">' + window.Offer.lang.discount_number + '</label>' +
                '      <input type="number" id="discount_number" name="discount_number" class="input"' +
                '      value="' + prev_number + '">' +
                '   </div>' +
                '</div>';
            break;
        case 'credits':
            let prev_credits = $('#prev_credits').val();
            inputs =
                '<div class="row">' +
                '   <div class="input-field col s8 m6">' +
                '      <label for="credits">' + window.Offer.lang.credits_number + '</label>' +
                '      <input type="number" id="credits" name="credits" class="input"' +
                '      value="' + prev_credits + '">' +
                '   </div>' +
                '</div>';
            break;
        default:
            inputs = '';
            break;
    }

    content.append(inputs);
}
