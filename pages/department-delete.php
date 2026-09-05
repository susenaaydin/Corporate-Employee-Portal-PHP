<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: departments.php");
    exit;
}

$id = (int)$_GET["id"];

/* BU DEPARTMANDA PERSONEL VAR MI? */

$kontrol = $pdo->prepare("
SELECT COUNT(*)
FROM employees
WHERE departman_id=?
");

$kontrol->execute([$id]);

$personelSayisi = $kontrol->fetchColumn();

if($personelSayisi > 0){

    echo "
    <script>
        alert('Bu departmanda kayıtlı personeller bulunduğu için silinemez.');
        window.location='departments.php';
    </script>
    ";

    exit;
}

/* DEPARTMANI SİL */

$sil = $pdo->prepare("
DELETE
FROM departments
WHERE id=?
");

$sil->execute([$id]);

header("Location: departments.php");
exit;
?>