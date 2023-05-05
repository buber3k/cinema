<?php
include('../include/navbar.php');
require_once('../../app/function.php');

$class = new seans();
$seanse = $class->lista_seansow();

if(isset($_POST['submit']))
{

    $id = $_POST['seans_id'];
    $class->usun_seans($id);
}


?>

    <div class="admin-dashboard">
        <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
            <h2>Seanse</h2>
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
                                <th scope="col">Film</th>
                                <th scope="col">Data</th>
                                <th scope="col">Od godziny</th>
                                <th scope="col">Sala</th>
                                <th scope="col">Usuń seans</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($seanse as $key => $val)
                            {
                                ?>
                                <th scope="row"><?php echo $key ?></th>
                                <td><?php echo $val[$key]['tytul']  ?></td>
                                <td><?php echo $val[$key]['data']  ?></td>
                                <td><?php echo $val[$key]['od']  ?></td>
                                <td><?php echo $val[$key]['id_sali']  ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="seans_id" value="<?php echo $key  ?>">
                                        <input type="submit" name="submit" value="usuń">
                                    </form>
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

<?php include('../include/footer.php'); ?>