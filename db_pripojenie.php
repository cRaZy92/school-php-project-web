<?php

$testing = true;

if(!$testing){
    $db_ip = '****';
    $db_login = '****';
    $db_pass = '****';
    $db_name = '****';
    $db_port = '****';
}else{
    $db_ip = '127.0.0.1';
    $db_login = 'adam';
    $db_pass = '****';
    $db_name = 'project_server';
    $db_port = '3306';
}

$db_spojenie = mysqli_connect($db_ip,$db_login, $db_pass, $db_name,$db_port);

if (!$db_spojenie) {
    echo 'Vzniknutá chyba: ' . mysqli_connect_error();
    die ('Pripojenie sa nepodarilo');
    }

mysqli_query($db_spojenie, "SET NAMES 'utf8'");
?>
