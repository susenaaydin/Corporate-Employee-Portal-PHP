<?php

session_start();


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}


require_once "../config/database.php";



/* İstatistikler */

$toplamPersonel = $pdo->query(
"SELECT COUNT(*) FROM employees"
)->fetchColumn();



$toplamDepartman = $pdo->query(
"SELECT COUNT(*) FROM departments"
)->fetchColumn();



$toplamSertifika = $pdo->query(
"SELECT COUNT(*) FROM certificates"
)->fetchColumn();



$toplamBelge = $pdo->query(
"SELECT COUNT(*) FROM documents"
)->fetchColumn();




/* Son Personeller */

$sonPersoneller = $pdo->query("

SELECT

employees.*,

departments.department_name

FROM employees

LEFT JOIN departments

ON employees.departman_id = departments.id

ORDER BY employees.id DESC

LIMIT 5


")->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>

<html lang="tr">

<head>


<meta charset="UTF-8">


<title>Kontrol Paneli</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">



<style>


body{

background:#f4f6f9;

}



.content{

margin-left:260px;

padding:30px;

}




.topbar{

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:30px;

}




.stat-card{

border:none;

border-radius:20px;

color:white;

transition:.3s;

}


.stat-card:hover{

transform:translateY(-8px);

}



.stat-card i{

font-size:45px;

}



.stat-card h2{

font-size:40px;

font-weight:700;

}



.bg-blue{

background:linear-gradient(135deg,#2563eb,#1e40af);

}


.bg-green{

background:linear-gradient(135deg,#16a34a,#166534);

}


.bg-orange{

background:linear-gradient(135deg,#f59e0b,#b45309);

}


.bg-red{

background:linear-gradient(135deg,#dc2626,#991b1b);

}





.box-card{

border:none;

border-radius:20px;

transition:.3s;

}



.box-card:hover{

transform:translateY(-5px);

box-shadow:0 10px 25px rgba(0,0,0,.12);

}




.quick-icon{

font-size:45px;

}



</style>



</head>


<body>



<?php include("../includes/header.php"); ?>


<?php include("../includes/sidebar.php"); ?>




<div class="content">






<!-- ÜST ALAN -->


<div class="topbar">


<div>


<h3>


👋 Hoş Geldin,

<?= htmlspecialchars($_SESSION["ad"] ?? "Kullanıcı") ?>


</h3>



<p class="text-muted">

Kurumsal Portal Yönetim Paneli

</p>


</div>




<div>


<a href="logout.php" class="btn btn-danger">


<i class="bi bi-box-arrow-right"></i>

Çıkış Yap


</a>


</div>



</div>








<!-- İSTATİSTİKLER -->



<div class="row">



<div class="col-lg-3 col-md-6 mb-4">


<div class="card stat-card bg-blue">


<div class="card-body">


<i class="bi bi-people-fill"></i>


<h2>

<?= $toplamPersonel ?>

</h2>


<p>

Toplam Personel

</p>


</div>


</div>


</div>







<div class="col-lg-3 col-md-6 mb-4">


<div class="card stat-card bg-green">


<div class="card-body">


<i class="bi bi-diagram-3-fill"></i>


<h2>

<?= $toplamDepartman ?>

</h2>


<p>

Departman

</p>


</div>


</div>


</div>







<div class="col-lg-3 col-md-6 mb-4">


<div class="card stat-card bg-orange">


<div class="card-body">


<i class="bi bi-patch-check-fill"></i>


<h2>

<?= $toplamSertifika ?>

</h2>


<p>

Sertifika

</p>


</div>


</div>


</div>








<div class="col-lg-3 col-md-6 mb-4">


<div class="card stat-card bg-red">


<div class="card-body">


<i class="bi bi-folder-fill"></i>


<h2>

<?= $toplamBelge ?>

</h2>


<p>

Belge

</p>


</div>


</div>


</div>



</div>









<!-- SON PERSONELLER -->



<div class="card shadow box-card mt-3">


<div class="card-header bg-white">


<h5>

🆕 Son Eklenen Personeller

</h5>


</div>



<div class="card-body">



<table class="table table-hover">


<thead>


<tr>

<th>ID</th>

<th>Ad</th>

<th>Soyad</th>

<th>Departman</th>

<th>Pozisyon</th>

<th>İşlem</th>


</tr>


</thead>




<tbody>



<?php foreach($sonPersoneller as $p){ ?>



<tr>


<td>

<?= $p["id"] ?>

</td>


<td>

<?= htmlspecialchars($p["ad"]) ?>

</td>



<td>

<?= htmlspecialchars($p["soyad"]) ?>

</td>



<td>

<?= htmlspecialchars($p["department_name"] ?? "-") ?>

</td>



<td>

<?= htmlspecialchars($p["pozisyon"]) ?>

</td>



<td>


<a href="employee-detail.php?id=<?= $p["id"] ?>" 
class="btn btn-primary btn-sm">


<i class="bi bi-eye"></i>

Detay


</a>


</td>


</tr>



<?php } ?>



</tbody>



</table>



</div>


</div>









<!-- HIZLI İŞLEMLER -->
 <div class="col-lg-4 col-md-6 mb-4">

<div class="card box-card text-center">

<div class="card-body">


<i class="bi bi-person-gear quick-icon text-danger"></i>


<h5>
Kullanıcı Yönetimi
</h5>


<a href="../admin/users.php" class="btn btn-danger">

Yönet

</a>


</div>

</div>

</div>



<h4 class="mt-5 mb-3">

⚡ Hızlı İşlemler

</h4>




<div class="row">






<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-people quick-icon text-primary"></i>


<h5 class="mt-3">

Personel Yönetimi

</h5>



<a href="employees.php" class="btn btn-primary">

Görüntüle

</a>



</div>


</div>


</div>







<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-person-plus quick-icon text-success"></i>



<h5>

Personel Ekle

</h5>



<a href="employee-add.php" class="btn btn-success">

Yeni Kayıt

</a>


</div>


</div>


</div>







<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-search quick-icon text-warning"></i>



<h5>

Personel Ara

</h5>



<a href="search.php" class="btn btn-warning">

Ara

</a>


</div>


</div>


</div>









<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-building quick-icon text-info"></i>


<h5>

Departman Yönetimi

</h5>



<a href="departments.php" class="btn btn-info">

Yönet

</a>



</div>


</div>


</div>








<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-award quick-icon text-secondary"></i>



<h5>

Sertifika Yönetimi

</h5>



<a href="certificates.php" class="btn btn-secondary">

Yönet

</a>



</div>


</div>


</div>







<div class="col-lg-4 col-md-6 mb-4">


<div class="card box-card text-center">


<div class="card-body">


<i class="bi bi-file-earmark-excel quick-icon text-success"></i>



<h5>

Excel Aktarım

</h5>



<a href="personel-import.php" class="btn btn-success">

Aktar

</a>



</div>


</div>


</div>





</div>







</div>




</body>

</html>