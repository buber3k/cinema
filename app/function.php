<?php


//=============== połączenie z bazą danych ==============

class db
{

    public function connect()
    {

        $connect = pg_connect( "host=localhost dbname=URDBNAME user=YOURUSERNAME password=YOURPASSWORD"  );
        if(!$connect){
            echo  'Błąd połączenia z bazą';
            exit();
        }

        return $connect;
    }
}



// ======================= rejestracje ===============



class register extends db
{
    private $name;
    private $surname;
    private $email;
    private $password;
    private $r_password;
    public $error;



    function __construct($request = null)
    {
        $this->name = $request['imie'];
        $this->surname = $request['nazwisko'];
        $this->email = $request['email'];
        $this->password = $request['haslo'];
        $this->r_password = $request['r_haslo'];

        $this->error['validate'] = 0;
    }



    public function validate()
    {
        $required = ['imie', 'nazwisko', 'email', 'haslo', 'r_haslo'];

        foreach($required as $value)
        {
            $this->error[$value] = '';
        }




        if(isset($this->name) && $this->name == '')
        {
            $this->error['imie'] = 'To pole jest wymagane';
            $this->error['validate'] = 1;
        }


        if(isset($this->surname) && $this->surname == '')
        {
            $this->error['nazwisko'] = 'To pole jest wymagane';
            $this->error['validate'] = 1;
        }


        if(isset($this->email) && $this->email == '')
        {
            $this->error['email'] = 'To pole jest wymagane';
            $this->error['validate'] = 1;
        }
        elseif(isset($this->email) && $this->email != '')
        {
            $connect = $this->connect();
            $query = ("SELECT id FROM uzytkownicy WHERE email = '$this->email'");

            $wynik = pg_query($connect, $query);
            pg_close($connect);


            $unique = pg_num_rows($wynik);

            if($unique > 0){
                $this->error['email'] = 'Taki email już istnieje';
                $this->error['validate'] = 1;
            }
        }


        if(isset($this->password) && $this->password == '')
        {
            $this->error['haslo'] = 'To pole jest wymagane';
            $this->error['validate'] = 1;
        }


        if(isset($this->r_password) && $this->r_password == '')
        {
            $this->error['r_haslo'] = 'To pole jest wymagane';
            $this->error['validate'] = 1;
        }
        elseif(isset($this->r_password) && $this->r_password != $this->password)
        {
            $this->error['r_haslo'] = 'Nieprawidłowe hasło';
            $this->error['validate'] = 1;
        }



        if(!isset($this->name) || !isset($this->email) || !isset($this->password) || !isset($this->r_password))
        {
            $this->error['validate'] = 1;
        }



        if($this->error['validate'] == 1)
        {
            return $this->error;
        }
        else
        {
            return $this->register();
        }
    }



    protected function register()
    {

        $password = md5($this->password);

        $connect = $this->connect();

        $q = ("SELECT * FROM uzytkownicy");
        $wynik = pg_query($connect, $q);

        $count = pg_num_rows($wynik);



        if($count == 0)
        {
            $id_role = 1;
        }
        else
        {
            $id_role = 2;
        }

        $q2 = ("INSERT INTO uzytkownicy (imie, nazwisko, email, haslo, role) VALUES ('$this->name', '$this->surname', '$this->email', '$password', '$id_role')");
        pg_query($connect, $q2);

        $user = ("SELECT id FROM users WHERE email = '$this->email'");
        $wynik2 = pg_query($connect, $user);
        $id = pg_fetch_array($wynik2);

        pg_close($connect);

        if(!isset($_SESSION)) session_start();

        $_SESSION['auth'] = 1;
        $_SESSION['auth_id'] = $id['id'];
        $_SESSION['role_id'] = $id_role;

        if($id_role == 1)
        {
            return header("Location: ../admin/dashboard.php");
        }
        else
        {
            return header("Location: ../user/dashboard.php");
        }

    }
}




//==================== login ====================


