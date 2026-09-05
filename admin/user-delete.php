<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

adminKontrol();


if(isset($_GET["id"])){

    $id = $_GET["id"];


    // Kendini silmesini engelle

    if($id == $_SESSION["user_id"]){

        die("Kendi hesabınızı silemezsiniz.");

    }



    $sil = $pdo->prepare("
    DELETE FROM users
    WHERE id=?
    ");


    $sil->execute([$id]);

}


header("Location: users.php");
exit;