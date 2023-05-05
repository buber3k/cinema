<?php
include('../include/navbar.php');
require_once('../../app/function.php');

$class = new pracownik();
$bilety = $class->lista_kupionych();





?>

    <div class="admin-dashboard">
        <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
            <h2>Kupione bilety</h2>
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
                    <div class="table-responsive-xl">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Tytuł</th>
                                <th scope="col">Łączna wartość sprzedaży</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($bilety as $key => $val)
                            {
                                ?>
                                <th scope="row"><?php echo $key ?></th>
                                <td><?php echo $val[$key]['tytul']  ?></td>
                                <td><?php echo $val[$key]['razem']  ?> zł</td>


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

<?php include('../include/footer.php'); ?>