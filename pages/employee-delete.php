<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!adminMi()){
    die("Bu sayfaya erişim yetkiniz yok.");
}
$id = (int)$_GET["id"];

/* Personeli getir */

$sorgu = $pdo->prepare("SELECT fotograf FROM employees WHERE id=?");
$sorgu->execute([$id]);

$employee = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$employee){

    header("Location: employees.php");
    exit;

}

/* Fotoğrafı sil */

if(!empty($employee["fotograf"])){

    $dosya="../uploads/".$employee["fotograf"];

    if(file_exists($dosya)){

        unlink($dosya);

    }

}

/* Sertifikaları sil */

$pdo->prepare("DELETE FROM employee_certificates WHERE personel_id=?")
    ->execute([$id]);

/* Belgeleri sil */

$pdo->prepare("DELETE FROM documents WHERE personel_id=?")
    ->execute([$id]);

/* Personeli sil */

$pdo->prepare("DELETE FROM employees WHERE id=?")
    ->execute([$id]);

header("Location: employees.php");
exit;
?>
