<?php

$authorized = is_authorized($secret);
$payload = get_jwt_payload($secret);

?>
<script>
    $(() => {
        $( "title" ).html('Добро пожаловать');

        $( "#logout" ).on('click', (e) => {
            $.ajax({
                method: 'post',
                url: '../php/logout.php'
            }).then((data) => {
                location.reload();
            });
        });
    });
</script>
<?php
    if (!$authorized)
        die('Not authorized');
?>

<link rel="stylesheet" href="styles/authorized.css">
<link rel="stylesheet" href="styles/fontawesome/css/all.min.css">
<div class="container">
    <header>
        <div class="profile">
            <div class="user">
                <div class="username">
                    <?php echo $payload->username ?>
                </div>

                <div class="border">
                    <i class="fa fa-user fa-lg"></i>
                </div>
            </div>

            <div class="menu">
                <div class="menu_container">
                    <div class="menu-item" id="logout">
                        <i class="fa fa-door-open"></i>
                        <span>Выйти</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <h2>
        <?php if ($payload->role == 'admin'): ?>
            GOCTF{neNAViju_sQl_ZAPR0$1}
        <?php else: ?>
            <div class="text">Ничего нет</div>
            <div class="icon">
                <i class="fa-regular fa-face-smile fa-spin fa-10x"></i>
            </div>
        <?php endif ?>
    </h2>

</div>