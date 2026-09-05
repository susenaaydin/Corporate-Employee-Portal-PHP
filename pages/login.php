<?php

session_start();

require_once "../config/database.php";

$hata = "";

if(isset($_SESSION["user_id"])){

    header("Location: dashboard.php");
    exit;

}

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $email=trim($_POST["email"]);
    $password=$_POST["password"];

    $sorgu=$pdo->prepare("

    SELECT

    users.*,
    roles.role_name

    FROM users

    LEFT JOIN roles

    ON users.role_id=roles.id

    WHERE users.email=?

    LIMIT 1

    ");

    $sorgu->execute([$email]);

    $kullanici=$sorgu->fetch(PDO::FETCH_ASSOC);

    if($kullanici){

        if(password_verify($password,$kullanici["password"])){

            $_SESSION["user_id"]=$kullanici["id"];

            $_SESSION["employee_id"]=$kullanici["employee_id"];

            $_SESSION["ad"]=$kullanici["first_name"];

            $_SESSION["soyad"]=$kullanici["last_name"];

            $_SESSION["role_id"]=$kullanici["role_id"];

            $_SESSION["role_name"]=$kullanici["role_name"];

            header("Location: dashboard.php");
            exit;

        }else{

            $hata="Şifre hatalı.";

        }

    }else{

        $hata="Bu e-posta adresine ait kullanıcı bulunamadı.";

    }

}

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kurumsal Portal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{

background:linear-gradient(135deg,#0d6efd,#4f46e5);

height:100vh;

display:flex;

justify-content:center;

align-items:center;

font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;

}

.card{

border:none;

border-radius:20px;

box-shadow:0 20px 40px rgba(0,0,0,.25);

}

.logo{

font-size:60px;

}

.btn-primary{

border-radius:10px;

padding:12px;

font-weight:600;

}

.form-control{

border-radius:10px;

padding:12px;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-4 col-md-6">

<div class="card">

<div class="card-body p-5">

<div class="text-center mb-4">

<div class="logo">

🏢

</div>

<h3 class="mt-3">

Kurumsal Portal

</h3>

<p class="text-muted">

Personel Yönetim Sistemi

</p>

</div>

<?php if($hata!=""){ ?>

<div class="alert alert-danger">

<?= $hata ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

E-Posta

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-envelope-fill"></i>

</span>

<input

type="email"

name="email"

class="form-control"

required

>

</div>

</div>

<div class="mb-4">

<label class="form-label">

Şifre

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock-fill"></i>

</span>

<input

type="password"

name="password"

class="form-control"

required

>

</div>

</div>

<button

type="submit"

class="btn btn-primary w-100"

>

<i class="bi bi-box-arrow-in-right"></i>

Giriş Yap

</button>

</form>

<div class="text-center mt-4">

<hr>

<small class="text-muted">

© <?= date("Y") ?>

Kurumsal Portal

<br>

Tüm hakları saklıdır.

</small>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>