class auth extends db
{
    private $email;
    private $password;
    public $error = Array();




    public function __construct($request = null)
    {
        $this->email = $request['email'];
        $this->password = $request['haslo'];
        $this->error['validate'] = false;
    }




    public function validate()
    {
        $validate = ['email', 'haslo', 'auth'];
        foreach($validate as $val)
        {
            $this->error[$val] = '';
        }

        $connect = $this->connect();

        if(isset($this->email) && $this->email != '')
        {
            $query = ("SELECT email FROM uzytkownicy WHERE email='$this->email'");
            $wynik = pg_query($connect, $query);

            if(pg_num_rows($wynik) == 0)
            {
                $this->error['email'] = "Zły email";
                $this->error['validate'] = true;
            }
        }
        elseif(isset($this->email) && $this->email == '')
        {
            $this->error['email'] = "To pole jest wymagane";
            $this->error['validate'] = true;
        }


        if(isset($this->password) && $this->password == '')
        {
            $this->error['haslo'] = "To pole jest wymagane";
            $this->error['validate'] = true;
        }



        if($this->error['validate'] == true)
        {
            return $this->error;
        }
        else
        {
            return $this->login();
        }
    }




    protected function login()
    {
        $pass = md5($this->password);
        $connect = $this->connect();
        $query = ("SELECT * FROM uzytkownicy WHERE email='$this->email' AND haslo='$pass' LIMIT 1");
        $wynik = pg_query($connect, $query);

        if(pg_num_rows($wynik) == 0)
        {
            $this->email['auth'] = "Błędne dane";
            return $this->error;
        }
        else
        {
            $user = pg_fetch_array($wynik);

            if(!isset($_SESSION)) session_start();
            $_SESSION['auth'] = 1;
            $_SESSION['auth_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role'];


            if($user['role'] == 1)
            {
                return header("Location: ../admin/dashboard.php");
            }
            else
            {
                return header("Location: ../user/dashboard.php");
            }
        }
    }
}







//======================= user =======================


class user extends db
{

    public function panel_uzytkownika()
    {
        $connect = $this->connect();
        $filmy = [];

        $query = ("SELECT filmy.id, tytul, rezyser, gatunek, od_lat FROM filmy, seanse");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $filmy[$row['id']] = array(
                $row['id'] => array(
                    'tytul' => $row['tytul'],
                    'rezyser' => $row['rezyser'],
                    'gatunek' => $row['gatunek'],
                    'od_lat' => $row['od_lat'],
                ),
            );
        }

