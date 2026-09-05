<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!kullaniciYonetebilirMi()){

    die("Bu sayfaya erişim yetkiniz yok.");

}


/* =======================================================
   KULLANICI DURUMUNU DEĞİŞTİR
======================================================= */

if(isset($_GET["id"]) && isset($_GET["durum"])){

    $id=(int)$_GET["id"];

    $durum=(int)$_GET["durum"];


    /* Admin kendi hesabını pasif yapamaz */

    if($id!=$_SESSION["user_id"]){

        $guncelle=$pdo->prepare("

        UPDATE users

        SET status=?

        WHERE id=?

        ");

        $guncelle->execute([

            $durum,

            $id

        ]);

    }

    header("Location: users.php");

    exit;

}



/* =======================================================
   KULLANICILARI GETİR
======================================================= */

$sql=$pdo->query("

SELECT

users.*,

roles.role_name,

departments.department_name,

employees.personel_no,

employees.ad,

employees.soyad

FROM users

LEFT JOIN roles

ON users.role_id=roles.id

LEFT JOIN departments

ON users.department_id=departments.id

LEFT JOIN employees

ON users.employee_id=employees.id

ORDER BY users.id DESC

");

$kullanicilar=$sql->fetchAll(PDO::FETCH_ASSOC);



/* =======================================================
   İSTATİSTİKLER
======================================================= */

$toplamKullanici=count($kullanicilar);

$aktif=0;

$pasif=0;

$admin=0;

$ik=0;

$yonetici=0;

$personel=0;


foreach($kullanicilar as $k){

    if($k["status"]==1){

        $aktif++;

    }else{

        $pasif++;

    }


    switch($k["role_name"]){

        case "Admin":

            $admin++;

        break;


        case "İK":

            $ik++;

        break;


        case "Yönetici":

            $yonetici++;

        break;


        default:

            $personel++;

        break;

    }

}

?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kullanıcı Yönetimi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{

    background:#f4f6f9;

}

.content{

    margin-left:270px;

    padding:30px;

}

.card{

    border:none;

    border-radius:18px;

}

.stat-card{

    transition:.3s;

}

.stat-card:hover{

    transform:translateY(-5px);

}

.table td{

    vertical-align:middle;

}

.table th{

    white-space:nowrap;

}

.badge{

    font-size:13px;

}

.btn{

    border-radius:10px;

}

</style>

</head>

<body>

<?php require_once "../includes/sidebar.php"; ?>

<div class="content">

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="bi bi-people-fill"></i>

Kullanıcı Yönetimi

</h2>

<p class="text-muted mb-0">

Kullanıcı hesaplarını görüntüleyebilir, düzenleyebilir ve aktif/pasif duruma getirebilirsiniz.

</p>

</div>

<a
href="user-add.php"
class="btn btn-success">

<i class="bi bi-person-plus-fill"></i>

Yeni Kullanıcı

</a>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow stat-card">

<div class="card-body text-center">

<h6 class="text-muted">

Toplam Kullanıcı

</h6>

<h2 class="text-primary">

<?= $toplamKullanici ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow stat-card">

<div class="card-body text-center">

<h6 class="text-muted">

Aktif

</h6>

<h2 class="text-success">

<?= $aktif ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow stat-card">

<div class="card-body text-center">

<h6 class="text-muted">

Pasif

</h6>

<h2 class="text-danger">

<?= $pasif ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow stat-card">

<div class="card-body text-center">

<h6 class="text-muted">

Admin

</h6>

<h2 class="text-dark">

<?= $admin ?>

</h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-table"></i>

Kullanıcı Listesi

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr>

<th>ID</th>

<th>Personel No</th>

<th>Ad Soyad</th>

<th>E-Posta</th>

<th>Departman</th>

<th>Rol</th>

<th>Durum</th>

<th width="220">

İşlemler

</th>

</tr>

</thead>

<tbody>

<?php foreach($kullanicilar as $k){ ?>

<tr>

<td>

<?= $k["id"] ?>

</td>

<td>

<?= htmlspecialchars($k["personel_no"] ?? "-") ?>

</td>

<td>

<div class="fw-bold">

<?= htmlspecialchars(($k["ad"] ?? $k["first_name"])." ".($k["soyad"] ?? $k["last_name"])) ?>

</div>

<?php if(!empty($k["job_title"])){ ?>

<small class="text-muted">

<?= htmlspecialchars($k["job_title"]) ?>

</small>

<?php } ?>

</td>

<td>

<?= htmlspecialchars($k["email"]) ?>

</td>

<td>

<?= htmlspecialchars($k["department_name"] ?? "-") ?>

</td>

<td>

<?php

switch($k["role_name"]){

case "Admin":

echo '<span class="badge bg-danger">Admin</span>';

break;

case "İK":

echo '<span class="badge bg-success">İK</span>';

break;

case "Yönetici":

echo '<span class="badge bg-primary">Yönetici</span>';

break;

default:

echo '<span class="badge bg-secondary">Personel</span>';

}

?>

</td>

<td>

<?php if($k["status"]==1){ ?>

<span class="badge bg-success">

<i class="bi bi-check-circle-fill"></i>

Aktif

</span>

<?php }else{ ?>

<span class="badge bg-danger">

<i class="bi bi-x-circle-fill"></i>

Pasif

</span>

<?php } ?>

</td>

<td>

<div class="btn-group">

<a
href="user-edit.php?id=<?= $k["id"] ?>"
class="btn btn-warning btn-sm"
title="Düzenle">

<i class="bi bi-pencil-square"></i>

</a>

<?php if($k["id"] != $_SESSION["user_id"]){ ?>

<?php if($k["status"]==1){ ?>

<a
href="users.php?id=<?= $k["id"] ?>&durum=0"
class="btn btn-secondary btn-sm"
onclick="return confirm('Kullanıcı pasif yapılsın mı?')"
title="Pasif Yap">

<i class="bi bi-person-dash-fill"></i>

</a>

<?php }else{ ?>

<a
href="users.php?id=<?= $k["id"] ?>&durum=1"
class="btn btn-success btn-sm"
onclick="return confirm('Kullanıcı aktif yapılsın mı?')"
title="Aktif Yap">

<i class="bi bi-person-check-fill"></i>

</a>

<?php } ?>

<?php }else{ ?>

<button
class="btn btn-outline-secondary btn-sm"
disabled
title="Kendi hesabınız">

<i class="bi bi-person-fill-lock"></i>

</button>

<?php } ?>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<div class="row mt-4">

<div class="col-md-4 mb-3">

<div class="card shadow-sm stat-card">

<div class="card-body text-center">

<i class="bi bi-person-badge-fill text-success fs-2"></i>

<h6 class="mt-2 text-muted">

İK Kullanıcısı

</h6>

<h2 class="text-success fw-bold">

<?= $ik ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card shadow-sm stat-card">

<div class="card-body text-center">

<i class="bi bi-person-workspace text-primary fs-2"></i>

<h6 class="mt-2 text-muted">

Yönetici

</h6>

<h2 class="text-primary fw-bold">

<?= $yonetici ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card shadow-sm stat-card">

<div class="card-body text-center">

<i class="bi bi-people text-secondary fs-2"></i>

<h6 class="mt-2 text-muted">

Personel

</h6>

<h2 class="text-secondary fw-bold">

<?= $personel ?>

</h2>

</div>

</div>

</div>

</div>

<div class="text-center mt-4 mb-3">

<small class="text-muted">

Kurumsal Portal • Kullanıcı Yönetimi

</small>

</div>

</div>

</div>

</body>

</html>

