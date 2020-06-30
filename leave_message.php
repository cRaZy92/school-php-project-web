<?php
session_start();
if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}
    $title="Zanechanie odkazu";
    include "html_hlavicka.php";
    include "body_start.php";

if(isset($_POST['ok'])){
    require "db_pripojenie.php";

        $sprava = $_POST['sprava'];
        $id = $_SESSION['user_id'];

$db_zanechaj_odkaz = mysqli_query($db_spojenie,"INSERT INTO private_questions (user_id, question, date) VALUES ('$id', '$sprava', NOW())");

if (!$db_zanechaj_odkaz) {
    die ('Chyba zaslania príkazu SQL, pri odoslani zápisu do tabuľky.'  . mysqli_error($db_spojenie));
}
$success= 1;


if($db_spojenie) mysqli_close($db_spojenie);

}
?>
<form action="" method="post">
<div class="text-center">
    <?php if(isset($success))
        echo '<div class="alert alert-success">
        <strong>Úspešne odoslané!</strong> Ak ide o problém, pokúsim sa to čo najskôr vyriešiť.</div>';
        ?>
		<h2>Zanechaj odkaz</h2>
        <p>Našiel si nejakú chybu alebo máš nápad na zlepšenie? Neváhaj a napíš mi. (maximálne 200 znakov)</p>
		Správu zanechaj tu: <br>
		<textarea rows="5" cols="100" name="sprava" max="200"></textarea> <br><br>
        
		<input type="submit" class="btn btn-lg btn-warning" value="Odoslať správu" name="ok">
        </div>
	</form>
<?php
include "body_end.php";
include "html_pata.php";
?>