        return $filmy;
    }


    public function poj_film($id)
    {
        $connect = $this->connect();
        $film = [];

        $query = ("SELECT id, tytul, opis, dlugosc, rezyser, gatunek, od_lat FROM filmy WHERE id = $id");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $film = array(
                    'tytul' => $row['tytul'],
                    'rezyser' => $row['rezyser'],
                    'gatunek' => $row['gatunek'],
                    'od_lat' => $row['od_lat'],
                    'dlugosc' => $row['dlugosc'],
                    'opis' => $row['opis'],
                    'id' => $row['id'],
            );
        }

        return $film;
    }


    public function seanse($id)
    {
        $connect = $this->connect();
        $seanse = [];

        $query = ("SELECT id, id_filmu, id_sali, data, od  FROM seanse WHERE id_filmu = $id");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $seanse[$row['id']] = array(
                $row['id'] => array(
//                'id' => $row['tytul'],
                'id_filmu' => $row['id_filmu'],
                'id_sali' => $row['id_sali'],
                'data' => $row['data'],
                'od' => $row['od'],
                ),
            );
        }

        return $seanse;
    }


    public function bilety()
    {
        $connect = $this->connect();
        $bilety = [];

        $query = ("SELECT * FROM bilety");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $bilety[$row['id']] = array(
                $row['id'] => array(
                    'cena' => $row['cena'],
                    'nazwa' => $row['nazwa'],
                ),
            );
        }

        return $bilety;
    }


    public function sale()
    {
        $connect = $this->connect();
        $miejsce = [];

        $query = ("SELECT * FROM sale");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $miejsce[$row['id']] = array(
                $row['id'] => array(
                    'ilosc_mejsc' => $row['ilosc_miejsc'],
                ),
            );
        }

        return $miejsce;
    }



    public function kupione()
    {
        $connect = $this->connect();
        $kupione = [];

        $query = ("SELECT id_seansu, miejsce FROM kupione");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $kupione[$row['id_seansu']] = array(
                $row['id_seansu'] => array(
                    'miejsce' => $row['miejsce'],
                ),
            );
        }

        return $kupione;
    }


    public function lista_blietów()
    {
        $connect = $this->connect();
        $id = $_SESSION['auth_id'];
        $bilety = null;


        $query = ("SELECT * FROM kupione WHERE id_uzytkownika = $id");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $bilety[$row['id']] = array(
                $row['id'] => array(
                    'miejsce' => $row['miejsce'],
                    'cena' => $row['cena'],
                    'data' => $row['data'],
                    'sala' => $row['sala'],
                    'od' => $row['od'],
                    'tytul' => $row['tytul'],
                ),
            );
        }

        return $bilety;
    }



    public function zakup($request, $id)
    {

        $connect = $this->connect();
        $kupione = [];
        $id_seansu = $request['id_seansu'];

        $query = ("SELECT * FROM seanse WHERE id = $id_seansu ");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $kupione = array(

                    'data' => $row['data'],
                    'od' => $row['od'],
                    'id_sali' => $row['id_sali'],
            );
        }

        $miejsce = $request['miejsce'];
        $cena = $request['cena'];
        $data = $kupione['data'];
        $tytul = $request['tytul'];
        $od = $kupione['od'];
        $id_sali = $kupione['id_sali'];
        $id_uzytkownika = $_SESSION['auth_id'];


        $q2 = ("INSERT INTO kupione (id_seansu, id_filmu, miejsce, cena, id_uzytkownika, data, sala, od, tytul) VALUES ('$id_seansu', '$id', '$miejsce', '$cena', '$id_uzytkownika', '$data', '$id_sali', '$od', '$tytul')");
        pg_query($connect, $q2);


        $q3 = ("SELECT * FROM podsumowanie WHERE id_filmu = $id ");
        $w3 = pg_query($connect, $q3);

        $row = pg_fetch_all($w3);


        if(!isset($row) || empty($row))
        {

            $q4 = ("INSERT INTO podsumowanie (id_filmu, razem) VALUES ('$id', '$cena')");
            pg_query($connect, $q4);
        }
        else
        {

            $razem = $cena + $row[0]['razem'];
            $q5 = ("UPDATE podsumowanie SET id_filmu = '$id', razem = $razem  WHERE id_filmu = $id");
            pg_query($connect, $q5);

        }



        return header("Location: ../user/dashboard.php");
    }

}




//============= autoryzacja ============




class checkAuth
{


    // sprawdzenie czy użytkownik jest zalogowany

    public function user()
    {
        $folder = explode("/", $_SERVER['REQUEST_URI']);
        $path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$folder[1].'/';

        if(!isset($_SESSION['auth']) || $_SESSION['auth'] != 1)
        {
            header("Location: $path");
        }
    }


    // sprawdzenie czy użytkownik jest zalogowany i jest adminem

    public function admin()
    {
        $folder = explode("/", $_SERVER['REQUEST_URI']);
        $path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$folder[1].'/';

        if(!isset($_SESSION['auth']) || $_SESSION['auth'] != 1 || $_SESSION['role_id'] != 1)
        {
            header("Location: $path");
        }
    }
}



//====================== panel pracownika ===============




class pracownik extends db
{

    public function lista_uzytkownikow()
    {
        $connect = $this->connect();
        $users = [];

        $query = ("SELECT * FROM uzytkownicy");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $users[$row['id']] = array(
                $row['id'] => array(
                    'imie' => $row['imie'],
                    'nazwisko' => $row['nazwisko'],
                    'email' => $row['email'],
                    'role' => $row['role'],
                ),
            );
        }

