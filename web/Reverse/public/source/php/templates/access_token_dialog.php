<form data-postid="<?php echo $_GET['post_id'] ?>">
    <input type="text" name="access_token" id="access_token">
    <input type="submit" value="Открыть"></input>
</form>

<script>
    $( '#dialog_container form' ).on('submit', function(event) {
        event.preventDefault();

        var postid = $( this ).data('postid');
        var access_token = $( '#access_token' ).val();

        $.ajax({
            url: '/controllers/post_access.php',
            method: 'post',
            data: { postid: postid, access_token: access_token },
            success: function(data) {
                try {
                    var response = JSON.parse(data);

                    if (response.error)
                        alert(response.message);
                    else
                        location.href = `/post/${postid}/${access_token}`;
                } catch {}
            }
        });
    });
</script>