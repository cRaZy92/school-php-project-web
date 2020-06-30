<?php
session_start();
$title = "Domov";
include "html_hlavicka.php";

?>
    <main role="main" class="container">

      <div style="text-align:center">
        <h1>Vitajte na stránke</h1>
        <p class="lead"><small>Online since 5. 2. 2016</small></p>
        <?php
        if(!isset($_SESSION['signed_in'])){
        ?>
            <p class="lead">Teraz sa môžeš prihásiť alebo registrovať.</p>
        <?php
        }
        else{
            ?>

            <p class="lead">Viem že táto stránka nie je dokonalá, preto je dôležité aby ste mi napísali každú chybu ktorú nájdete. Zanechať ju môžte tu:</p>
            <button type="button" class="btn btn-warning" name="chyba" onclick="location.href='leave_message.php';" > <i class="fas fa-bug"></i>  Nahlásiť chybu</button>

            <br><br>

            <div id="ts3viewer_1114755" style=""> </div>

            <script src="https://static.tsviewer.com/short_expire/js/ts3viewer_loader.js"></script>
            <script>
            var ts3v_url_1 = "https://www.tsviewer.com/ts3viewer.php?ID=1114755&text=474747&text_size=13&text_family=1&text_s_color=000000&text_s_weight=normal&text_s_style=normal&text_s_variant=normal&text_s_decoration=none&text_i_color=&text_i_weight=normal&text_i_style=normal&text_i_variant=normal&text_i_decoration=none&text_c_color=&text_c_weight=normal&text_c_style=normal&text_c_variant=normal&text_c_decoration=none&text_u_color=000000&text_u_weight=normal&text_u_style=normal&text_u_variant=normal&text_u_decoration=none&text_s_color_h=&text_s_weight_h=bold&text_s_style_h=normal&text_s_variant_h=normal&text_s_decoration_h=none&text_i_color_h=000000&text_i_weight_h=bold&text_i_style_h=normal&text_i_variant_h=normal&text_i_decoration_h=none&text_c_color_h=&text_c_weight_h=normal&text_c_style_h=normal&text_c_variant_h=normal&text_c_decoration_h=none&text_u_color_h=&text_u_weight_h=bold&text_u_style_h=normal&text_u_variant_h=normal&text_u_decoration_h=none&iconset=default_colored_2014";
            ts3v_display.init(ts3v_url_1, 1114755, 100);
            </script>
            <?php
        }
        ?>
        <br>        
      </div>

    </main><!-- /.container -->
<?php
    include "html_pata.php";
?>
