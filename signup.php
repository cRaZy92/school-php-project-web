<?php
session_start();

if(isset($_SESSION['signed_in'])){
    $title="Chyba!";
    include "html_hlavicka.php";
    include "body_start.php";
    echo 'Už si prihláseny! <a href="index.php">Klikni sem pre návrat.</a>';
    include "body_end.php";
    die;
}
$title="Registrácia";
include "html_hlavicka.php";
echo '<body class="text-center">';

if (isset($_POST['registruj'])){
    require "db_pripojenie.php";

    $nick = htmlspecialchars($_POST['nick']);
    $heslo = htmlspecialchars($_POST['heslo']);
    $heslo_z = htmlspecialchars($_POST['heslo_z']);
    $email = htmlspecialchars($_POST['email']);

        //zkontroluje ci sa hesla zhoduju
    if($heslo != $heslo_z) {
        echo "Heslá sa nezhoduju";
        $reg_error = 1;
    }
    else
        $reg_error = 0;

        //zkontroluje validnost emailu
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Neplatný formát emailu.";
        $reg_error = 1;
    }

    $vysledok_nick = mysqli_query($db_spojenie, "SELECT nick FROM users WHERE nick ='$nick'");

    if(!$vysledok_nick)
        echo 'Skus znova. Chyba:' . mysql_error();
    else if(mysqli_num_rows($vysledok_nick) != 0)    // kontrola nicku - nick je volny
            echo "Nick sa už používa! Zvoľ si prosím iný.";
        else
            $reg_error = 0;

    if($reg_error == 0) {
        $hashed_password = password_hash($heslo, PASSWORD_DEFAULT); //hash hesla - zabezpečenie
        // vloženie udajov o pouzivatelovi do databazy
    $registruj = mysqli_query($db_spojenie,
    "INSERT INTO
        users
        (nick, password, name,  surname, ts_nick, gender, email, reg_date, last_login)
    VALUES
        ('$nick', '$hashed_password', 'n', 'n', 'n', 'n', '$email', NOW(), NOW())");
    }else
        echo "Chyba!";

if (!$registruj)   //kontrola pribehu zapisu do databazy
    die ('Chyba zaslania príkazu do databazy pri registracii'  . mysqli_error($db_spojenie));
else {  //uspešná registrácia
    header('location: login.php');   //prepoji na login.php
   $_SESSION['n_user'] = true;
}   //uspesná registrácia
if ($db_spojenie) mysqli_close($db_spojenie);   //odpojenie z databazy
}   //isset [registruj]

            /* Form register */
?>
<form class="form-signin" action="" method="post">
    <img class="mb-4" src="img/a_logo_inverted_edit.jpg" width="128" height="128">
      <h1 class="h3 mb-3 font-weight-normal">Registruj sa</h1>
        <input type="text" name="nick" class="form-control" placeholder="Nick" minlength="3" maxlength="10" required autofocus>
        <input type="email" name="email" class="form-control" placeholder="Emailová adresa" minlength="5" maxlength="40" required>
        <div class="input-group">
            <input type="password" name="heslo" id="inputPassword" class="form-control" placeholder="Heslo" minlength="4" maxlength="16" required>
            <input type="password" name="heslo_z" id="inputPassword" class="form-control" placeholder="Heslo znova" minlength="4" maxlength="16" required>
        </div>
      <button class="btn btn-lg btn-success btn-block" type="submit" name="registruj">Registrovať</button>
</form>
<?php

include "body_end.php";
include "html_pata.php";
?>
