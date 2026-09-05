<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if(isset($_POST["kaydet"])){

    $department_name = trim($_POST["department_name"]);

    if($department_name==""){

        $hata="Departman adı boş bırakılamaz.";

    }else{

        $kontrol=$pdo->prepare("
        SELECT COUNT(*)
        FROM departments
        WHERE department_name=?
        ");

        $kontrol->execute([$department_name]);

        if($kontrol->fetchColumn()>0){

            $hata="Bu departman zaten mevcut.";

        }else{

            $ekle=$pdo->prepare("
            INSERT INTO departments
            (department_name)
            VALUES
            (?)
            ");

            $ekle->execute([$department_name]);

            header("Location: departments.php");
            exit;

        }

    }

}
?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Departman Ekle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

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

<div class="card-header bg-success text-white">

<h3>🏢 Yeni Departman</h3>

</div>

<div class="card-body">

<?php if(isset($hata)){ ?>

<div class="alert alert-danger">

<?= $hata ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Departman Adı

</label>

<input
type="text"
name="department_name"
class="form-control"
placeholder="Örn: Kalite"

required>

</div>

<div class="d-flex justify-content-between">

<a
href="departments.php"
class="btn btn-secondary">

← Geri

</a>

<button
type="submit"
name="kaydet"
class="btn btn-success">

💾 Kaydet

</button>

</div>

</form>

</div>

</div>

</div>

</body>

</html>