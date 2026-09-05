<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

$mesaj = "";
$hata = "";

/* Kullanıcı bilgileri */

$sorgu = $pdo->prepare("

SELECT

users.*,
roles.role_name,
departments.department_name

FROM users

LEFT JOIN roles
ON users.role_id = roles.id

LEFT JOIN departments
ON users.department_id = departments.id

WHERE users.id = ?

");

$sorgu->execute([
    $_SESSION["user_id"]
]);

$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$kullanici){

    die("Kullanıcı bulunamadı.");

}


/* Bilgileri Güncelle */

if(isset($_POST["guncelle"])){

    $ad       = trim($_POST["first_name"]);
    $soyad    = trim($_POST["last_name"]);
    $telefon  = trim($_POST["phone"]);
    $email    = trim($_POST["email"]);

    $foto = $kullanici["profile_photo"];

    if(!empty($_FILES["profile_photo"]["name"])){

        if(!is_dir("../uploads/profiles")){

            mkdir("../uploads/profiles",0777,true);

        }

        $foto = time()."_".basename($_FILES["profile_photo"]["name"]);

        move_uploaded_file(

            $_FILES["profile_photo"]["tmp_name"],

            "../uploads/profiles/".$foto

        );

    }

    $kontrol = $pdo->prepare("

    SELECT id

    FROM users

    WHERE email=?

    AND id<>?

    ");

    $kontrol->execute([

        $email,

        $_SESSION["user_id"]

    ]);

    if($kontrol->fetch()){

        $hata="Bu e-posta adresi kullanılmaktadır.";

    }else{

        $guncelle = $pdo->prepare("

        UPDATE users

        SET

        first_name=?,
        last_name=?,
        email=?,
        phone=?,
        profile_photo=?

        WHERE id=?

        ");

        $guncelle->execute([

            $ad,
            $soyad,
            $email,
            $telefon,
            $foto,
            $_SESSION["user_id"]

        ]);

        $_SESSION["ad"] = $ad;
        $_SESSION["soyad"] = $soyad;

        $mesaj="Bilgiler başarıyla güncellendi.";

        $sorgu->execute([
            $_SESSION["user_id"]
        ]);

        $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    }

}

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Profilim</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.profile-card{
    border:none;
    border-radius:18px;
}

.profile-photo{
    width:170px;
    height:170px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid #0d6efd;
}

.info-card{
    border:none;
    border-radius:18px;
}

.form-control,
.form-select{
    border-radius:10px;
}

</style>

</head>

<body>

<?php require_once "../includes/sidebar.php"; ?>

<div class="container-fluid">

<div class="row">

<div class="col-lg-4 mb-4">

<div class="card shadow profile-card">

<div class="card-body text-center">

<?php

if(!empty($kullanici["profile_photo"])
&& file_exists("../uploads/profiles/".$kullanici["profile_photo"])){

?>

<img
src="../uploads/profiles/<?= htmlspecialchars($kullanici["profile_photo"]) ?>"
class="profile-photo mb-3">

<?php }else{ ?>

<img
src="../assets/user.png"
class="profile-photo mb-3">

<?php } ?>

<h3>

<?= htmlspecialchars($kullanici["first_name"]) ?>

<?= htmlspecialchars($kullanici["last_name"]) ?>

</h3>

<p class="text-muted mb-1">

<?= htmlspecialchars($kullanici["role_name"]) ?>

</p>

<p class="text-muted">

<?= htmlspecialchars($kullanici["department_name"] ?? "-") ?>

</p>

<hr>

<div class="text-start">

<p>

<strong>E-Posta:</strong>

<br>

<?= htmlspecialchars($kullanici["email"]) ?>

</p>

<p>

<strong>Telefon:</strong>

<br>

<?= htmlspecialchars($kullanici["phone"] ?: "-") ?>

</p>

<p>

<strong>Departman:</strong>

<br>

<?= htmlspecialchars($kullanici["department_name"] ?? "-") ?>

</p>

<p>

<strong>Rol:</strong>

<br>

<?= htmlspecialchars($kullanici["role_name"]) ?>

</p>

</div>

</div>

</div>

</div>

<div class="col-lg-8">

<div class="card shadow info-card">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="bi bi-person-circle"></i>

Profil Bilgilerini Güncelle

</h4>

</div>

<div class="card-body">

<?php if($mesaj!=""){ ?>

<div class="alert alert-success">

<?= $mesaj ?>

</div>

<?php } ?>

<?php if($hata!=""){ ?>

<div class="alert alert-danger">

<?= $hata ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Ad

</label>

<input
type="text"
name="first_name"
class="form-control"
value="<?= htmlspecialchars($kullanici["first_name"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Soyad

</label>

<input
type="text"
name="last_name"
class="form-control"
value="<?= htmlspecialchars($kullanici["last_name"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

E-Posta

</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($kullanici["email"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Telefon

</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($kullanici["phone"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Departman

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($kullanici["department_name"] ?? "-") ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Rol

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($kullanici["role_name"]) ?>"
readonly>

</div>

<div class="col-md-12 mb-4">

<label class="form-label">

Profil Fotoğrafı

</label>

<input
type="file"
name="profile_photo"
class="form-control"
accept=".jpg,.jpeg,.png">

<small class="text-muted">

Boş bırakırsanız mevcut fotoğraf korunacaktır.

</small>

</div>

<div class="col-md-12 d-flex gap-2">

<button
type="submit"
name="guncelle"
class="btn btn-primary">

<i class="bi bi-check-circle-fill"></i>

Bilgileri Güncelle

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Dashboard

</a>

</div>

</div>

</form>

<hr class="my-4">

<h5>

<i class="bi bi-lock-fill"></i>

Şifre Değiştir

</h5>

<form method="POST" action="change-password.php">

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">

Mevcut Şifre

</label>

<input
type="password"
name="old_password"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Yeni Şifre

</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Yeni Şifre (Tekrar)

</label>

<input
type="password"
name="new_password2"
class="form-control"
required>

</div>

<div class="col-md-12">

<button
type="submit"
class="btn btn-warning">

<i class="bi bi-key-fill"></i>

Şifreyi Değiştir

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>

