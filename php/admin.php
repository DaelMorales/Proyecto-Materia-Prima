<?php
session_start();

if ($_SESSION["acceso"] != "1") {
    header("location: login.php");
    exit();
}

header("location: ./admin.html");
exit();
?>
