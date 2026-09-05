<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["id"])) {
    die("Personel bulunamadı.");
}

$id = (int)$_GET["id"];

/* PERSONEL */

$sql = $pdo->prepare("
SELECT
employees.*,
departments.department_name
FROM employees
LEFT JOIN departments
ON employees.departman_id = departments.id
WHERE employees.id = ?
");

$sql->execute([$id]);

$employee = $sql->fetch(PDO::FETCH_ASSOC);

if(!$employee){
    die("Personel bulunamadı.");
}

/* SERTİFİKALAR */

$sql = $pdo->prepare("
SELECT
employee_certificates.*,
certificates.sertifika_adi
FROM employee_certificates
LEFT JOIN certificates
ON employee_certificates.sertifika_id = certificates.id
WHERE employee_certificates.personel_id = ?
ORDER BY employee_certificates.alinma_tarihi DESC
");

$sql->execute([$employee["id"]]);

$sertifikaListesi = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Personel Detayı</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
background:#f4f6f9;
}

.card{
border:none;
border-radius:18px;
}

.profile{
width:220px;
height:220px;
object-fit:cover;
border-radius:50%;
border:5px solid #0d6efd;
}

.section-title{
background:#0d6efd;
color:white;
padding:12px;
border-radius:10px;
font-size:18px;
font-weight:bold;
margin-top:30px;
margin-bottom:15px;
}

.table th{
width:220px;
background:#f8f9fa;
}

.badge-status{
font-size:14px;
padding:8px 12px;
}

</style>

</head>

<body>

<div class="container py-4">

<a href="employees.php"
class="btn btn-secondary mb-4">

<i class="bi bi-arrow-left"></i>

Personel Listesi

</a>

<div class="card shadow">

<div class="card-body">

<div class="row">

<div class="col-lg-4 text-center">

<?php if(empty($employee["fotograf"])): ?>

<img
src="https://via.placeholder.com/220"
class="profile">

<?php else: ?>

<img
src="../uploads/<?= htmlspecialchars($employee["fotograf"]) ?>"
class="profile">

<?php endif; ?>

<h3 class="mt-3">

<?= htmlspecialchars($employee["ad"]) ?>

<?= htmlspecialchars($employee["soyad"]) ?>

</h3>

<p class="text-muted">

<?= htmlspecialchars($employee["pozisyon"]) ?>

</p>

<span class="badge bg-primary fs-6">

<?= htmlspecialchars($employee["department_name"]) ?>

</span>

<div class="d-grid gap-2 mt-4">

<a
href="employee-edit.php?id=<?= $employee["id"] ?>"
class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Personeli Düzenle

</a>

<a
href="employee-certificate-add.php?personel_id=<?= $employee["id"] ?>"
class="btn btn-success">

<i class="bi bi-patch-check-fill"></i>

Sertifika Ekle

</a>

<a
href="employees.php"
class="btn btn-outline-secondary">

Personel Listesi

</a>

</div>

</div>

<div class="col-lg-8">

<div class="section-title">

<i class="bi bi-person-fill"></i>

Kişisel Bilgiler

</div>

<table class="table table-bordered">

<tr>
<th>Personel No</th>
<td><?= htmlspecialchars($employee["personel_no"]) ?></td>
</tr>

<tr>
<th>Ad</th>
<td><?= htmlspecialchars($employee["ad"]) ?></td>
</tr>

<tr>
<th>Soyad</th>
<td><?= htmlspecialchars($employee["soyad"]) ?></td>
</tr>

<tr>
<th>Cinsiyet</th>
<td><?= htmlspecialchars($employee["cinsiyet"]) ?></td>
</tr>

<tr>
<th>Medeni Durum</th>
<td><?= htmlspecialchars($employee["medeni_durum"]) ?></td>
</tr>

<tr>
<th>Kan Grubu</th>
<td><?= htmlspecialchars($employee["kan_grubu"]) ?></td>
</tr>

<tr>
<th>Doğum Tarihi</th>
<td><?= date("d.m.Y", strtotime($employee["dogum_tarihi"])) ?></td>
</tr>

<tr>
<th>Telefon</th>
<td><?= htmlspecialchars($employee["telefon"]) ?></td>
</tr>

<tr>
<th>E-Posta</th>
<td><?= htmlspecialchars($employee["eposta"]) ?></td>
</tr>

</table>

<div class="section-title">

<i class="bi bi-building"></i>

Kurumsal Bilgiler

</div>

<table class="table table-bordered">

<tr>
<th>Departman</th>
<td><?= htmlspecialchars($employee["department_name"]) ?></td>
</tr>

<tr>
<th>Pozisyon</th>
<td><?= htmlspecialchars($employee["pozisyon"]) ?></td>
</tr>

<tr>
<th>İşe Giriş Tarihi</th>
<td><?= date("d.m.Y", strtotime($employee["ise_giris_tarihi"])) ?></td>
</tr>

<tr>
<th>Yönetici</th>
<td><?= htmlspecialchars($employee["yonetici"]) ?></td>
</tr>

<tr>
<th>Lokasyon</th>
<td><?= htmlspecialchars($employee["lokasyon"]) ?></td>
</tr>

</table>

<div class="section-title">

<i class="bi bi-mortarboard-fill"></i>

Eğitim Bilgileri

</div>

<table class="table table-bordered">

<tr>
<th>Eğitim</th>
<td><?= htmlspecialchars($employee["egitim"]) ?></td>
</tr>

<tr>
<th>Yabancı Dil</th>
<td><?= htmlspecialchars($employee["yabanci_dil"]) ?></td>
</tr>

</table>

<div class="section-title">

<i class="bi bi-card-text"></i>

Açıklama

</div>

<div class="card border shadow-sm">

<div class="card-body">

<?php if(empty($employee["aciklama"])): ?>

<span class="text-muted">

Bu personel için açıklama girilmemiş.

</span>

<?php else: ?>

<?= nl2br(htmlspecialchars($employee["aciklama"])) ?>

<?php endif; ?>

</div>

</div>

<div class="section-title">

<i class="bi bi-patch-check-fill"></i>

Sertifikalar

</div>

<div class="d-flex justify-content-between mb-3">

<h5>Personelin Sertifikaları</h5>

<a
href="certificate-add.php?personel_id=<?= $employee["id"] ?>"
class="btn btn-success">

<i class="bi bi-plus-circle"></i>

Yeni Sertifika Ekle

</a>

</div>

<?php if(count($sertifikaListesi)==0){ ?>

<div class="alert alert-warning">

Bu personele ait henüz sertifika bulunmuyor.

</div>

<?php }else{ ?>

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary">

<tr>

<th>#</th>

<th>Sertifika</th>

<th>Alınma Tarihi</th>

<th>Bitiş Tarihi</th>

<th>Durum</th>

<th>Dosya</th>

<th>İşlem</th>

</tr>

</thead>

<tbody>

<?php foreach($sertifikaListesi as $s){ ?>

<?php

$durum = "";
$renk = "";

if(empty($s["bitis_tarihi"])){

    $durum = "Süresiz";
    $renk = "secondary";

}else{

    $bugun = strtotime(date("Y-m-d"));
    $bitis = strtotime($s["bitis_tarihi"]);

    $gun = floor(($bitis-$bugun)/86400);

    if($gun < 0){

        $durum = "Süresi Doldu";
        $renk = "danger";

    }elseif($gun <= 30){

        $durum = $gun." Gün Kaldı";
        $renk = "warning";

    }else{

        $durum = "Geçerli";
        $renk = "success";

    }

}

?>

<tr>

<td><?= $s["id"] ?></td>

<td>

<strong>

<?= htmlspecialchars($s["sertifika_adi"]) ?>

</strong>

</td>

<td>

<?= !empty($s["alinma_tarihi"]) ? date("d.m.Y",strtotime($s["alinma_tarihi"])) : "-" ?>

</td>

<td>

<?= !empty($s["bitis_tarihi"]) ? date("d.m.Y",strtotime($s["bitis_tarihi"])) : "-" ?>

</td>

<td>

<span class="badge bg-<?= $renk ?> badge-status">

<?= $durum ?>

</span>

</td>

<td>

<?php if(!empty($s["dosya"])){ ?>

<a
href="../uploads/certificates/<?= htmlspecialchars($s["dosya"]) ?>"
target="_blank"
class="btn btn-info btn-sm">

<i class="bi bi-file-earmark-pdf"></i>

Aç

</a>

<?php }else{ ?>

<span class="text-muted">

Dosya Yok

</span>

<?php } ?>

</td>

<td>

<a
href="certificate-delete.php?id=<?= $s["id"] ?>&personel_id=<?= $employee["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Sertifika silinsin mi?');">

<i class="bi bi-trash"></i>

Sil

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

<div class="section-title">

<i class="bi bi-folder2-open"></i>

Belgeler

</div>

<?php

$belgeler = $pdo->prepare("
SELECT *
FROM documents
WHERE personel_id=?
ORDER BY id DESC
");

$belgeler->execute([$employee["id"]]);

$belgeListesi = $belgeler->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="mb-3">

<a
href="document-add.php?personel_id=<?= $employee["id"] ?>"
class="btn btn-primary">

<i class="bi bi-upload"></i>

Belge Ekle

</a>

</div>

<?php if(count($belgeListesi)==0){ ?>

<div class="alert alert-info">

Bu personele ait belge bulunmuyor.

</div>

<?php }else{ ?>

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary">

<tr>

<th>#</th>

<th>Belge</th>

<th>Dosya</th>

<th>İşlem</th>

</tr>

</thead>

<tbody>

<?php foreach($belgeListesi as $b){ ?>

<tr>

<td><?= $b["id"] ?></td>

<td><?= htmlspecialchars($b["belge_adi"]) ?></td>

<td>

<?php if(!empty($b["dosya"])){ ?>

<a
href="../uploads/documents/<?= htmlspecialchars($b["dosya"]) ?>"
target="_blank"
class="btn btn-info btn-sm">

<i class="bi bi-file-earmark"></i>

Görüntüle

</a>

<?php }else{ ?>

<span class="text-muted">

Dosya Yok

</span>

<?php } ?>

</td>

<td>

<a
href="document-delete.php?id=<?= $b["id"] ?>&personel_id=<?= $employee["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Belge silinsin mi?')">

<i class="bi bi-trash"></i>

Sil

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

</div>

</div>

</div>

</div>

</body>

</html>
