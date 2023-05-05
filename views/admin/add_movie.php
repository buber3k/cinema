<?php
include('../include/navbar.php');
require_once('../../app/function.php');


$movie = new film();
$error = $movie->walidacja();


if(isset($_POST['submit']))
{
    $request = $_POST['data'];
    $movie = new film($request);

    $error = $movie->walidacja();
}


?>


<div class="employee-dashboard">


    <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
        <h2>Dodawanie filmu</h2>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="add-movie">

                    <form class="form-signin" method="post" action="">
                        <div class="text-center mb-4">
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Tytul:</label>
                            <input type="text" id="inputPassword" class="form-control"  name="data[tytul]">
                            <?php echo $error['tytul'] ?>
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Reżyser:</label>
                            <input type="text" id="inputPassword" class="form-control"  name="data[rezyser]">
                            <?php echo $error['rezyser'] ?>
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Gatunek:</label>
                            <input type="text" id="inputPassword" class="form-control"  name="data[gatunek]">
                            <?php echo $error['gatunek'] ?>
                        </div>

                        <div class="form-label-group">
                            <label for="inputEmail">Długość (w minutach):</label>
                            <input type="number" id="inputEmail" class="form-control"  name="data[dlugosc]">
                            <?php echo $error['dlugosc'] ?>
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Film dozwolony od lat:</label>
                            <input type="number" id="inputPassword" class="form-control"  name="data[od_lat]">
                            <?php echo $error['od_lat'] ?>
                        </div>

                        <div class="form-label-group">
                            <label for="inputPassword">Opis:</label>
                            <textarea name="data[opis]" id="" class="form-control"   cols="100" rows="5"></textarea>
                            <?php echo $error['opis'] ?>

                        </div>

                        <input type="hidden" name="data[edit]" value="0">


                        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Dodaj film</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>



<?php include('../include/footer.php'); ?>


