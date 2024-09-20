<script>
    $(document).on('submit', '.ajax-submission', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = form.attr('method');
        const data = form.serialize();
        clearMessages();
        
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                if (response.status == 'success') {
                    showMessage('success', response.message);
                    redirectTo(response.redirect, 2000);
                } else {
                    showMessage('danger', response.message || 'An error occurred');
                }
            },
            error: function(response) {
                if (response.status === 422) {
                    showValidationErrors(response);
                } else {
                    showMessage('danger', response.message || 'An unexpected error occurred.');
                }
            }
        });
    });
    
    function showMessage(type, message) {
        if (!message) message = 'An unexpected error occurred.';
        if (type == 'success') {
            GLOBAL.displayFlashMessage({
                type: "success",
                description: message
            });
        } else {
            $('#show-messages').html(messageHtml);
        }
        
    }
    
    function redirectTo(url, time) {
        setTimeout(function() {
            window.location.href = url;
        }, time);
    }
    
    function showValidationErrors(response) {
        const errors = response.responseJSON.errors;
        let errorHtml = '<div class="alert alert-danger">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<ul>';

        $.each(errors, function(key, value) {
            errorHtml += `<li>${value}</li>`;
        });
        errorHtml += '</ul></div>';
        $('#show-messages').html(errorHtml);
    }
    
    function clearMessages() {
        $('#show-messages').html('');
    }
</script>