        return $users;
    }


    public function zmiana_roli_kont($request)
    {
        $connect = $this->connect();

        foreach($request as $key => $val)
        {
            $query = ("UPDATE uzytkownicy SET role = '$val'  WHERE id = $key");
            pg_query($connect, $query);
        }

        pg_close($connect);


        return header("Refresh:0");
    }


    public function panel_pracownika()
    {
        $connect = $this->connect();
        $filmy = [];

        $query = ("SELECT * FROM filmy");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $filmy[$row['id']] = array(
                $row['id'] => array(
                    'tytul' => $row['tytul'],
                    'opis' => $row['opis'],
                    'rezyser' => $row['rezyser'],
                    'gatunek' => $row['gatunek'],
                    'od_lat' => $row['od_lat'],
                    'dlugosc' => $row['dlugosc'],
                ),
            );
        }

        return $filmy;
    }



    public function lista_kupionych()
    {
        $connect = $this->connect();
        $sell = [];

        $query = ("SELECT podsumowanie.id, razem, filmy.tytul FROM podsumowanie JOIN filmy ON podsumowanie.id_filmu = filmy.id;");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $sell[$row['id']] = array(
                $row['id'] => array(
                    'razem' => $row['razem'],
                    'tytul' => $row['tytul'],
                ),
            );
        }

        return $sell;
    }

}





class film extends db
{
    public $tytul;
    public $opis;
    public $gatunek;
    public $rezyser;
    public $od_lat;
    public $dlugosc;
    public $edit;
    public $id;
    public $error;



    function __construct($request = null, $id = null)
    {
        $this->tytul = $request['tytul'];
        $this->opis = $request['opis'];
        $this->gatunek = $request['gatunek'];
        $this->rezyser = $request['rezyser'];
        $this->od_lat = $request['od_lat'];
        $this->dlugosc = $request['dlugosc'];
        $this->id = $id;

        $this->edit = $request['edit'];

        $this->error['validate'] = 0;
    }



    public function walidacja()
    {
        $required = ['tytul', 'opis', 'gatunek', 'rezyser', 'od_lat', 'dlugosc'];

        foreach($required as $value)
        {
            $this->error[$value] = '';
        }


        foreach($required as $req)
        {
            if(isset($this->$req) && $this->$req == '')
            {
                $this->error[$req] = 'To pole jest wymagane';
                $this->error['validate'] = 1;
            }

            if(!isset($this->$req))
            {
                $this->error['validate'] = 1;
            }
        }



        if($this->error['validate'] == 1)
        {
            return $this->error;
        }
        elseif(isset($this->edit) && $this->edit == 1)
        {
            return $this->zapisz_zmiany();
        }
        else
        {
            return $this->dodaj_film();
        }
    }



    public function dodaj_film()
    {
        $connect = $this->connect();
        $query = ("INSERT INTO filmy (tytul, opis, rezyser,  gatunek, od_lat, dlugosc) VALUES ('$this->tytul', '$this->opis', '$this->rezyser', '$this->gatunek', '$this->od_lat', '$this->dlugosc')");
        $x = pg_query($connect, $query);

        pg_close($connect);

        return header("Location: dashboard.php");
    }



    public function dane_filmu($id)
    {
        $connect = $this->connect();
        $film = [];

        $query = ("SELECT * FROM filmy WHERE id = $id");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $film = array(

                'tytul' => $row['tytul'],
                'opis' => $row['opis'],
                'rezyser' => $row['rezyser'],
                'gatunek' => $row['gatunek'],
                'od_lat' => $row['od_lat'],
                'dlugosc' => $row['dlugosc'],

            );
        }

