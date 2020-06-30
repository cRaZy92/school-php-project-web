<?php
session_start();
ob_start();

if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}

if(!isset($p_otazok))
{
    $p_otazok = 0;
}

$title="Fórum";
include "html_hlavicka.php";

require "db_pripojenie.php";

if(isset($_POST['vloz_post'])){  //ak uzivatel chce vlozit novú otázku
        $otazka = $_POST['post'];
        $id = $_SESSION['user_id'];

$db_uloz_otazku = mysqli_query($db_spojenie,"INSERT INTO forum_posts (user_id, post, date) VALUES ('$id', '$otazka', NOW())");  //vlozenie otazky do databazy

if (!$db_uloz_otazku) {
    die ('Chyba zaslania príkazu SQL, pri odoslani otazky do tabuľky.'  . mysqli_error($db_spojenie));
}else
    header('location: forum.php');     //uspesne vlozenie do db
}

    //forum formular na vkladanie otazok
?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
          <h4 class="modal-title" id="myModalLabel">Vytvoriť otázku</h4>
        </div>
        <div class="modal-body" >
        <form action="forum.php" method="post" ng-app="myNoteApp" ng-controller="myNoteCtrl">
        <label for="otazka"><b>Napíš svoju otázku.</b></label><br>
        <textarea ng-model="message" rows="2" cols="50" name="post" maxlength="500"></textarea> <br>
        <p>Zostavajuci počet znakov: <span ng-bind="left()"></span></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn btn-success" name="vloz_post">Odoslať</button>
          </form>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Zrušiť</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  var app = angular.module("NoteApp", []);
  app.controller("NoteCtrl", function($scope) {
      $scope.message = "";
      $scope.left = function() {
          return 500 - $scope.message.length;
      };
      $scope.clear = function() {
          $scope.message = "";
      };
  });
  </script>

<body class="bg-light">

<main role="main" class="container">
  <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">

    <img class="mr-3" src="https://openclipart.org/download/185270/Light-Bulb-Icon.svg" alt="" width="48" height="48">
    <div class="lh-100">
      <h6 class="mb-0 text-white lh-100">Vitaj na našom fóre</h6>

      <small>Celkovo ##p_otazok##</small>

    </div>

  </div>
  <button type="button" id="alignright" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Položiť otázku
    </button><br>

  <div class="my-3 p-3 bg-white rounded box-shadow">
    <h6 class="border-bottom border-gray pb-2 mb-0">Všetky otázky</h6>

<?php
$otazky = mysqli_query($db_spojenie, "SELECT post_id, user_id, post, date FROM forum_posts ORDER BY date DESC"); //vybratie vsetkych otazok z db a zoradenie ich podla casu
if(mysqli_num_rows($otazky) == 0)
    echo "Zatiaľ žiadne otázky.";   //ak neexistuju ziadne otazky v db
else{
    echo "<br>";
while($jedna_otazka = mysqli_fetch_array($otazky)) //vypisuje vsetky otazky v db
{
    $user_id_o = $jedna_otazka['user_id'];
    $nick_sql = mysqli_query($db_spojenie, "SELECT nick FROM users WHERE user_id='$user_id_o'");
    $uzivatel = mysqli_fetch_array($nick_sql);
    $nick_uzivatela_o = $uzivatel['nick'];
    $p_otazok++;
    include "forum_post_temp.php";
}

}

switch($p_otazok){
    case 0:
        $otazka = "$p_otazok otázok.";
    break;

    case 1:
        $otazka = "$p_otazok otázka.";
    break;

    case 2:
    case 3:
    case 4:
        $otazka = "$p_otazok otázky.";
    break;

    default:
        $otazka = "$p_otazok otázok.";
}

echo str_replace("##p_otazok##", $otazka, ob_get_clean());
if($db_spojenie) mysqli_close($db_spojenie);
?>

</main>
<script>
var modal = document.getElementById('id01');

// Ak uživatel klikne mimo okna, zavrie sa
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php
include "html_pata.php";
?>
