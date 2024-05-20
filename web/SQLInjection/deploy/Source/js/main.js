$(() => {
    load_search_data();

    $('#search_form').submit(e =>
    {
        e.preventDefault();
        load_search_data();
    });
});

function load_search_data()
{
    var method = $('#search_form').attr('method');
    var action = $('#search_form').attr('action');
    var data = `script=${$('#script').val()}`.toLowerCase();

    $.ajax({
        type: method,
        url: action,
        data: data,
        success: function(result) {
            $('#search_result').html(result);
        }
    });
}