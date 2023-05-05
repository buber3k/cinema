<?php

include('../include/navbar.php');
require_once('../../app/function.php');

$auth = new register();
$error = $auth->validate();


if(isset($_POST['submit']))
{
    $request = $_POST['data'];
    $auth = new register($request);
    $error = $auth->validate();
}

?>




    <div class="container">
        <div class="login-site">
            <form class="form-signin" method="post" action="">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-3 font-weight-normal">Stwórz nowe konto</h1>
                </div>

                <div class="form-label-group">
                    <label for="inputPassword">Imię:</label>
                    <input type="text" id="inputPassword" class="form-control" placeholder="Imię" name="data[imie]">
                    <?php echo $error['imie'] ?>
                </div>

                <div class="form-label-group">
                    <label for="inputPassword">Nazwisko:</label>
                    <input type="text" id="inputPassword" class="form-control" placeholder="Imię" name="data[nazwisko]">
                    <?php echo $error['nazwisko'] ?>
                </div>

                <div class="form-label-group">
                    <label for="inputEmail">Email:</label>
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="data[email]">
                    <?php echo $error['email'] ?>
                </div>

                <div class="form-label-group">
                    <label for="inputPassword">Hasło:</label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="data[haslo]">
                    <?php echo $error['haslo'] ?>
                </div>

                <div class="form-label-group">
                    <label for="inputPassword">Powtórz hasło:</label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="data[r_haslo]">
                    <?php echo $error['r_haslo'] ?>
                </div>

                <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Stwórz konto</button>
            </form>
        </div>
    </div>

<?php include('../include/footer.php'); ?>