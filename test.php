<?php
session_start();
if(!isset($_SESSION['signed_in'])) include "chyba_prihlasenia.php";

$title = "TS uživatelia";
include "html_hlavicka.php";

require "db_pripojenie.php";

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes');
}

echo '<div class="container">';
echo "<br>";

echo "<br>";
$ts_users = mysqli_query($db_spojenie, 'SELECT * FROM ts_users ORDER BY time_spent DESC');

if (!$ts_users)
die ('Chyba zaslania príkazu SQL'  . mysqli_error($db_spojenie));
?>
<table class="table table-bordered">
<thead>
  <tr>
    <th>UID uzivatela</th>
    <th>Nick</th>
    <th>Level</th>
    <th>Money</th>
    <th>Experience</th>
    <th>Time spent</th>
    <th>Last check (daily reward)</th>
  </tr>
</thead>
<?php
while($user = mysqli_fetch_array($ts_users)) {
?>
    <tbody>
      <tr>
        <td><?php echo $user['user_id']; ?></td>
        <td><?php echo $user['user_nickname']; ?></td>
        <td><?php echo $user['level']; ?></td>
        <td><?php echo $user['money']; ?></td>
        <td><?php echo $user['exp']; ?></td>
        <td><?php echo secondsToTime($user['time_spent']*60); ?></td>
        <td><?php echo $user['last_check']; ?></td>
      </tr>
    <?php
}
echo "</tbody>";
echo "</table>";

echo '</div>';

if ($db_spojenie) mysqli_close($db_spojenie);


include "html_pata.php";
?>
