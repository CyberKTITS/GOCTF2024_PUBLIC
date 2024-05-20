$(() => {

    $('form').on('submit', function(e) {
        e.preventDefault();

        const action = $( this ).attr('action');
        const method = $( this ).attr('method');
        const data = $( this ).serialize();

        $('form input.error').removeClass('error');
        $('.form-container *').removeAttr('data-error-content');

        $.ajax({
            url: action,
            method: method,
            data: data,

            success: function(data, status, xhr) {
                newLocation = xhr.getResponseHeader("Location");
                if (newLocation != undefined) {
                    location.href = newLocation;
                }
            },

            error: function(err) {
                if (err.status === 400) {
                    var error = JSON.parse(err.responseText)

                    if ((elements = error.highlight_elements) != undefined) {

                        $(elements.forEach(element => {
                            $(`form input[name=${element}]`).addClass('error');
                        }))
                    }

                    if ((alert_elements = error.alert) != undefined) {
                        alert_elements.forEach(alert_object => 
                            $(alert_object.selector).attr('data-error-content', alert_object.message));
                    }
                }
            }
        });
    });

});