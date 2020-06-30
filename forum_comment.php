<?php
session_start();

if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}

if(isset($_POST['post_id'])){
    $_SESSION['post_id'] = $_POST['post_id']; 
}
    $id_otazky = $_SESSION['post_id'];
    require "db_pripojenie.php";

    $info_otazky = mysqli_query($db_spojenie, "SELECT * FROM forum_posts WHERE post_id='$id_otazky'");
    $riadok_info = mysqli_fetch_array($info_otazky);
    $otazka = $riadok_info['post'];
   

$title="$otazka";
include "html_hlavicka.php";
include "body_start.php";

echo "<h3>$otazka</h3>";
echo "<br>";

if(isset($_POST['komentovat'])){
    $komentar = $_POST['komentar'];
    $id = $_SESSION['user_id'];
    $db_uloz_komentar = mysqli_query($db_spojenie,"INSERT INTO forum_comments (post_id_c, user_id, comment, date) VALUES ('$id_otazky', '$id', '$komentar', NOW())");

    if (!$db_uloz_komentar) 
        die ('Chyba zaslania príkazu SQL, pri odoslani komentaru do tabuľky.'  . mysqli_error($db_spojenie));
    else{
    header('location: forum_comment.php');
    //echo '<script> location.replace("komentovanie.php"); </script>';
    }
}

// vypis kometarov
$komentare = mysqli_query($db_spojenie, "SELECT user_id, comment, date FROM forum_comments WHERE post_id_c='$id_otazky' ORDER BY date DESC");
if(mysqli_num_rows($komentare) == 0)  
    echo "<h5>Zatiaľ žiadne komentáre k tejto otázke.</h5>";
else{
    while($jeden_komentar = mysqli_fetch_array($komentare)){
        $id_uzivatela_k = $jeden_komentar['user_id'];
        $nick_sql = mysqli_query($db_spojenie, "SELECT nick FROM users WHERE user_id='$id_uzivatela_k'");
        $uzivatel = mysqli_fetch_array($nick_sql);
        $nick_uzivatela_k = $uzivatel['nick'];
    
        include "forum_comment_temp.php";
        }
echo "<br><br><br>";
}

?>
<form action="" method="post" ng-app="myNoteApp" ng-controller="myNoteCtrl">
<textarea ng-model="message" cols="100" rows="5" name="komentar" maxlength="300"></textarea>

<p>
<input type="submit" value="Odoslať komentár" name="komentovat">
<button ng-click="clear()">Zmazať</button>
</p>

<p>Zostavajuci počet znakov: <span ng-bind="left()"></span></p>
</form>

<script>
var app = angular.module("NoteApp", []); 
app.controller("NoteCtrl", function($scope) {
    $scope.message = "";
    $scope.left = function() {
        return 300 - $scope.message.length;
    };
    $scope.clear = function() {
        $scope.message = "";
    };
});
</script>

<?php
if ($db_spojenie) mysqli_close($db_spojenie);

include "body_end.php";
include "html_pata.php";
?>
