<?php
include('../include/navbar.php');
require_once('../../app/function.php');


$class = new pracownik();
$filmy = $class->panel_pracownika();

?>


<div class="employee-dashboard">


    <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
        <h2>Dodawanie filmu</h2>
    </div>

    <div class="container">
        <nav class="nav">
            <a class="nav-link" href="<?php echo $path.'views/admin/dashboard.php'; ?>">Panel pracownika</a>
            <a class="nav-link" href="<?php echo $path.'views/admin/ticket.php'; ?>">Bilety</a>
            <a class="nav-link" href="<?php echo $path.'views/admin/rooms.php'; ?>">Sale</a>
            <a class="nav-link" href="<?php echo $path.'views/admin/users_list.php'; ?>">Lista użytkowników</a>
            <a class="nav-link" href="<?php echo $path.'views/admin/showing_list.php'; ?>">Lista Seansów</a>
            <a class="nav-link" href="<?php echo $path.'views/admin/sell.php'; ?>">Kupione</a>
        </nav>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="new-movie">
                    <a href="<?php echo $path.'views/admin/add_movie.php'; ?>"> <button href="">Dodaj nowy film</button></a>
                </div>
            </div>
        </div>

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
                                    <th scope="col">Czas trwania / min</th>
                                    <th scope="col">Od lat</th>
                                    <th scope="col">Edytuj</th>
                                    <th scope="col">Dodaj seans</th>
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
                                    <td><?php echo $val[$key]['dlugosc']  ?></td>
                                    <td><?php echo $val[$key]['od_lat']  ?></td>
                                    <td>
                                        <a href="<?php echo $path.'views/admin/edit_movie.php?id='.$key; ?>">Edytuj</a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $path.'views/admin/add_showing.php?id_filmu='.$key; ?>">Dodaj</a>
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