        return $film;
    }


    public function zapisz_zmiany()
    {
        $connect = $this->connect();


            $query = ("UPDATE filmy SET tytul = '$this->tytul', opis = '$this->opis', rezyser = '$this->rezyser', gatunek = '$this->gatunek', od_lat = '$this->od_lat', dlugosc = '$this->dlugosc'  WHERE id = $this->id");
            pg_query($connect, $query);

        pg_close($connect);



        return header("Refresh:0");
    }
}


//===== bilety ===============

class bilet extends db
{
    public $nazwa;
    public $cena;

    public $edit;
    public $id;
    public $error;



    function __construct($request = null, $id = null)
    {
        $this->nazwa = $request['nazwa'];
        $this->cena = $request['cena'];

        $this->id = $id;

        $this->edit = $request['edit'];

        $this->error['validate'] = 0;
    }



    public function walidacja()
    {
        $required = ['nazwa', 'cena'];

        foreach($required as $value)
        {
            $this->error[$value] = '';
        }


        foreach($required as $req)
        {
            if(isset($this->$req) && $this->$req == '')
            {
                $this->error[$req] = 'To pole jest wymagane';
                $this->error['validate'] = 1;
            }

            if(!isset($this->$req))
            {
                $this->error['validate'] = 1;
            }
        }



        if($this->error['validate'] == 1)
        {
            return $this->error;
        }
        elseif(isset($this->edit) && $this->edit == 1)
        {
            return $this->zapisz_zmiany();
        }
        else
        {
            return $this->dodaj_bilet();
        }
    }


    public function lista_biletow()
    {
        $connect = $this->connect();
        $bilety = [];

        $query = ("SELECT * FROM bilety");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $bilety[$row['id']] = array(
                $row['id'] => array(
                    'nazwa' => $row['nazwa'],
                    'cena' => $row['cena'],
                ),
            );
        }

        return $bilety;
    }


    public function dodaj_bilet()
    {
        $connect = $this->connect();
        $query = ("INSERT INTO bilety (nazwa, cena) VALUES ('$this->nazwa', '$this->cena')");
        pg_query($connect, $query);
        pg_close($connect);

        return header("Location: ticket.php");
    }



    public function dane_bilet($id)
    {
        $connect = $this->connect();
        $bilet = [];

        $query = ("SELECT * FROM bilety WHERE id = $id");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $bilet = array(

                'nazwa' => $row['nazwa'],
                'cena' => $row['cena'],
            );
        }

        return $bilet;
    }


    public function zapisz_zmiany()
    {
        $connect = $this->connect();


        $query = ("UPDATE bilety SET nazwa = '$this->nazwa', cena = '$this->cena' WHERE id = $this->id");
        pg_query($connect, $query);

        pg_close($connect);



        return header("Refresh:0");
    }
}






//===== Sale kinowe ===============

class sale extends db
{
    public $ilosc_miejsc;

    public $edit;
    public $id;
    public $error;



    function __construct($request = null, $id = null)
    {
        $this->ilosc_miejsc = $request['ilosc_miejsc'];

        $this->id = $id;

        $this->edit = $request['edit'];

        $this->error['validate'] = 0;
    }



    public function walidacja()
    {
        $required = ['ilosc_miejsc'];

        foreach($required as $value)
        {
            $this->error[$value] = '';
        }


        foreach($required as $req)
        {
            if(isset($this->$req) && $this->$req == '')
            {
                $this->error[$req] = 'To pole jest wymagane';
                $this->error['validate'] = 1;
            }

            if(!isset($this->$req))
            {
                $this->error['validate'] = 1;
            }
        }



        if($this->error['validate'] == 1)
        {
            return $this->error;
        }
        elseif(isset($this->edit) && $this->edit == 1)
        {
            return $this->zapisz_zmiany();
        }
        else
        {
            return $this->dodaj_sale();
        }
    }


    public function lista_sal()
    {
        $connect = $this->connect();
        $sale = [];

        $query = ("SELECT * FROM sale");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $sale[$row['id']] = array(
                $row['id'] => array(
                    'ilosc_miejsc' => $row['ilosc_miejsc'],
                ),
            );
        }

