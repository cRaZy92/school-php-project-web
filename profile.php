<?php
session_start();

if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}

    $title="Profil";
    include "html_hlavicka.php";
    require "db_pripojenie.php";
        $id = $_SESSION['user_id'];
        $date = $_SESSION['reg_date'];
        $date_u = DateTime::createFromFormat('Y-m-d', $date);
        $date_u = $date_u->format('d. m. Y');

        /*profil html*/
        ?>
    <div class="container bootstrap snippet">
        <div class="row">
              <div class="col-sm-10"><h1><?php echo $_SESSION['nick']; ?></h1></div>
        </div>
        <div class="row">
              <div class="col-sm-3"><!--left col-->


          <div class="text-center">
              <?php
            $nick = $_SESSION['nick'];
            $img_path = 'images/'.$nick.'.';

            if(file_exists($img_path.'png')){
                $img_path = 'images/'.$nick.'.'.'png';
                ?>
                <img src=<?php echo "$img_path"; ?> class="avatar img-circle img-thumbnail" alt="avatar">
                <?php
                $custom_img = 1;
            }

            if (file_exists($img_path.'jpg')){
                $img_path = 'images/'.$nick.'.'.'jpg';
                ?>
                <img src=<?php echo "$img_path"; ?> class="avatar img-circle img-thumbnail" alt="avatar">
                <?php
                $custom_img = 1;
            }

            if (file_exists($img_path.'jpeg')){
                $img_path = 'images/'.$nick.'.'.'jpeg';
                ?>
                <img src=<?php echo "$img_path"; ?> class="avatar img-circle img-thumbnail" alt="avatar">
                <?php
                $custom_img = 1;
            }

            if(!isset($custom_img)){
              if($_SESSION['pohlavie'] == "žena")
                echo '<img src="img/img_avatar_f.png" class="avatar img-circle img-thumbnail" alt="avatar">';
                else{
                    if($_SESSION['pohlavie'] == "muž")
                        echo '<img src="img/img_avatar_m.png" class="avatar img-circle img-thumbnail" alt="avatar">';
                    else
                        echo '<img src="img/img_avatar_i.png" class="avatar img-circle img-thumbnail" alt="avatar">';
                }
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
        user_id ='$id'");
    $p_otazok = mysqli_num_rows($vsetky_otazky);

    $vsetky_komentare = mysqli_query($db_spojenie,
    "SELECT
        post_id_c
    FROM
        forum_comments
    WHERE
        user_id ='$id'");
    $p_komentarov = mysqli_num_rows($vsetky_komentare);


    $requests = mysqli_query($db_spojenie,
    "SELECT
        id_sender
    FROM
        friend_requests
    WHERE
        id_recipient ='$id'");

            ?>


              <ul class="list-group">
                <li class="list-group-item text-muted ">Aktivita <i class="fa fa-dashboard fa-1x"></i></li>
                <li class="list-group-item text-center"><span class="pull-left"><strong>Otázky:</strong></span> <?php echo $p_otazok; ?></li>
                <li class="list-group-item text-center"><span class="pull-left"><strong>Komentáre:</strong></span> <?php echo $p_komentarov; ?></li>
              </ul>
              <br>
              <h6 class="text-center">Dátum registrácie: <?php echo $date_u; ?></h6>
              <br>

            <div class="text-center">
                <button onclick="location.href = 'profile_edit.php';" class="btn btn-primary">Upraviť profil</button>
            </div>
              <?php
              if(mysqli_num_rows($requests) > 0){
              echo '<ul class="list-group">';
                echo '<li class="list-group-item text-muted text-center">Nové žiadosti o priateľtvo <i class="fa fa-dashboard fa-1x"></i></li>';

                while($jeden_request = mysqli_fetch_array($requests)){
                    $id_sender = $jeden_request['id_sender'];
                    $sender_info_sql = mysqli_query($db_spojenie,
                    "SELECT
                        nick
                    FROM
                        users
                    WHERE
                        user_id ='$id_sender'");
                    $sender_info = mysqli_fetch_array($sender_info_sql);
                   echo '<li class="list-group-item text-center"><span class="pull-left">Uživateľ <strong>'. $sender_info['nick'] .'</strong> ti posiela žiadosť! </span> </li>';

                }


              echo "</ul>";


            }
              ?>


            </div><!--/col-3-->
            <div class="col-sm-9">


              <div class="tab-content">
                <div class="tab-pane active" id="home">

                          <div class="form-group">

                              <div class="col-xs-6">
                                  <label for="first_name"><h4>Meno a priezvisko</h4></label>
                                  <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $_SESSION['name'] . " " . $_SESSION['surname']; ?></p>
                                  <hr>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-xs-6">
                                <label for="last_name"><h4>Email</h4></label>
                                <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $_SESSION['email']; ?></p>
                                  <hr>
                              </div>
                          </div>

                          <div class="form-group">
                              <div class="col-xs-6">
                                 <label for="mobile"><h4>Pohlavie</h4></label>
                                 <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $_SESSION['gender']; ?></p>
                                 <hr>
                              </div>

                          </div>

                          <div class="form-group">
                              <div class="col-xs-6">
                                 <label for="mobile"><h4>TeamSpeak nick</h4></label>
                                 <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> <?php echo $_SESSION['ts_nick']; ?></p>
                                 <hr>
                              </div>

                          </div>

                          <div>
                              <div class="col-xs-6">
                                 <label for="mobile"><h4>Friends</h4></label>
                                 <p style="font-size:20px"><span>&#8203;</span> <span>&#8203;</span> <span>&#8203;</span> something</p>
                                 <hr>
                              </div>

                          </div>


                </div><!--/tab-pane-->

              </div><!--/tab-content-->

            </div><!--/col-9-->
        </div><!--/row-->
    </div>

<?php
include "html_pata.php";
?>
