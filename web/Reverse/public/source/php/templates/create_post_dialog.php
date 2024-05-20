<form>
    <input type="text" name="title" id="title" required>
    <textarea name="description" id="description"></textarea>
    <input type="submit" value="Создать">
</form>

<style>
    form {
        min-width: 400px;
        display: flex;
        flex-direction: column;
    }

    form textarea {
        width: 100%;
        max-width: 400px;
    }
</style>

<script>
    $( '#dialog_container form' ).on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: '/controllers/create_post.php',
            method: 'post',
            data: $( this ).serialize(),
            success: function(data) {
                try {
                    var response = JSON.parse(data);

                    if (response.created)
                        location.href = "/";
                } catch {}
            }
        })
    });
</script>