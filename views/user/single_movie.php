<?php
include('../include/navbar.php');
require_once('../../app/function.php');


$class = new user();
$filmy = $class->poj_film($_GET['id']);
$seanse = $class->seanse($_GET['id']);
$bilety = $class->bilety();
$miejsce = $class->sale();
$kupione = $class->kupione();



if(isset($_POST['submit']))
{
    $request = $_POST['data'];
    $class->zakup($request, $_GET['id']);

}
?>


<div class="employee-dashboard">


    <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
        <h2><?php echo $filmy['tytul']; ?></h2>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="new-movie">
                    <ul>
                        <li>
                            Reżyser: <?php echo $filmy['rezyser'] ?>
                        </li>
                        <li>
                            Gatunek: <?php echo $filmy['gatunek'] ?>
                        </li>
                        <li>
                            Od lat: <?php echo $filmy['od_lat'] ?>
                        </li>
                        <li>
                            Długość: <?php echo $filmy['dlugosc'] ?>
                        </li>
                    </ul>
                    <p><?php echo $filmy['opis'] ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="all-movies" style="width: 100%;">

                <div class="col-md">
                    <p><h3>Terminy rezerwacji</h3></p>
                    <div class="table-responsive-xl">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Sala</th>
                                <th scope="col">Godzina rozpoczęcia</th>
                                <th scope="col">Rodzaj biletu</th>
                                <th scope="col">Miejsce</th>
                                <th scope="col">Kup</th>
                            </tr>
                            </thead>
                            <tbody>

                                <?php foreach($seanse as $key => $val)
                                {
                                    ?>
                                <form action="" method="post">
                                    <td><?php echo $val[$key]['data']  ?></td>
                                    <td><?php echo $val[$key]['id_sali']  ?>

                                    </td>
                                    <td><?php echo $val[$key]['od']  ?></td>
                                    <td>
                                        <div class="form-group col-md-4">
                                            <select id="inputState" name="data[cena]" class="form-control" style="width: 130px;" required>
                                                <?php foreach($bilety as $k => $value)
                                                {

                                                    ?>
                                                    <option value="<?php echo $value[$k]['cena'] ?>"><?php echo $value[$k]['nazwa'] ?> / <?php echo $value[$k]['cena'] ?> zł</option>
                                                    <?php
                                                }
                                                ?>

                                            </select>
                                        </div>

                                    </td>
                                    <td>
                                        <?php foreach($miejsce as $klucz => $wartosc)
                                        {
                                            if($klucz == $val[$key]['id_sali'])
                                            {

                                                ?>

                                                <div class="form-group col-md-4">
                                                    <select id="inputState" name="data[miejsce]" class="form-control" style="width: 130px;">
                                                        <?php for($i = 1; $i <= $wartosc[$klucz]['ilosc_mejsc']; $i++)
                                                        {
//                                                            foreach($kupione as $id => $miejsce)
//                                                            {
//
//                                                            if($miejsce[$key]['miejsce'] != $i || !isset($miejsce[$key]['miejsce'])){
//
//
                                                            ?>
                                                                <option value="<?php echo $i ?>">Miejsce <?php echo $i ?></option>
                                                            <?php
//                                                            }
//                                                            }
//                                                            if(!isset($kupione) || empty($kupione))
//                                                            {
//                                                                ?>
<!--                                                                <option value="--><?php //echo $i ?><!--">Miejsce --><?php //echo $i ?><!--</option>-->
<!---->
<!--                                                                --><?php
//                                                            }

                                                        }
                                                        ?>

                                                    </select>
                                                </div>

                                                <?php
                                            ?>



                                            <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?php echo $key  ?>" name="data[id_seansu]">
                                        <input type="hidden" value="<?php echo $filmy['tytul']; ?>" name="data[tytul]">
                                            <input type="submit" name="submit" value="kup">

                                    </td>


                                    </tr>

                                </form>

                                    <?php
                                }
                                ?>

                            <tr>

                            </tbody>
                        </table>


                    </div>
                </div>

            </div>
        </div>

    </div>
</div>



<?php include('../include/footer.php'); ?>


