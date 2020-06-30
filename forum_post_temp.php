<?php
$date = $jedna_otazka['date'];
$date_u = DateTime::createFromFormat('Y-m-d H:i:s', $date);
$date_u = $date_u->format('d. m. Y H:i');

/*
<div class="d-flex align-items-start flex-column" style="height: 200px;">
  <div class="mb-auto p-2">Flex item</div>
  <div class="p-2">Flex item</div>
  <div class="p-2">Flex item</div>
</div>
*/
?>
<form action="forum_comment.php" method="post"> <!-- začiatok formu na odoslanie ID otazky -->  
<div class="box">
  <div>   <?php echo $jedna_otazka['post']; ?></div>
  <div class="push"><button type="submit" class="float-right btn-success" name="post_id" value="<?php echo $jedna_otazka['post_id']; ?>">Zobraziť komentáre</button></div>
  <div>
    Napísal <a href='profile_other.php?id_uzivatel=<?php echo $user_id_o; ?>'><?php echo $nick_uzivatela_o; ?></a> <span style="font-size:12px">dňa <?php echo $date_u; ?></span>
  </div>
</div>
</form>
<hr>
