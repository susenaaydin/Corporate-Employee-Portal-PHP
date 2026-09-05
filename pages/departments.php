<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!adminMi()){
    die("Bu sayfaya erişim yetkiniz yok.");
}

/* DEPARTMANLARI GETİR */

$departmanlar = $pdo->query("
SELECT *
FROM departments
ORDER BY department_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Departman Yönetimi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
background:#f5f7fa;
}

.card{
border:none;
border-radius:15px;
}

</style>

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h3 class="mb-0">

🏢 Departman Yönetimi

</h3>

<a
href="department-add.php"
class="btn btn-light">

<i class="bi bi-plus-circle"></i>

Yeni Departman

</a>

</div>

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary">

<tr>

<th width="80">ID</th>

<th>Departman Adı</th>

<th width="220">İşlemler</th>

</tr>

</thead>

<tbody>

<?php foreach($departmanlar as $d){ ?>

<tr>

<td><?= $d["id"] ?></td>

<td>

<strong><?= htmlspecialchars($d["department_name"]) ?></strong>

</td>

<td>

<a
href="department-edit.php?id=<?= $d["id"] ?>"
class="btn btn-warning btn-sm">

✏ Düzenle

</a>

<a
href="department-delete.php?id=<?= $d["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Bu departman silinsin mi?')">

🗑 Sil

</a>

</td>

</tr>

<?php } ?>

<?php if(count($departmanlar)==0){ ?>

<tr>

<td colspan="3" class="text-center text-muted">

Henüz departman eklenmemiş.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<div class="mt-3">

<a
href="dashboard.php"
class="btn btn-secondary">

⬅ Dashboard

</a>

</div>

</div>

</body>

</html>