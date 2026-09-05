<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();

if(!adminMi() && !ikMi()){
    die("Bu sayfaya erişim yetkiniz yok.");
}



$mesaj = "";
$hata = "";

$atlanalar = [];


if(isset($_POST["aktar"])) {


    if(isset($_FILES["csv"]) && $_FILES["csv"]["error"] == 0) {


        $dosya = $_FILES["csv"]["tmp_name"];


        $handle = fopen($dosya, "r");


        if($handle){


            $basarili = 0;
            $hatali = 0;
            $atlanan = 0;



            // Başlık satırı atla

            fgetcsv($handle, 1000, ";");



            while(($row = fgetcsv($handle, 1000, ";")) !== false){


                try {



                    $personel_no = trim($row[0] ?? "");
                    $ad = trim($row[1] ?? "");
                    $soyad = trim($row[2] ?? "");
                    $cinsiyet = trim($row[3] ?? "");
                    $medeni = trim($row[4] ?? "");
                    $kan = trim($row[5] ?? "");

                    $dogum = trim($row[6] ?? "");
                    $ise_giris = trim($row[7] ?? "");

                    $telefon = trim($row[8] ?? "");
                    $eposta = trim($row[9] ?? "");

                    $departman = trim($row[10] ?? "");

                    $pozisyon = trim($row[11] ?? "");
                    $yonetici = trim($row[12] ?? "");
                    $lokasyon = trim($row[13] ?? "");
                    $egitim = trim($row[14] ?? "");
                    $yabanci = trim($row[15] ?? "");
                    $aciklama = trim($row[16] ?? "");





                    // zorunlu alan

                    if(
                        empty($personel_no) ||
                        empty($ad) ||
                        empty($soyad)
                    ){

                        $hatali++;

                        continue;

                    }






                    // aynı personel kontrolü


                    $kontrol = $pdo->prepare("
                    SELECT id 
                    FROM employees
                    WHERE personel_no = ?
                    ");


                    $kontrol->execute([$personel_no]);



                    if($kontrol->fetch()){


                        $atlanan++;


                        $atlanalar[] =
                        $personel_no." - ".$ad." ".$soyad;


                        continue;


                    }







                    // departman bul


                    $dep = $pdo->prepare("
                    SELECT id
                    FROM departments
                    WHERE department_name = ?
                    ");



                    $dep->execute([$departman]);



                    $departman_id = $dep->fetchColumn();



                    if(!$departman_id){


                        $hatali++;

                        continue;


                    }









                    // tarih formatı düzeltme

                    function tarihCevir($tarih){


                        if(empty($tarih)){

                            return null;

                        }


                        $parca = explode(".", $tarih);



                        if(count($parca)==3){

                            return $parca[2]."-".$parca[1]."-".$parca[0];

                        }


                        return null;


                    }





                    $dogum_tarihi = tarihCevir($dogum);

                    $ise_tarihi = tarihCevir($ise_giris);









                    $ekle = $pdo->prepare("

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
                    aciklama

                    )

                    VALUES

                    (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)

                    ");







                    $ekle->execute([


                    $personel_no,
                    $ad,
                    $soyad,
                    $cinsiyet,
                    $medeni,
                    $kan,
                    $dogum_tarihi,
                    $ise_tarihi,
                    $telefon,
                    $eposta,
                    $departman_id,
                    $pozisyon,
                    $yonetici,
                    $lokasyon,
                    $egitim,
                    $yabanci,
                    $aciklama


                    ]);




                    $basarili++;




                }


                catch(PDOException $e){


                    $hatali++;


                    echo "

                    <div class='alert alert-danger'>
                    Hata:
                    ".$e->getMessage()."
                    </div>

                    ";


                }



            }



            fclose($handle);





            $mesaj = "

            Aktarım tamamlandı.
            <br>
            ✅ Başarılı: ".$basarili."
            <br>
            ⚠️ Atlanan: ".$atlanan."
            <br>
            ❌ Hatalı: ".$hatali."

            ";



        }



    }

    else{


        $hata = "CSV dosyası seçilmedi.";


    }


}



?>



<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Personel CSV Aktarım</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body class="bg-light">



<div class="container mt-5">



<div class="card shadow">


<div class="card-header">

<h4>
📊 Personel CSV Aktarım
</h4>


</div>



<div class="card-body">



<?php if($mesaj){ ?>

<div class="alert alert-success">

<?= $mesaj ?>

</div>


<?php } ?>





<?php if(count($atlanalar)>0){ ?>


<div class="alert alert-warning">


<b>Atlanan Personeller</b>


<ul>


<?php foreach($atlanalar as $a){ ?>


<li>
<?= htmlspecialchars($a) ?>
</li>


<?php } ?>


</ul>


</div>


<?php } ?>






<?php if($hata){ ?>


<div class="alert alert-danger">

<?= $hata ?>

</div>


<?php } ?>






<form method="POST" enctype="multipart/form-data">


<div class="mb-3">


<label class="form-label">

CSV Dosyası

</label>


<input 

type="file"

name="csv"

class="form-control"

accept=".csv"

required>


</div>





<button 
class="btn btn-success"
name="aktar">

📥 Aktar

</button>



<a href="dashboard.php" class="btn btn-secondary">

Geri Dön

</a>



</form>





<hr>



<h5>CSV Sütun Sırası</h5>


<p>

Personel No,
Ad,
Soyad,
Cinsiyet,
Medeni Durum,
Kan Grubu,
Doğum Tarihi,
İşe Giriş Tarihi,
Telefon,
E-Posta,
Departman,
Pozisyon,
Yönetici,
Lokasyon,
Eğitim,
Yabancı Dil,
Açıklama

</p>




</div>


</div>


</div>



</body>

</html>