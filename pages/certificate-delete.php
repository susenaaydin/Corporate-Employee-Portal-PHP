<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: certificates.php");
    exit;
}

$id = (int)$_GET["id"];

/* SERTİFİKA KULLANILIYOR MU? */

$kontrol = $pdo->prepare("
SELECT COUNT(*)
FROM employee_certificates
WHERE sertifika_id=?
");

$kontrol->execute([$id]);

$kullanimSayisi = $kontrol->fetchColumn();

if($kullanimSayisi > 0){

    echo "
    <script>
        alert('Bu sertifika personele atanmış olduğu için silinemez.');
        window.location='certificates.php';
    </script>
    ";

    exit;
}

/* SİL */

$sil = $pdo->prepare("
DELETE
FROM certificates
WHERE id=?
");

$sil->execute([$id]);

header("Location: certificates.php");
exit;
?>