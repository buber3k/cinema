<?php
include('../include/navbar.php');
require_once('../../app/function.php');


$class = new bilet();
$error = $class->walidacja();
$bilet = $class->dane_bilet($_GET['id']);


if(isset($_POST['submit']))
{
    $request = $_POST['data'];
    $class = new bilet($request, $_GET['id']);

    $error = $class->walidacja();
}


?>


<div class="employee-dashboard">


    <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
        <h2>Edycja biletu</h2>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="add-movie">

                    <form class="form-signin" method="post" action="">
                        <div class="text-center mb-4">
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Rodzaj biletu:</label>
                            <input type="text" id="inputPassword" class="form-control" value="<?php echo $bilet['nazwa'] ?>"  name="data[nazwa]">
                            <?php echo $error['nazwa'] ?>
                        </div>


                        <div class="form-label-group">
                            <label for="inputPassword">Cena:</label>
                            <input type="number" id="inputPassword" class="form-control" value="<?php echo $bilet['cena'] ?>"  name="data[cena]">
                            <?php echo $error['cena'] ?>
                        </div>

                        <input type="hidden" name="data[edit]" value="1">


                        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Edytuj bilet</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>



<?php include('../include/footer.php'); ?>


