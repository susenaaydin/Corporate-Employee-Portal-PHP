<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!adminMi() && !ikMi()){
    die("Bu sayfaya erişim yetkiniz yok.");
}

/***************************
SERTİFİKA EKLE
****************************/

if(isset($_POST["ekle"])){

    $sertifika_adi=trim($_POST["sertifika_adi"]);
    $aciklama=trim($_POST["aciklama"]);

    if($sertifika_adi!=""){

        $kontrol=$pdo->prepare("
        SELECT COUNT(*)
        FROM certificates
        WHERE sertifika_adi=?
        ");

        $kontrol->execute([$sertifika_adi]);

        if($kontrol->fetchColumn()==0){

            $ekle=$pdo->prepare("
            INSERT INTO certificates
            (
                sertifika_adi,
                aciklama
            )
            VALUES
            (?,?)
            ");

            $ekle->execute([
                $sertifika_adi,
                $aciklama
            ]);

        }

    }

    header("Location: certificates.php");
    exit;
}

/***************************
SERTİFİKA SİL
****************************/

if(isset($_GET["sil"])){

    $id=(int)$_GET["sil"];

    $kontrol=$pdo->prepare("
    SELECT COUNT(*)
    FROM employee_certificates
    WHERE sertifika_id=?
    ");

    $kontrol->execute([$id]);

    if($kontrol->fetchColumn()==0){

        $sil=$pdo->prepare("
        DELETE
        FROM certificates
        WHERE id=?
        ");

        $sil->execute([$id]);

    }

    header("Location: certificates.php");
    exit;
}

/***************************
GÜNCELLE
****************************/

if(isset($_POST["guncelle"])){

    $id=$_POST["id"];

    $sertifika_adi=trim($_POST["sertifika_adi"]);

    $aciklama=trim($_POST["aciklama"]);

    $guncelle=$pdo->prepare("
    UPDATE certificates
    SET
    sertifika_adi=?,
    aciklama=?
    WHERE id=?
    ");

    $guncelle->execute([
        $sertifika_adi,
        $aciklama,
        $id
    ]);

    header("Location: certificates.php");
    exit;

}

/***************************
LİSTE
****************************/

$liste=$pdo->query("
SELECT *
FROM certificates
ORDER BY sertifika_adi
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Sertifika Yönetimi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:12px;
}

</style>

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="mb-0">

📜 Sertifika Yönetimi

</h3>

</div>

<div class="card-body">

<h5 class="mb-3">

➕ Yeni Sertifika Ekle

</h5>

<form method="POST">

<div class="row">

<div class="col-md-5">

<input
type="text"
name="sertifika_adi"
class="form-control"
placeholder="Sertifika Adı"
required>

</div>

<div class="col-md-5">

<input
type="text"
name="aciklama"
class="form-control"
placeholder="Açıklama">

</div>

<div class="col-md-2">

<button
type="submit"
name="ekle"
class="btn btn-success w-100">

Kaydet

</button>

</div>

</div>

</form>

<hr>

<h5 class="mb-3">

📋 Kayıtlı Sertifikalar

</h5>

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="70">ID</th>

<th>Sertifika Adı</th>

<th>Açıklama</th>

<th width="220">İşlemler</th>

</tr>

</thead>

<tbody>

<?php foreach($liste as $s){ ?>

<tr>

<form method="POST">

<input
type="hidden"
name="id"
value="<?= $s["id"] ?>">

<td>

<?= $s["id"] ?>

</td>

<td>

<input
type="text"
name="sertifika_adi"
class="form-control"
value="<?= htmlspecialchars($s["sertifika_adi"]) ?>">

</td>

<td>

<input
type="text"
name="aciklama"
class="form-control"
value="<?= htmlspecialchars($s["aciklama"]) ?>">

</td>

<td>
<button
type="submit"
name="guncelle"
class="btn btn-primary btn-sm">

💾 Güncelle

</button>

<a
href="certificates.php?sil=<?= $s["id"] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Bu sertifikayı silmek istediğinize emin misiniz?')">

🗑 Sil

</a>

</td>

</form>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php

if(count($liste)==0){

?>

<tr>

<td colspan="4" class="text-center">

Henüz hiç sertifika eklenmemiş.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<div class="mt-3">

<a
href="dashboard.php"
class="btn btn-secondary">

⬅ Dashboard'a Dön

</a>

</div>

</div>

</div>

</div>

</body>

</html>