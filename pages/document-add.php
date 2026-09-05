<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET["personel_id"])) {
    header("Location: employees.php");
    exit;
}

$personel_id = (int)$_GET["personel_id"];

/* PERSONELİ GETİR */

$sorgu = $pdo->prepare("
SELECT *
FROM employees
WHERE id=?
");

$sorgu->execute([$personel_id]);

$employee = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$employee){
    die("Personel bulunamadı.");
}

/* KAYDET */

if(isset($_POST["kaydet"])){

    $belge_adi = trim($_POST["belge_adi"]);

    $dosya = "";

    if(!empty($_FILES["dosya"]["name"])){

        if(!is_dir("../uploads/documents")){
            mkdir("../uploads/documents",0777,true);
        }

        $uzanti = strtolower(pathinfo($_FILES["dosya"]["name"],PATHINFO_EXTENSION));

        $izinli = [
            "pdf",
            "jpg",
            "jpeg",
            "png",
            "doc",
            "docx",
            "xls",
            "xlsx"
        ];

        if(in_array($uzanti,$izinli)){

            $dosya = time()."_".basename($_FILES["dosya"]["name"]);

            move_uploaded_file(
                $_FILES["dosya"]["tmp_name"],
                "../uploads/documents/".$dosya
            );

        }else{

            die("Desteklenmeyen dosya türü.");

        }

    }

    $ekle = $pdo->prepare("
    INSERT INTO documents
    (
        personel_id,
        belge_adi,
        dosya
    )
    VALUES
    (?,?,?)
    ");

    $ekle->execute([
        $personel_id,
        $belge_adi,
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

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Belge Ekle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
background:#f5f7fa;
}

.card{
border:none;
border-radius:18px;
}

.card-header{
font-size:22px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="container py-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<i class="bi bi-file-earmark-plus-fill"></i>

Personel Belgesi Ekle

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-12 mb-3">

<label class="form-label">

Personel

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($employee["ad"]." ".$employee["soyad"]) ?>"
readonly>

</div>

<div class="col-md-12 mb-3">

<label class="form-label">

Belge Adı

</label>

<input
type="text"
name="belge_adi"
class="form-control"
placeholder="Örn: Diploma, Kimlik Fotokopisi, İş Sözleşmesi..."
required>

</div>

<div class="col-md-12 mb-4">

<label class="form-label">

Belge Dosyası

</label>

<input
type="file"
name="dosya"
class="form-control"
accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
required>

<div class="form-text">

Desteklenen dosya türleri:
PDF, Word, Excel, JPG ve PNG.

</div>

</div>

<hr>

<div class="col-md-12">

<div class="alert alert-info">

<strong>Bilgilendirme</strong><br>

Yüklediğiniz belge personelin dijital dosyasında saklanacaktır.
Belge daha sonra personel detay ekranından görüntülenebilir.

</div>

</div>

<div class="col-md-12 d-flex justify-content-between mt-3">

<a
href="employee-detail.php?id=<?= $personel_id ?>"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Personel Detayına Dön

</a>

<div>

<button
type="reset"
class="btn btn-outline-danger me-2">

<i class="bi bi-x-circle"></i>

Temizle

</button>

<button
type="submit"
name="kaydet"
class="btn btn-primary">

<i class="bi bi-save"></i>

Belgeyi Kaydet

</button>

</div>

</div>

</div>

</form>

</div>

</div>

</div>

</body>

</html>