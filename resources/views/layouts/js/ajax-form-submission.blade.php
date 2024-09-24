<script>
    $(document).on('submit', '.ajax-submission', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = form.attr('method');
        clearMessages();
        
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,  // Important: Prevent jQuery from processing the data
            contentType: false,  // Important: Let the browser set the Content-Type as multipart/form-data
            success: function(response) {
                if (response.status == 'success') {
                    showMessage('success', response.message);
                    if(response.redirect) {
                        redirectTo(response.redirect, 1000);
                    }
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
            let messageHtml = '<div class="alert alert-danger">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                message +
                '</div>';
            $('#show-messages').html(messageHtml);
            $('html, body').animate({
                scrollTop: $('#show-messages').offset().top
            }, 1000);
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
        $('html, body').animate({
            scrollTop: $('#show-messages').offset().top
        }, 1000);
    }
    
    function clearMessages() {
        $('#show-messages').html('');
    }
</script>