<?php
include('../include/navbar.php');
require_once('../../app/function.php');

$class = new pracownik();
$users = $class->lista_uzytkownikow();


if(isset($_POST['submit']))
{
    $request = $_POST['id'];
    $class->zmiana_roli_kont($request);
}

?>

    <div class="admin-dashboard">
        <div class="head" style="display: flex; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
            <h2>Lista użytkowników / zmiana roli</h2>
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
                        <form action="" method="post">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">Imię</th>
                                    <th scope="col">Nazwisko</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Pracownik</th>
                                    <th scope="col">Użytkownik</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $key => $val)
                                    {
                                        ?>
                                        <th scope="row"><?php echo $key ?></th>
                                        <td><?php echo $val[$key]['imie']  ?></td>
                                        <td><?php echo $val[$key]['nazwisko']  ?></td>
                                        <td><?php echo $val[$key]['email']  ?></td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id[<?php echo $key ?>]" id="exampleRadios1" value="1" <?php ( $val[$key]['role'] == 1 ?  print_r('checked') :  '')?>  <?php ($_SESSION['auth_id'] == $key ? print_r('disabled') : '')?> >
                                                <label class="form-check-label" for="exampleRadios1">
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id[<?php echo $key ?>]" id="exampleRadios1" value="2" <?php ( $val[$key]['role'] == 2 ?  print_r('checked') :  '')?>  <?php ($_SESSION['auth_id'] == $key ? print_r('disabled') : '')?>>
                                                <label class="form-check-label" for="exampleRadios1">
                                                </label>
                                            </div>
                                        </td>
                                        </tr>

                                        <?php
                                    }
                                    ?>

                                    <tr>

                                </tbody>
                            </table>

                            <div class="submit-button">
                                <input type="submit" name="submit" value="Zmień">

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include('../include/footer.php'); ?>