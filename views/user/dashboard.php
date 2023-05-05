<?php
include('../include/navbar.php');
require_once('../../app/function.php');


$class = new user();
$filmy = $class->panel_uzytkownika();
?>


<div class="employee-dashboard">


    <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
        <h2>Filmy</h2>
    </div>

    <div class="container">
        <nav class="nav">
            <a class="nav-link" href="<?php echo $path.'views/user/dashboard.php'; ?>">Panel użytkownika</a>
            <a class="nav-link" href="<?php echo $path.'views/user/ticket.php'; ?>">Bilety</a>
        </nav>
    </div>

    <div class="container">


        <div class="row">
            <div class="all-movies">

                <div class="col-md">
                    <div class="table-responsive-xl">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Tytuł</th>
                                <th scope="col">Gatunek</th>
                                <th scope="col">Reżyser</th>
                                <th scope="col">Od lat</th>
                                <th scope="col">Zobacz</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($filmy as $key => $val)
                            {
                                ?>
                                <th scope="row"><?php echo $key ?></th>
                                <td><?php echo $val[$key]['tytul']  ?></td>
                                <td><?php echo $val[$key]['gatunek']  ?></td>
                                <td><?php echo $val[$key]['rezyser']  ?></td>
                                <td><?php echo $val[$key]['od_lat']  ?></td>
                                <td>
                                    <a href="<?php echo $path.'views/user/single_movie.php?id='.$key; ?>">Zobacz</a>
                                </td>


                                </tr>

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


