<?php
session_start();
if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}

if(isset($_GET['id_uzivatel'])){
    $id_other = $_GET['id_uzivatel'];
}
else
die("Chyba!");

    require "db_pripojenie.php";

    $vysledok = mysqli_query($db_spojenie, "SELECT nick,ts_nick,name,surname,gender,reg_date FROM users WHERE user_id='$id_other'");
    $riadok_uzivatel = mysqli_fetch_array($vysledok);

$title="Profil použivateľa ". $riadok_uzivatel['nick'];
include "html_hlavicka.php";

    $id = $_SESSION['user_id'];        
        
        $date = $riadok_uzivatel['reg_date'];
        $date_u = DateTime::createFromFormat('Y-m-d', $date);
        $date_u = $date_u->format('d. m. Y');

        $requests = mysqli_query($db_spojenie, "SELECT id FROM friend_requests WHERE id_sender='$id' AND id_recipient='$id_other'");
        if(mysqli_num_rows($requests) != 0)
            $already_requested = true;

        if(isset($_POST['pridat'])){
                $registruj_login = mysqli_query($db_spojenie, 
                "INSERT INTO
                    friend_requests
                    (id_sender,id_recipient)
                VALUES
                    ('$id','$id_other')");
                $add_success=true;
                $already_requested = true;
        }
    //html profil 
?>
    <div class="container bootstrap snippet">
<?php
if(isset($add_success)){
    //hlasenie o uspesnom pridaní priatela
    echo '<div class="alert alert-success">
      <strong>Žiadosť odoslaná!</strong> Dúfaj že ju príjme.</div>';
}
?>
    <div class="row">
  		<div class="col-sm-10"><h1><?php echo $riadok_uzivatel['nick']; ?></h1></div>
    </div>
    <div class="row">
  		<div class="col-sm-3"><!--left col-->
              

      <div class="text-center">
        <?php
        if($riadok_uzivatel['gender'] == "f")
        echo '<img src="img/img_avatar_f.png" class="avatar img-circle img-thumbnail" alt="avatar">';
        else{
            if($riadok_uzivatel['gender'] == "m")
                echo '<img src="img/img_avatar_m.png" class="avatar img-circle img-thumbnail" alt="avatar">';
            else
                echo '<img src="img/img_avatar_i.png" class="avatar img-circle img-thumbnail" alt="avatar">';
        }
        ?>
        
      </div></hr><br>

        <!--  Webstránka použivateľa       
          <div class="panel panel-default">
            <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><a href="http://bootnipets.com">bootnipets.com</a></div>
          </div>
        -->

        <?php
     
    $vsetky_otazky = mysqli_query($db_spojenie, 
    "SELECT 
        post_id
    FROM
        forum_posts
    WHERE
        user_id ='$id_other'");

$p_otazok = mysqli_num_rows($vsetky_otazky);

$sql_komentare = "SELECT 
            post_id_c
        FROM
            forum_comments
        WHERE
            user_id ='$id_other'";
     
$vsetky_komentare = mysqli_query($db_spojenie, $sql_komentare);
$p_komentarov = mysqli_num_rows($vsetky_komentare);

        ?>

          
          <ul class="list-group">
            <li class="list-group-item text-muted">Aktivita <i class="fa fa-dashboard fa-1x"></i></li>
            <li class="list-group-item text-center"><span class="pull-left"><strong>Otázky:</strong></span> <?php echo $p_otazok; ?></li>
            <li class="list-group-item text-center"><span class="pull-left"><strong>Komentáre:</strong></span> <?php echo $p_komentarov; ?></li>
          </ul> 
          <br>
          <h6 class="text-center">Dátum registrácie: <?php echo $date_u; ?></h6>
          <br>
          <div class="text-center">
          <?php
        if($id != $id_other){
if(isset($already_requested)){
    echo '<button type="submit" class="btn btn-success" disabled>Čaká sa na odpoveď</button>';
}
else{
?>
    <form action="profile_other.php?id_uzivatel=<?php echo $id_other ?>" method="POST">
        <button type="submit" name="pridat" value="$id_other" class="btn btn-success">Pridať priateľa</button>
    </form>
<?php
    }
}
?>
          
        </div>
          
        </div><!--/col-3-->
    	<div class="col-sm-9">
            

              
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Meno a priezvisko</h4></label>
                              <?php 
                            if($riadok_uzivatel['name'] != "n")
                                $meno = $riadok_uzivatel['name'];
                            else    
                                $meno = "Neuvedené"; 
                            if($riadok_uzivatel['surname'] != "n")
                                $priezvisko = $riadok_uzivatel['surname']; 
                            else    
                                $priezvisko = " ";

                            if($riadok_uzivatel['ts_nick'] != "n")
                                $ts_nick = $riadok_uzivatel['ts_nick'];
                            else
                                $ts_nick = "Neuvedené";
                                    ?>

                              <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $meno . " " . $priezvisko; ?></p>
                              <hr>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                            <label for="last_name"><h4>TeamSpeak nick</h4></label>
                            <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $ts_nick; ?></p>
                              <hr>
                          </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Pohlavie</h4></label>
                             <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php 
                             switch($riadok_uzivatel['gender']){
                                case "n":
                                    echo "Neuvedené";
                                break;
                
                                case "m":
                                    echo "Muž";
                                break;
                
                                case "f":
                                    echo "Žena";
                                break;
                
                                case "o":
                                    echo "Iné";
                                break;
                
                                default:
                                    echo "Neuvedené";
                            }
                             ?></p>
                          </div>
                        
                      </div>
                     

              <hr>
              
            </div><!--/tab-pane-->               
               
                  
             
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
</div>

<?php
    if ($db_spojenie) mysqli_close($db_spojenie);

include "html_pata.php";
?>