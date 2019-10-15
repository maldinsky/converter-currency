$(document).ready(function() {
    $('.btn-convert').on('click', function () {

        $('input[name="amount"]').removeClass('is-invalid');

        let currencies = {
            'from': $('select[name="currency_from"]').val(),
            'to': $('select[name="currency_to"]').val(),
            'amount': $('input[name="amount"]').val()
        };

        if(!currencies.amount){
            $('input[name="amount"]').addClass('is-invalid');
            return false;
        }

        $.ajax({
            url: 'converter',
            type: 'post',
            data: currencies,
            dataType: 'json',
            success: function (json) {
                $('input[name="result_convert"]').val(json);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });


    $('.btn-save-setting').on('click', function () {
        $.ajax({
            url: '/settings/save',
            type: 'post',
            data: $('#setting-form').serialize(),
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
});

