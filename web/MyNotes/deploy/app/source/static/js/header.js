$(() => {
    $("#logout").on('click', function(e) {
        $.ajax({
            method: 'POST',
            url: '/logout',

            success: function(data, status, xhr) {
                newLocation = xhr.getResponseHeader("Location");
                if (newLocation != undefined) {
                    location.href = newLocation;
                }
            }
        });
    })
});