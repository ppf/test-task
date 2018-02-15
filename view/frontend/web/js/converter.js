require([
        "jquery"
    ],
    function ($) {
        "use strict";

        var form = $('#convertForm');

        form.on('click', '#convertButton', function () {

            // check if for  is valid
            if (form.valid()) {

                var amount = $('#amount').val();
                var currency = $('#currency').val();

                $.ajax({
                    showLoader: true,
                    url: $('#convertForm').attr('action'),
                    data: {'ajax': 1, 'amount': amount, 'from_currency': currency},
                    type: "POST",
                    dataType: 'json'
                }).done(function (data) {

                    // display response message if exists, if not clear message block
                    $('#message').html(data.message);

                    // display response value if exists, if not clear result block

                    if (data.value) {
                        $('#result').html(amount + ' ' + currency + " = " + data.value + ' ' + data.currency);
                    } else {
                        $('#result').html("");
                    }

                });
            }

            return false;
        });

    });
