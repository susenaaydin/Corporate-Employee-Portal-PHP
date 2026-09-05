<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!adminMi() && !ikMi()){
    die("Bu sayfaya erişim yetkiniz yok.");
}
$id = (int)$_GET["id"];

/* PERSONELİ GETİR */

$sorgu = $pdo->prepare("
SELECT *
FROM employees
WHERE id=?
");

$sorgu->execute([$id]);

$employee = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    die("Personel bulunamadı.");
}

/* DEPARTMANLAR */

$departmanlar = $pdo->query("
SELECT *
FROM departments
ORDER BY department_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* GÜNCELLE */

if(isset($_POST["guncelle"])){

    $personel_no      = trim($_POST["personel_no"]);
    $ad               = trim($_POST["ad"]);
    $soyad            = trim($_POST["soyad"]);
    $cinsiyet         = $_POST["cinsiyet"];
    $medeni_durum     = $_POST["medeni_durum"];
    $kan_grubu        = $_POST["kan_grubu"];
    $dogum_tarihi     = $_POST["dogum_tarihi"];
    $ise_giris_tarihi = $_POST["ise_giris_tarihi"];
    $telefon          = trim($_POST["telefon"]);
    $eposta           = trim($_POST["eposta"]);
    $departman_id     = $_POST["departman_id"];
    $pozisyon         = trim($_POST["pozisyon"]);
    $yonetici         = trim($_POST["yonetici"]);
    $lokasyon         = trim($_POST["lokasyon"]);
    $egitim           = trim($_POST["egitim"]);
    $yabanci_dil      = trim($_POST["yabanci_dil"]);
    $aciklama         = trim($_POST["aciklama"]);

    $fotograf = $employee["fotograf"];

    /* FOTOĞRAF */

    if(!empty($_FILES["fotograf"]["name"])){

        if(!is_dir("../uploads")){
            mkdir("../uploads",0777,true);
        }

        $uzanti = pathinfo($_FILES["fotograf"]["name"],PATHINFO_EXTENSION);

        $fotograf = time().".".$uzanti;

        move_uploaded_file(
            $_FILES["fotograf"]["tmp_name"],
            "../uploads/".$fotograf
        );
    }

    $update = $pdo->prepare("
    UPDATE employees SET

    personel_no=?,
    ad=?,
    soyad=?,
    cinsiyet=?,
    medeni_durum=?,
    kan_grubu=?,
    dogum_tarihi=?,
    ise_giris_tarihi=?,
    telefon=?,
    eposta=?,
    departman_id=?,
    pozisyon=?,
    yonetici=?,
    lokasyon=?,
    egitim=?,
    yabanci_dil=?,
    aciklama=?,
    fotograf=?

    WHERE id=?
    ");

    $update->execute([

        $personel_no,
        $ad,
        $soyad,
        $cinsiyet,
        $medeni_durum,
        $kan_grubu,
        $dogum_tarihi,
        $ise_giris_tarihi,
        $telefon,
        $eposta,
        $departman_id,
        $pozisyon,
        $yonetici,
        $lokasyon,
        $egitim,
        $yabanci_dil,
        $aciklama,
        $fotograf,
        $id

    ]);

    header("Location: employee-detail.php?id=".$id);
    exit;
}
?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Personel Düzenle</title>

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

<div class="card-header bg-warning">

<i class="bi bi-pencil-square"></i>

Personel Düzenle

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">Personel No</label>

<input
type="text"
name="personel_no"
class="form-control"
value="<?= htmlspecialchars($employee["personel_no"]) ?>"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Ad</label>

<input
type="text"
name="ad"
class="form-control"
value="<?= htmlspecialchars($employee["ad"]) ?>"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Soyad</label>

<input
type="text"
name="soyad"
class="form-control"
value="<?= htmlspecialchars($employee["soyad"]) ?>"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Cinsiyet</label>

<select name="cinsiyet" class="form-select">

<option value="Kadın" <?= $employee["cinsiyet"]=="Kadın" ? "selected" : "" ?>>Kadın</option>

<option value="Erkek" <?= $employee["cinsiyet"]=="Erkek" ? "selected" : "" ?>>Erkek</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Medeni Durum</label>

<select name="medeni_durum" class="form-select">

<option value="Bekar" <?= $employee["medeni_durum"]=="Bekar" ? "selected" : "" ?>>Bekar</option>

<option value="Evli" <?= $employee["medeni_durum"]=="Evli" ? "selected" : "" ?>>Evli</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Kan Grubu</label>

<select name="kan_grubu" class="form-select">

<option value="A+" <?= $employee["kan_grubu"]=="A+" ? "selected" : "" ?>>A+</option>
<option value="A-" <?= $employee["kan_grubu"]=="A-" ? "selected" : "" ?>>A-</option>
<option value="B+" <?= $employee["kan_grubu"]=="B+" ? "selected" : "" ?>>B+</option>
<option value="B-" <?= $employee["kan_grubu"]=="B-" ? "selected" : "" ?>>B-</option>
<option value="AB+" <?= $employee["kan_grubu"]=="AB+" ? "selected" : "" ?>>AB+</option>
<option value="AB-" <?= $employee["kan_grubu"]=="AB-" ? "selected" : "" ?>>AB-</option>
<option value="0+" <?= $employee["kan_grubu"]=="0+" ? "selected" : "" ?>>0+</option>
<option value="0-" <?= $employee["kan_grubu"]=="0-" ? "selected" : "" ?>>0-</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Doğum Tarihi</label>

<input
type="date"
name="dogum_tarihi"
class="form-control"
value="<?= $employee["dogum_tarihi"] ?>">

</div>

<div class="col-md-4 mb-3">

<label class="form-label">İşe Giriş Tarihi</label>

<input
type="date"
name="ise_giris_tarihi"
class="form-control"
value="<?= $employee["ise_giris_tarihi"] ?>">

</div>

<div class="col-md-4 mb-3">

<label class="form-label">Telefon</label>

<input
type="text"
name="telefon"
class="form-control"
value="<?= htmlspecialchars($employee["telefon"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">E-Posta</label>

<input
type="email"
name="eposta"
class="form-control"
value="<?= htmlspecialchars($employee["eposta"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Departman</label>

<select name="departman_id" class="form-select">

<?php foreach($departmanlar as $d){ ?>

<option
value="<?= $d["id"] ?>"
<?= $employee["departman_id"]==$d["id"] ? "selected" : "" ?>>

<?= htmlspecialchars($d["department_name"]) ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Pozisyon</label>

<input
type="text"
name="pozisyon"
class="form-control"
value="<?= htmlspecialchars($employee["pozisyon"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Yönetici</label>

<input
type="text"
name="yonetici"
class="form-control"
value="<?= htmlspecialchars($employee["yonetici"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Lokasyon</label>

<input
type="text"
name="lokasyon"
class="form-control"
value="<?= htmlspecialchars($employee["lokasyon"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Eğitim</label>

<input
type="text"
name="egitim"
class="form-control"
value="<?= htmlspecialchars($employee["egitim"]) ?>">

</div>

<div class="col-md-12 mb-3">

<label class="form-label">Yabancı Dil</label>

<input
type="text"
name="yabanci_dil"
class="form-control"
value="<?= htmlspecialchars($employee["yabanci_dil"]) ?>">

</div>

<div class="col-md-12 mb-3">

<label class="form-label">Açıklama</label>

<textarea
name="aciklama"
rows="5"
class="form-control"><?= htmlspecialchars($employee["aciklama"]) ?></textarea>

</div>

<div class="col-md-12 mb-4">

<label class="form-label">

Fotoğraf

</label>

<?php if(!empty($employee["fotograf"])){ ?>

<div class="mb-3">

<img
src="../uploads/<?= htmlspecialchars($employee["fotograf"]) ?>"
class="img-thumbnail"
style="max-width:200px;">

</div>

<?php }else{ ?>

<div class="alert alert-secondary">

Henüz fotoğraf yüklenmemiş.

</div>

<?php } ?>

<input
type="file"
name="fotograf"
class="form-control"
accept=".jpg,.jpeg,.png">

<div class="form-text">

Yeni fotoğraf seçmezsen mevcut fotoğraf korunacaktır.

</div>

</div>

<hr class="my-4">

<div class="col-md-12 d-flex justify-content-between">

<a
href="employee-detail.php?id=<?= $employee["id"] ?>"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

İptal

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
name="guncelle"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Personeli Güncelle

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