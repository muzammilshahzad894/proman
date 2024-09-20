var stripePubKey = $('meta[name="stripe-key"]').attr('content');

console.log(stripePubKey);

Stripe.setPublishableKey(stripePubKey);

var $form = $('#booking_form');

$form.submit(function (e) {
    e.preventDefault();
    $('#charge-error').addClass('hidden');
    $form.find('button').prop('disabled', true);
    Stripe.card.createToken({
        number: $('#bt_cc').val(),
        cvc: $('#card-cvc').val(),
        exp_month: $('#card-expiry-month').val(),
        exp_year: $('#card-expiry-year').val(),
        // name: $('#card-name').val()
    }, stripeResponseHandler);
    return false; // it will stop from continuing form to submit to backend
});

function stripeResponseHandler(status, response) {
    if (response.error) {
        $('#charge-error').removeClass('hidden').text(response.error.message);
        $form.find('button').prop('disabled', false);
    } else {
        var token = response.id;
        console.log('Token: ' + token);

        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // Submit the form:
        $form.get(0).submit();
    }
}
