<?php
session_start();
if(!isset($_SESSION['admin']) && $_SESSION['admin'] != true)//test či je použivatel admin
{
  //ak nie tak napise chybu
    $title="Chyba!";
    include "html_hlavicka.php";
    include "body_start.php";
    echo "Nedostatočné práva!";
    include "body_end.php";
    include "html_pata.php";
}

$title = "TS config";
include "html_hlavicka.php";

require "db_pripojenie.php";
echo '<div class="container">';
echo "<br>";


if(isset($_POST['submit'])){
    $counter_chid = $_POST['counter_chid'];
    $online_max_chid = $_POST['online_max_chid'];
    $prefix = $_POST['prefix'];
    $hour_online_bonus = $_POST['hour_online_bonus'];

    if (!mysqli_query($db_spojenie, "UPDATE ts_bots_config SET num_value='$counter_chid' WHERE name='counter_chid'"))
        die ('Chyba pri zmene configu v DB ' . mysqli_error($db_spojenie));
    if (!mysqli_query($db_spojenie, "UPDATE ts_bots_config SET  num_value='$online_max_chid' WHERE name='online_max_chid'"))
        die ('Chyba pri zmene configu v DB ' . mysqli_error($db_spojenie));
    if (!mysqli_query($db_spojenie, "UPDATE ts_bots_config SET char_value='$prefix' WHERE name='prefix'"))
        die ('Chyba pri zmene configu v DB ' . mysqli_error($db_spojenie));
    if (!mysqli_query($db_spojenie, "UPDATE ts_bots_config SET num_value='$hour_online_bonus' WHERE name='hour_online_bonus'"))
        die ('Chyba pri zmene configu v DB ' . mysqli_error($db_spojenie));

    echo '<br>';
    echo '<div class="alert alert-success">';
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
    echo '<strong>Success!</strong> Config bol úspešne uložený.';
    echo '</div>';
}

echo "<br>";
$settings = mysqli_query($db_spojenie, 'SELECT * FROM ts_bots_config');

if (!$settings)
  die ('Chyba pri načítavaní configu z DB ' . mysqli_error($db_spojenie));
?>
<table class="table table-bordered">
<thead>
  <tr>
    <th>ID</th>
    <th>Setting name</th>
    <th>Value</th>
    <th>Description</th>
  </tr>
</thead>
  <form action="ts_config.php" method="post">
<?php
while($setting = mysqli_fetch_array($settings)) {
?>
    <tbody>
      <tr>
        <td><?php echo $setting['setting_id']; ?></td>
        <td><?php echo $setting['name']; ?></td>
        <td><?php
          if($setting['num_value'] != NULL)
            $setting_value = $setting['num_value'];
          else
            $setting_value = $setting['char_value'];
            echo '<input type="text" name="'. $setting['name'] .'" value="'. $setting_value .'">';
         ?></td>
        <td><?php echo $setting['description']; ?></td>
      </tr>
    <?php
}


?>
</tbody>
</table>

<input type="submit" name="submit" value="Potvrdiť zmeny">
</form>

<?php

echo '</div>';

if ($db_spojenie) mysqli_close($db_spojenie);

include "html_pata.php";
?>
