<div class="media">
    <div class="media-left">
    <a href='profil_other.php?id_uzivatel=<?php echo $id_uzivatela_k; ?>'><img src="img/img_avatar_m.png" class="media-object" style="width:60px"></a>
    </div>
  <div class="media-body">
    <h4 class="media-heading"><a href='profil_other.php?id_uzivatel=<?php echo $id_uzivatela_k; ?>'>
    <?php 
      $date = date_create($jeden_komentar['date']);
     $date_c = date_format($date, 'd. m. Y  H:i:s');
    echo $nick_uzivatela_k;
    echo "</a>";
    echo '<font style="font-size:11px"> dňa ';
    echo $date_c;
    echo "</font>";
    ?> 
    </h4>
    <p><?php echo $jeden_komentar['comment']; ?></p>
  </div>
</div>


<!--
<div class="box">
  <div><img src="img/img_avatar_m.png" alt="" class="mr-2 rounded" width="64" height="64"></div>
  <div>Lorem ipsum... asdasdasda das das dasjsdnfsdnfn sdn fnsd fnsdn fsidn fsdnfi sdf</div>
  <div class="push">Four</div>
</div>
<hr>
-->