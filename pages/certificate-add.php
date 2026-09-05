<?php

session_start();


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}


require_once "../config/database.php";



/*
KULLANICI ROLÜNÜ KONTROL ET
*/


$rolSorgu = $pdo->prepare("

SELECT 

roles.role_name

FROM users

LEFT JOIN roles

ON users.role_id = roles.id

WHERE users.id = ?

");


$rolSorgu->execute([

$_SESSION["user_id"]

]);


$rol = $rolSorgu->fetchColumn();





/*
SADECE ADMIN VE İK GİREBİLİR
*/


if($rol != "Admin" && $rol != "İK"){


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





if(isset($_POST["kaydet"])){


    $sertifika_adi = trim($_POST["sertifika_adi"]);

    $aciklama = trim($_POST["aciklama"]);




    if($sertifika_adi==""){


        $hata="Sertifika adı boş bırakılamaz.";



    }else{



        $kontrol=$pdo->prepare("

        SELECT COUNT(*)

        FROM certificates

        WHERE sertifika_adi=?

        ");



        $kontrol->execute([

            $sertifika_adi

        ]);




        if($kontrol->fetchColumn()>0){


            $hata="Bu sertifika zaten mevcut.";



        }else{



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




            header("Location: certificates.php");

            exit;



        }


    }


}


?>



<!DOCTYPE html>

<html lang="tr">


<head>


<meta charset="UTF-8">


<title>Yeni Sertifika</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">



</head>




<body class="bg-light">



<div class="container mt-5">



<div class="card shadow">



<div class="card-header bg-success text-white">



<h3>

📜 Yeni Sertifika

</h3>



</div>




<div class="card-body">





<?php if(isset($hata)){ ?>


<div class="alert alert-danger">

<?= htmlspecialchars($hata) ?>

</div>


<?php } ?>






<form method="POST">





<div class="mb-3">


<label class="form-label">

Sertifika Adı

</label>



<input

type="text"

name="sertifika_adi"

class="form-control"

required>



</div>







<div class="mb-3">


<label class="form-label">

Açıklama

</label>



<textarea

name="aciklama"

class="form-control"

rows="4"></textarea>



</div>







<button

type="submit"

name="kaydet"

class="btn btn-success">


💾 Kaydet


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