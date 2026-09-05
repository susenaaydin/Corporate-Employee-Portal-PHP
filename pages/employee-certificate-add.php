<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["personel_id"])) {
    die("Personel seçilmedi.");
}

$personel_id = (int)$_GET["personel_id"];

/* PERSONEL */

$sql = $pdo->prepare("
SELECT *
FROM employees
WHERE id=?
");

$sql->execute([$personel_id]);

$employee = $sql->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Personel bulunamadı.");
}

/* TÜM SERTİFİKALAR */

$sertifikalar = $pdo->query("
SELECT *
FROM certificates
ORDER BY sertifika_adi ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* KAYDET */

if(isset($_POST["kaydet"])){

    $sertifika_id = $_POST["sertifika_id"];
    $alinma = $_POST["alinma_tarihi"];
    $bitis = $_POST["bitis_tarihi"];

    $dosya="";

    if(!empty($_FILES["dosya"]["name"])){

        if(!is_dir("../uploads/certificates")){
            mkdir("../uploads/certificates",0777,true);
        }

        $dosya=time()."_".$_FILES["dosya"]["name"];

        move_uploaded_file(
            $_FILES["dosya"]["tmp_name"],
            "../uploads/certificates/".$dosya
        );
    }

    $ekle=$pdo->prepare("
    INSERT INTO employee_certificates
    (
        personel_id,
        sertifika_id,
        alinma_tarihi,
        bitis_tarihi,
        dosya
    )
    VALUES
    (?,?,?,?,?)
    ");

    $ekle->execute([
        $personel_id,
        $sertifika_id,
        $alinma,
        $bitis,
        $dosya
    ]);

    header("Location: employee-detail.php?id=".$personel_id);
    exit;
}
?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Personel Sertifikası Ekle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
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

<h3>📜 Personel Sertifikası Ekle</h3>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Personel

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($employee["ad"]." ".$employee["soyad"]) ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Sertifika

</label>

<select
name="sertifika_id"
class="form-select"
required>

<option value="">-- Sertifika Seçiniz --</option>

<?php foreach($sertifikalar as $s){ ?>

<option value="<?= $s["id"] ?>">

<?= htmlspecialchars($s["sertifika_adi"]) ?>

</option>

<?php } ?>

</select>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Alınma Tarihi

</label>

<input
type="date"
name="alinma_tarihi"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Geçerlilik Bitiş Tarihi

</label>

<input
type="date"
name="bitis_tarihi"
class="form-control">

</div>

</div>

<div class="mb-3">

<label class="form-label">

Sertifika Dosyası

</label>

<input
type="file"
name="dosya"
class="form-control"
accept=".pdf,.jpg,.jpeg,.png">

<div class="form-text">

İsteğe bağlı olarak PDF veya resim yükleyebilirsiniz.

</div>

</div>

<div class="d-flex justify-content-between">

<a
href="employee-detail.php?id=<?= $personel_id ?>"
class="btn btn-secondary">

⬅ Personel Detayına Dön

</a>

<button
type="submit"
name="kaydet"
class="btn btn-success">

💾 Sertifikayı Kaydet

</button>

</div>

</form>

</div>

</div>

<div class="card shadow mt-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

📋 Personelin Mevcut Sertifikaları

</h5>

</div>

<div class="card-body">

<?php

$liste = $pdo->prepare("
SELECT
ec.*,
c.sertifika_adi
FROM employee_certificates ec
INNER JOIN certificates c
ON ec.sertifika_id = c.id
WHERE ec.personel_id=?
ORDER BY ec.alinma_tarihi DESC
");

$liste->execute([$personel_id]);

$kayitlar = $liste->fetchAll(PDO::FETCH_ASSOC);

if(count($kayitlar)==0){

?>

<div class="alert alert-warning mb-0">

Bu personele henüz sertifika eklenmemiş.

</div>

<?php

}else{

?>

<table class="table table-bordered table-striped align-middle">

<thead class="table-dark">

<tr>

<th>Sertifika</th>

<th>Alınma Tarihi</th>

<th>Bitiş Tarihi</th>

<th>Dosya</th>

</tr>

</thead>

<tbody>

<?php foreach($kayitlar as $k){ ?>

<tr>

<td>

<?= htmlspecialchars($k["sertifika_adi"]) ?>

</td>

<td>

<?= $k["alinma_tarihi"] ?>

</td>

<td>

<?php

if(!empty($k["bitis_tarihi"])){

    echo $k["bitis_tarihi"];

}else{

    echo "-";

}

?>

</td>

<td>

<?php

if(!empty($k["dosya"])){

?>

<a
href="../uploads/certificates/<?= $k["dosya"] ?>"
target="_blank"
class="btn btn-sm btn-primary">

📄 Görüntüle

</a>

<?php

}else{

echo "-";

}

?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

</div>

</div>

</div>

</body>

</html>