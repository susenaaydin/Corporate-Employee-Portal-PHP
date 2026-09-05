<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

if(!isset($_GET["id"])){
    header("Location: certificates.php");
    exit;
}

$id=(int)$_GET["id"];

$sorgu=$pdo->prepare("
SELECT *
FROM certificates
WHERE id=?
");

$sorgu->execute([$id]);

$sertifika=$sorgu->fetch(PDO::FETCH_ASSOC);

if(!$sertifika){
    die("Sertifika bulunamadı.");
}

if(isset($_POST["guncelle"])){

    $sertifika_adi=trim($_POST["sertifika_adi"]);
    $aciklama=trim($_POST["aciklama"]);

    if($sertifika_adi==""){

        $hata="Sertifika adı boş olamaz.";

    }else{

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

}
?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Sertifika Düzenle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-warning">

<h3>✏ Sertifika Düzenle</h3>

</div>

<div class="card-body">

<?php if(isset($hata)){ ?>

<div class="alert alert-danger">

<?= $hata ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Sertifika Adı</label>

<input
type="text"
name="sertifika_adi"
class="form-control"
value="<?= htmlspecialchars($sertifika["sertifika_adi"]) ?>"
required>

</div>

<div class="mb-3">

<label>Açıklama</label>

<textarea
name="aciklama"
class="form-control"
rows="4"><?= htmlspecialchars($sertifika["aciklama"]) ?></textarea>

</div>

<button
type="submit"
name="guncelle"
class="btn btn-warning">

💾 Güncelle

</button>

<a
href="certificates.php"
class="btn btn-secondary">

İptal

</a>

</form>

</div>

</div>

</div>

</body>

</html>