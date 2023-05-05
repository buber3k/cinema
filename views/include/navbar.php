<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" >


    <?php
    $folder = explode("/", $_SERVER['REQUEST_URI']);
    $path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$folder[1].'/';
    ?>

    <base href="<?php echo $path ?>">

    <title>Sklep</title>

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <script src="assets/js/bootstrap.min.js"></script>

    <?php
    if(!isset($_SESSION)) session_start();
    ?>


</head>
<body>

<nav>
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between custom-nav">
            <div class="left-site">
                <a href="views/home.php" class="navbar-brand d-flex align-items-center">
                    <strong>Home</strong>
                </a>

            </div>
            <div class="right-site">
                <?php

                if(isset($_SESSION['auth']) && $_SESSION['auth'] == 1)
                {
                    if($_SESSION['role_id'] == 1)
                    {
                        ?>
                        <a href="views/admin/dashboard.php" class="navbar-brand d-flex align-items-center">
                            <strong>Panel admina</strong>
                        </a>
                        <form action="" method="post" class="logout">
                            <input type="hidden" name="logout" value="1">

                            <input type="submit" name="submit-l" class="navbar-brand d-flex align-items-center" value="Wyloguj">
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <a href="views/user/dashboard.php" class="navbar-brand d-flex align-items-center">
                            <strong>Twoje konto</strong>
                        </a>
                        <form action="" method="post" class="logout">
                            <input type="hidden" name="logout" value="1">

                            <input type="submit" name="submit-l" class="navbar-brand d-flex align-items-center" value="Wyloguj">
                        </form>
                        <?php
                    }
                }
                else
                {
                    ?>
                    <a href="views/auth/login.php" class="navbar-brand d-flex align-items-center">
                        <strong>Login</strong>
                    </a>
                    <a href="views/auth/register.php" class="navbar-brand d-flex align-items-center">
                        <strong>Register</strong>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</nav>

<div class="app">

<?php


if(isset($_POST['submit-l']) && $_POST['logout'] == 1)
{
    $_SESSION['auth'] = session_destroy();
    $_SESSION['auth_id'] = session_destroy();
    $_SESSION['role_id'] = session_destroy();
    header("Location: $path");
}

?>