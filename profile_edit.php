<?php
session_start();
if(!isset($_SESSION['signed_in']))
{
    include "chyba_prihlasenia.php";
    die;
}
    $title="Úprava profilu";

    include "html_hlavicka.php";
    require "db_pripojenie.php";

    $id = $_SESSION['user_id'];
    $nick = $_SESSION['nick'];

    if(isset($_FILES['image'])){
        $errors= array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $tmp = explode('.', $file_name);
        $file_ext = end($tmp);

        $expensions= array("jpeg","jpg","png","gif"); //povolene formaty

        if(in_array($file_ext,$expensions)== false){
            $error_img_ext=1;
            $errors[]="Tento formát nie je podporovaný, prosím použite JPG, JPEG alebo PNG.";
        }

        if($file_size > 2097152) {  //max velkost obrazku
            $error_img_size=1;
            $errors[]='Súbor je príliš veľký! Maximálne 2MB.';
        }

        if(empty($errors)==true) {
           move_uploaded_file($file_tmp,"images/".$nick.".".$file_ext);  //miesto ulozenia obrazka
           header('Location: profile.php');
        }else{
           echo 'Chyba!';
           print_r($errors);
        }
     }

if (isset($_POST['ulozit_zmeny'])){
    $uzivatel = mysqli_query($db_spojenie,
    "SELECT
        password
    FROM
        users
    WHERE
        user_id ='$id'");

    if(!mysqli_num_rows($uzivatel) == 1){
        //echo 'Chyba, skús sa odhlásiť a prihlásiť.'; //ak sa nenasla ziadna zhoda v databaze
        die;
    }
        $password = $_POST['heslo_z'];
        $riadok_uzivatel = mysqli_fetch_array($uzivatel);
        $hashed_password = $riadok_uzivatel['password'];
        if(password_verify($password, $hashed_password) == true){

    $meno = $_POST['meno_n'];
    $priezvisko = $_POST['priezvisko_n'];
    $ts_nick = $_POST['ts_nick_n'];
    $pohlavie = $_POST['pohlavie_n'];

    if(!empty($_POST['meno_n'])){
        $uprav_meno = mysqli_query($db_spojenie,
    "UPDATE
        users
    SET
        name = '$meno'
    WHERE
        user_id = '$id'");
    }

    if(!empty($_POST['priezvisko_n'])){
        $uprav_priezvisko = mysqli_query($db_spojenie,
    "UPDATE
        users
    SET
        surname = '$priezvisko'
    WHERE
        user_id = '$id'");
    }

    if(!empty($_POST['ts_nick_n'])){
        $uprav_ts_nick = mysqli_query($db_spojenie,
    "UPDATE
        users
    SET
        ts_nick = '$ts_nick'
    WHERE
        user_id = '$id'");
    }

    if(!empty($_POST['pohlavie_n'])){
        $uprav_pohlavie = mysqli_query($db_spojenie,
    "UPDATE
        users
    SET
        gender = '$pohlavie'
    WHERE
        user_id = '$id'");
    }

    $sql_udaje = mysqli_query($db_spojenie, "SELECT name,surname,ts_nick,gender FROM users WHERE user_id='$id'");
    $osobne_udaje = mysqli_fetch_array($sql_udaje);

    //prevzatie udajov o pouzivatelovi z databazy a ulozenie do session
    if($osobne_udaje['name'] != "n")
        $_SESSION['name'] = $osobne_udaje['name'];

    if($osobne_udaje['surname'] != "n")
        $_SESSION['surname'] = $osobne_udaje['surname'];

    if($osobne_udaje['ts_nick'] != "n")
        $_SESSION['ts_nick'] = $osobne_udaje['ts_nick'];

        switch($osobne_udaje['gender']){
            case "n":
                $_SESSION['gender'] = "Neuvedené";
            break;

            case "m":
                $_SESSION['gender'] = "Muž";
            break;

            case "f":
                $_SESSION['gender'] = "Žena";
            break;

            case "o":
                $_SESSION['gender'] = "Iné";
            break;

            default:
                $_SESSION['gender'] = "Neuvedené";
        }

        echo '<script> location.replace("profile.php"); </script>';

}else
    echo "Nesprávne heslo!";
}


// profil edit form
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
            }else if (file_exists($img_path.'jpg')){
                $img_path = 'images/'.$nick.'.'.'jpg';
                ?>
                <img src=<?php echo "$img_path"; ?> class="avatar img-circle img-thumbnail" alt="avatar">
                <?php
                $custom_img = 1;
            }else if (file_exists($img_path.'jpeg')){
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
        <form action="" method="POST" enctype="multipart/form-data">
        <br>
        <input type="file" name="image" id="file" class="inputfile">

        <label for="file" class="btn btn-primary"> Nahrať obrázok </label>
        <input type="submit" class="btn btn-success" name="img" value="Odoslať">
        </form>
        <?php
        if(isset($error_img_ext)){
            echo "Tento formát nie je podporovaný, prosím použite JPG, JPEG alebo PNG.";
        }

        if(isset($error_img_size)){
            echo "Súbor je príliš veľký! Maximálne 2MB.";
        }

        ?>
      </div><br>

        </div><!--/col-3-->
    	<div class="col-sm-9">

        <form class="form" action="profile_edit.php" method="post">

          <div class="tab-content">
            <div class="tab-pane active" id="home">

                      <div class="form-group">

                          <div class="col-xs-6">
                              <label for="first_name"><h4>Meno</h4></label>
                              <input type="text" class="form-control" name="meno_n" placeholder="<?php echo $_SESSION['name']; ?>">
                          </div>
                      </div>
                      <div class="form-group">

                          <div class="col-xs-6">
                            <label for="last_name"><h4>Priezvisko</h4></label>
                              <input type="text" class="form-control" name="priezvisko_n" placeholder="<?php echo $_SESSION['surname']; ?>">
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="gender"><h4>Pohlavie</h4></label>
                            <?php
                            switch($_SESSION['gender']){
                                case "Neuvedené":
                                    echo '<p><select name="pohlavie_n" class="form-control">
                                        <option value="n">Neuvedené</option>
                                        <option value="m">Muž</option>
                                        <option value="f">Žena</option>
                                        <option value="o">Iné</option>
                                        </select></p>';
                                break;

                                case "Iné":
                                    echo '<p><select name="pohlavie_n" class="form-control">
                                        <option value="o">Iné</option>
                                        <option value="m">Muž</option>
                                        <option value="f">Žena</option>
                                        <option value="n">Neuvedené</option>
                                        </select></p>';
                                break;

                                case "Muž":
                                    echo '<p><select name="pohlavie_n" class="form-control">
                                        <option value="m">Muž</option>
                                        <option value="o">Iné</option>
                                        <option value="f">Žena</option>
                                        <option value="n">Neuvedené</option>
                                        </select></p>';
                                break;

                                case "Žena":
                                    echo '<p><select name="pohlavie_n" class="form-control">
                                        <option value="f">Žena</option>
                                        <option value="m">Muž</option>
                                        <option value="o">Iné</option>
                                        <option value="n">Neuvedené</option>
                                        </select></p>';
                                break;
                                }
                            ?>
                          </div>
                      </div>

                      <div class="form-group">

                          <div class="col-xs-6">
                              <label for="phone"><h4>TeamSpeak nick</h4></label>
                              <input type="text" class="form-control" name="ts_nick_n" placeholder="<?php echo $_SESSION['ts_nick']; ?>">
                          </div>
                      </div>



                      <div class="form-group">

                          <div class="col-xs-6">
                              <label for="password"><h4>Heslo pre potvrdenie zmien</h4></label>
                              <input type="password" class="form-control" name="heslo_z" placeholder="Zadaj heslo" required>
                          </div>
                      </div>

                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit" name="ulozit_zmeny">Uložiť</button>
                               	<button class="btn btn-lg btn-danger" type="reset">Obnoviť</button>
                            </div>
                      </div>
              	</form>

                  </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
</div>






<?php
include "html_pata.php";
?>
