<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["id"]) || !isset($_GET["personel_id"])) {
    header("Location: employees.php");
    exit;
}

$id = (int)$_GET["id"];
$personel_id = (int)$_GET["personel_id"];

/* BELGEYİ BUL */

$sorgu = $pdo->prepare("
SELECT *
FROM documents
WHERE id=?
");

$sorgu->execute([$id]);

$belge = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$belge){
    die("Belge bulunamadı.");
}

/* DOSYAYI SİL */

if(!empty($belge["dosya"])){

    $dosyaYolu = "../uploads/documents/".$belge["dosya"];

    if(file_exists($dosyaYolu)){
        unlink($dosyaYolu);
    }

}

/* VERİTABANINDAN SİL */

$sil = $pdo->prepare("
DELETE
FROM documents
WHERE id=?
");

$sil->execute([$id]);

header("Location: employee-detail.php?id=".$personel_id);
exit;
?>