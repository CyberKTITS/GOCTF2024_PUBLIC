$(() => {
    $( "form" ).on( "submit", function( event ) {
        event.preventDefault();

        $.ajax({
            method: 'post',
            data: $( this ).serialize(),
            url: $( this ).attr('action'),
            success: function(data) {
                try
                {
                    var json = JSON.parse(data);

                    if (json.error)
                        alert(json.message);
                    else if (json.location != undefined)
                        location.href = json.location;
                } catch {}
            }
        })
    });
});