<?php  include_once('includes/config.php');


if($_SESSION['rango'] =="adm" or $_SESSION['rango'] =="distribuidor" or $_SESSION['rango'] =="usuario"):
session_destroy();

header('location: '.panel.'login/'); endif;
?>