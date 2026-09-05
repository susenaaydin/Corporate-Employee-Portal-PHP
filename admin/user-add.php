<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

adminKontrol();


$mesaj = "";
$hata = "";


// Roller
$roller = $pdo->query("
SELECT * FROM roles
ORDER BY id
")->fetchAll(PDO::FETCH_ASSOC);


// Departmanlar
$departmanlar = $pdo->query("
SELECT * FROM departments
ORDER BY department_name
")->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST["ekle"])){


    $ad = trim($_POST["first_name"]);
    $soyad = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $sifre = trim($_POST["password"]);
    $telefon = trim($_POST["phone"]);
    $rol = $_POST["role_id"];
    $departman = $_POST["department_id"];
    $pozisyon = trim($_POST["job_title"]);



    if(
        empty($ad) ||
        empty($soyad) ||
        empty($email) ||
        empty($sifre)
    ){

        $hata = "Zorunlu alanları doldurun.";

    }
    else{


        // Aynı mail kontrolü

        $kontrol = $pdo->prepare("
        SELECT id FROM users WHERE email=?
        ");

        $kontrol->execute([$email]);


        if($kontrol->fetch()){


            $hata = "Bu e-posta zaten kayıtlı.";


        }
        else{


            $ekle = $pdo->prepare("

            INSERT INTO users

            (
            role_id,
            department_id,
            first_name,
            last_name,
            email,
            password,
            phone,
            job_title
            )

            VALUES
            (?,?,?,?,?,?,?,?)

            ");


            $ekle->execute([

            $rol,
            $departman,
            $ad,
            $soyad,
            $email,
            $sifre,
            $telefon,
            $pozisyon

            ]);


            $mesaj="Kullanıcı başarıyla eklendi.";

        }


    }



}


?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Kullanıcı Ekle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="card shadow">


<div class="card-header">

<h4>
➕ Yeni Kullanıcı Ekle
</h4>

</div>



<div class="card-body">



<?php if($mesaj){ ?>

<div class="alert alert-success">
<?= $mesaj ?>
</div>

<?php } ?>


<?php if($hata){ ?>

<div class="alert alert-danger">
<?= $hata ?>
</div>

<?php } ?>



<form method="POST">



<div class="row">



<div class="col-md-6 mb-3">

<label>Ad</label>

<input 
type="text"
name="first_name"
class="form-control"
required>

</div>




<div class="col-md-6 mb-3">

<label>Soyad</label>

<input 
type="text"
name="last_name"
class="form-control"
required>

</div>





<div class="col-md-6 mb-3">

<label>E-Posta</label>

<input 
type="email"
name="email"
class="form-control"
required>

</div>




<div class="col-md-6 mb-3">

<label>Şifre</label>

<input 
type="password"
name="password"
class="form-control"
required>

</div>





<div class="col-md-6 mb-3">

<label>Telefon</label>

<input 
type="text"
name="phone"
class="form-control">

</div>





<div class="col-md-6 mb-3">

<label>Pozisyon</label>

<input 
type="text"
name="job_title"
class="form-control">

</div>





<div class="col-md-6 mb-3">

<label>Rol</label>

<select name="role_id" class="form-select">


<?php foreach($roller as $r){ ?>

<option value="<?= $r["id"] ?>">

<?= htmlspecialchars($r["role_name"]) ?>

</option>


<?php } ?>


</select>


</div>





<div class="col-md-6 mb-3">

<label>Departman</label>


<select name="department_id" class="form-select">


<?php foreach($departmanlar as $d){ ?>

<option value="<?= $d["id"] ?>">

<?= htmlspecialchars($d["department_name"]) ?>

</option>


<?php } ?>


</select>


</div>




</div>




<button 
class="btn btn-success"
name="ekle">

Kaydet

</button>



<a href="users.php" class="btn btn-secondary">

Geri Dön

</a>



</form>


</div>

</div>


</div>


</body>

</html>