<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();


if (!personelEkleyebilirMi()) {

    echo "
    <div style='
        margin:50px;
        padding:20px;
        background:#f8d7da;
        color:#842029;
        border-radius:10px;
        font-family:Arial;
    '>
        Bu sayfaya erişim yetkiniz yok.
    </div>
    ";

    exit;

}



$departmanlar = $pdo->query("

SELECT *

FROM departments

ORDER BY department_name

")->fetchAll(PDO::FETCH_ASSOC);



$roller = $pdo->query("

SELECT *

FROM roles

ORDER BY id

")->fetchAll(PDO::FETCH_ASSOC);





if(isset($_POST["kaydet"])){


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

    // YENİ EKLENDİ
    $role_id          = $_POST["role_id"];

    $pozisyon         = trim($_POST["pozisyon"]);

    $yonetici         = trim($_POST["yonetici"]);

    $lokasyon         = trim($_POST["lokasyon"]);

    $egitim           = trim($_POST["egitim"]);

    $yabanci_dil      = trim($_POST["yabanci_dil"]);

    $aciklama         = trim($_POST["aciklama"]);




    $fotograf="";



    if(!empty($_FILES["fotograf"]["name"])){


        if(!is_dir("../uploads")){

            mkdir("../uploads",0777,true);

        }


        $fotograf=time()."_".basename($_FILES["fotograf"]["name"]);



        move_uploaded_file(

            $_FILES["fotograf"]["tmp_name"],

            "../uploads/".$fotograf

        );


    }





    // Personel no kontrol

    $kontrol=$pdo->prepare("

    SELECT id

    FROM employees

    WHERE personel_no=?

    ");



    $kontrol->execute([

        $personel_no

    ]);



    if($kontrol->fetch()){


        die("Bu personel numarası zaten kayıtlı.");


    }





    // Eposta kontrol

    $kontrol=$pdo->prepare("

    SELECT id

    FROM users

    WHERE email=?

    ");



    $kontrol->execute([

        $eposta

    ]);



    if($kontrol->fetch()){


        die("Bu e-posta zaten kullanılıyor.");


    }





    // PERSONEL EKLE


    $sql=$pdo->prepare("

    INSERT INTO employees

    (

    personel_no,

    ad,

    soyad,

    cinsiyet,

    medeni_durum,

    kan_grubu,

    dogum_tarihi,

    ise_giris_tarihi,

    telefon,

    eposta,

    departman_id,

    pozisyon,

    yonetici,

    lokasyon,

    egitim,

    yabanci_dil,

    aciklama,

    fotograf

    )


    VALUES

    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)

    ");



    $sql->execute([


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

        $fotograf


    ]);



    $employee_id=$pdo->lastInsertId();


    /*
    KULLANICI HESABI OLUŞTURMA
    */


    $varsayilanSifre = password_hash("123456", PASSWORD_DEFAULT);



    $kullanici = $pdo->prepare("

    INSERT INTO users

    (

    employee_id,

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

    (?,?,?,?,?,?,?,?,?)

    ");



    $kullanici->execute([


        $employee_id,


        $role_id,


        $departman_id,


        $ad,


        $soyad,


        $eposta,


        $varsayilanSifre,


        $telefon,


        $pozisyon


    ]);





    header("Location: employees.php?success=1");

    exit;


}



?>

<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Yeni Personel Ekle</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">



<style>

body{

background:#f4f6f9;

}


.card{

border:none;

border-radius:18px;

}


.card-header{

border-radius:18px 18px 0 0 !important;

}

</style>


</head>


<body>



<div class="container mt-4">


<div class="card shadow">



<div class="card-header bg-primary text-white">


<h3>

<i class="bi bi-person-plus"></i>

Yeni Personel Ekle

</h3>


</div>



<div class="card-body">



<form method="POST" enctype="multipart/form-data">


<div class="row">



<div class="col-md-4 mb-3">

<label class="form-label">

Personel No

</label>


<input

type="text"

name="personel_no"

class="form-control"

required>


</div>





<div class="col-md-4 mb-3">


<label class="form-label">

Ad

</label>


<input

type="text"

name="ad"

class="form-control"

required>


</div>





<div class="col-md-4 mb-3">


<label class="form-label">

Soyad

</label>


<input

type="text"

name="soyad"

class="form-control"

required>


</div>





<div class="col-md-4 mb-3">


<label>

Cinsiyet

</label>


<select

name="cinsiyet"

class="form-select">


<option>Kadın</option>

<option>Erkek</option>


</select>


</div>





<div class="col-md-4 mb-3">


<label>

Medeni Durum

</label>


<select

name="medeni_durum"

class="form-select">


<option>Bekar</option>

<option>Evli</option>


</select>


</div>





<div class="col-md-4 mb-3">


<label>

Kan Grubu

</label>


<select

name="kan_grubu"

class="form-select">


<option>A+</option>

<option>A-</option>

<option>B+</option>

<option>B-</option>

<option>AB+</option>

<option>AB-</option>

<option>0+</option>

<option>0-</option>


</select>


</div>





<div class="col-md-4 mb-3">


<label>

Doğum Tarihi

</label>


<input

type="date"

name="dogum_tarihi"

class="form-control">


</div>





<div class="col-md-4 mb-3">


<label>

İşe Giriş Tarihi

</label>


<input

type="date"

name="ise_giris_tarihi"

class="form-control">


</div>





<div class="col-md-4 mb-3">


<label>

Telefon

</label>


<input

type="text"

name="telefon"

class="form-control">


</div>


<div class="col-md-6 mb-3">

<label class="form-label">

E-Posta

</label>


<input

type="email"

name="eposta"

class="form-control"

required>


</div>





<div class="col-md-6 mb-3">

<label class="form-label">

Departman

</label>


<select

name="departman_id"

class="form-select"

required>



<option value="">

Departman Seçiniz

</option>



<?php foreach($departmanlar as $d){ ?>


<option value="<?= $d["id"] ?>">


<?= htmlspecialchars($d["department_name"]) ?>


</option>


<?php } ?>



</select>


</div>





<!-- YENİ ROL SEÇİMİ -->


<div class="col-md-6 mb-3">


<label class="form-label">

Sistem Rolü

</label>



<select

name="role_id"

class="form-select"

required>



<option value="">

Rol Seçiniz

</option>




<?php foreach($roller as $r){ ?>


<option value="<?= $r["id"] ?>">


<?= htmlspecialchars($r["role_name"]) ?>


</option>



<?php } ?>



</select>



</div>






<div class="col-md-6 mb-3">


<label class="form-label">

Pozisyon

</label>



<input

type="text"

name="pozisyon"

class="form-control">


</div>





<div class="col-md-6 mb-3">


<label class="form-label">

Yönetici

</label>



<input

type="text"

name="yonetici"

class="form-control">


</div>





<div class="col-md-6 mb-3">


<label class="form-label">

Lokasyon

</label>



<input

type="text"

name="lokasyon"

class="form-control">


</div>





<div class="col-md-6 mb-3">


<label class="form-label">

Eğitim

</label>



<input

type="text"

name="egitim"

class="form-control">


</div>





<div class="col-md-6 mb-3">


<label class="form-label">

Yabancı Dil

</label>



<input

type="text"

name="yabanci_dil"

class="form-control">


</div>





<div class="col-md-12 mb-3">


<label class="form-label">

Açıklama

</label>



<textarea

name="aciklama"

rows="4"

class="form-control"></textarea>



</div>





<div class="col-md-12 mb-4">


<label class="form-label">

Fotoğraf

</label>



<input

type="file"

name="fotograf"

class="form-control"

accept=".jpg,.jpeg,.png">


</div>





<div class="col-md-12 d-flex gap-2">



<button

type="submit"

name="kaydet"

class="btn btn-success">


<i class="bi bi-check-circle"></i>


Personeli Kaydet


</button>




<a

href="employees.php"

class="btn btn-secondary">


<i class="bi bi-arrow-left"></i>


Vazgeç


</a>




</div>



</div>



</form>



</div>



</div>



</div>



</body>



</html>
