<?php
include('../include/navbar.php');
require_once('../../app/function.php');

$auth = new auth();
$error = $auth->validate();


if(isset($_POST['submit']))
{
    $request = $_POST['data'];
    $auth = new auth($request);
    $error = $auth->validate();
}

?>

    <div class="container">
        <div class="login-site">
            <form class="form-signin" method="post" action="">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-3 font-weight-normal">Zaloguj się</h1>
                </div>

                <div class="form-label-group">
                    <label for="inputEmail">Email:</label>
                    <input type="email" id="inputEmail" class="form-control" name="data[email]" placeholder="Email adres" required autofocus>
                    <?php echo $error['email'] ?>
                </div>

                <div class="form-label-group">
                    <label for="inputPassword">Hasło:</label>
                    <input type="password" id="inputPassword" class="form-control" name="data[haslo]" placeholder="Hasło" required>
                    <?php echo $error['haslo'] ?>
                    <?php echo $error['auth'] ?>
                </div>

                <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Zaloguj</button>
            </form>
        </div>
    </div>

<?php include('../include/footer.php'); ?>