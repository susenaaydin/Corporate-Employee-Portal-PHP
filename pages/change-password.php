<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if($_SERVER["REQUEST_METHOD"]!="POST"){

    header("Location: profile.php");
    exit;

}

$eskiSifre = $_POST["old_password"];
$yeniSifre = $_POST["new_password"];
$tekrar    = $_POST["new_password2"];


/* Kullanıcıyı getir */

$sorgu = $pdo->prepare("

SELECT password

FROM users

WHERE id=?

");

$sorgu->execute([
    $_SESSION["user_id"]
]);

$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$kullanici){

    header("Location: profile.php");
    exit;

}


/* Eski şifre doğru mu */

if(!password_verify($eskiSifre,$kullanici["password"])){

    $_SESSION["password_error"]="Mevcut şifre yanlış.";

    header("Location: profile.php");
    exit;

}


/* Yeni şifreler aynı mı */

if($yeniSifre!=$tekrar){

    $_SESSION["password_error"]="Yeni şifreler eşleşmiyor.";

    header("Location: profile.php");
    exit;

}


/* En az 6 karakter */

if(strlen($yeniSifre)<6){

    $_SESSION["password_error"]="Şifre en az 6 karakter olmalıdır.";

    header("Location: profile.php");
    exit;

}


/* Şifreyi güncelle */

$hash = password_hash($yeniSifre,PASSWORD_DEFAULT);

$guncelle = $pdo->prepare("

UPDATE users

SET password=?

WHERE id=?

");

$guncelle->execute([

    $hash,

    $_SESSION["user_id"]

]);


$_SESSION["password_success"]="Şifreniz başarıyla değiştirildi.";

header("Location: profile.php");
exit;

?>