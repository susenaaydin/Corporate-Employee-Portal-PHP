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

$sorgu = $pdo->prepare("
SELECT *
FROM departments
WHERE id=?
");

$sorgu->execute([$id]);

$departman = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$departman){
    die("Departman bulunamadı.");
}

if(isset($_POST["guncelle"])){

    $department_name = trim($_POST["department_name"]);

    if($department_name==""){

        $hata="Departman adı boş bırakılamaz.";

    }else{

        $guncelle = $pdo->prepare("
        UPDATE departments
        SET department_name=?
        WHERE id=?
        ");

        $guncelle->execute([
            $department_name,
            $id
        ]);

        header("Location: departments.php");
        exit;
    }

}
?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Departman Düzenle</title>

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

<div class="card-header bg-warning">

<h3>✏ Departman Düzenle</h3>

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
value="<?= htmlspecialchars($departman["department_name"]) ?>"
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
name="guncelle"
class="btn btn-warning">

💾 Güncelle

</button>

</div>

</form>

</div>

</div>

</div>

</body>

</html>