        return $sale;
    }


    public function dodaj_sale()
    {
        $connect = $this->connect();
        $query = ("INSERT INTO sale (ilosc_miejsc) VALUES ('$this->ilosc_miejsc')");
        pg_query($connect, $query);
        pg_close($connect);

        return header("Location: rooms.php");
    }



//    public function dane_sali($id)
//    {
//        $connect = $this->connect();
//        $sala = [];
//
//        $query = ("SELECT * FROM sale WHERE id = $id");
//        $wynik = pg_query($connect, $query);
//
//        while ($row = pg_fetch_assoc($wynik)) {
//
//            $sala = array(
//
//                'ilosc_miejsc' => $row['ilosc_miejsc'],
//            );
//        }
//
//        return $sala;
//    }
//
//
//    public function zapisz_zmiany()
//    {
//        $connect = $this->connect();
//
//
//        $query = ("UPDATE sale SET ilosc_miejsc = '$this->ilosc_miejsc' WHERE id = $this->id");
//        pg_query($connect, $query);
//
//        pg_close($connect);
//
//
//
//        return header("Refresh:0");
//    }
}



//============ seanse ========

class seans extends db
{
    public $id_sali;
    public $data;
    public $od;

    public $edit;
    public $id;
    public $error;
    public $id_filmu;
    public $id_seansu;



    function __construct($request = null, $id_filmu = null, $id_seansu = null)
    {
        $this->data = $request['data'];
        $this->id_sali = $request['id_sali'];
        $this->od = $request['od'];

        $this->id_filmu = $id_filmu;
        $this->id_seansu = $id_seansu;
        $this->edit = $request['edit'];
        $this->error['validate'] = 0;
    }



    public function walidacja()
    {
        $required = ['id_sali', 'data', 'od'];

        foreach($required as $value)
        {
            $this->error[$value] = '';
        }


        foreach($required as $req)
        {
            if(isset($this->$req) && $this->$req == '')
            {
                $this->error[$req] = 'To pole jest wymagane';
                $this->error['validate'] = 1;
            }

            if(!isset($this->$req))
            {
                $this->error['validate'] = 1;
            }
        }



        if($this->error['validate'] == 1)
        {
            return $this->error;
        }
//        elseif(isset($this->edit) && $this->edit == 1)
//        {
////            return $this->zapisz_zmiany();
//        }
        else
        {
            return $this->dodaj_seans();
        }
    }


    public function lista_sal()
    {
        $connect = $this->connect();
        $sale = [];

        $query = ("SELECT * FROM sale");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {

            $sale[$row['id']] = array(
                $row['id'] => array(
                    'ilosc_miejsc' => $row['ilosc_miejsc'],
                ),
            );
        }

        return $sale;
    }



    public function lista_seansow()
    {
        $connect = $this->connect();
        $seanse = [];

        $query = ("SELECT  seanse.id, id_sali, data, tytul, od FROM seanse JOIN filmy ON seanse.id_filmu = filmy.id;");
        $wynik = pg_query($connect, $query);

        while ($row = pg_fetch_assoc($wynik)) {





            $seanse[$row['id']] = array(
                $row['id'] => array(
                    'id_sali' => $row['id_sali'],
                    'data' => $row['data'],
                    'od' => $row['od'],
                    'tytul' => $row['tytul'],
                ),
            );
        }



        return $seanse;
    }



    public function dodaj_seans()
    {
        $connect = $this->connect();
        $query = ("INSERT INTO seanse (id_filmu, id_sali, data, od) VALUES ('$this->id_filmu', '$this->id_sali', '$this->data', '$this->od')");
        pg_query($connect, $query);
        pg_close($connect);

        return header("Location: dashboard.php");
    }




    public function usun_seans($id)
    {
        $connect = $this->connect();

        $query = ("DELETE FROM seanse WHERE id = '$id'");
        pg_query($connect, $query);
        pg_close($connect);


        return header("Refresh:0");
    }
}