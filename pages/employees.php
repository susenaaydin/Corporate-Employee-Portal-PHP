<?php

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

girisKontrol();


$duzenlemeYetkisi = personelDuzenleyebilirMi();
$silmeYetkisi = personelSilebilirMi();



/*
 Kullanıcı rol bilgisi
*/

$sorgu = $pdo->prepare("

SELECT 

users.role_id,
users.department_id,
roles.role_name

FROM users

LEFT JOIN roles

ON users.role_id = roles.id

WHERE users.id = ?

");


$sorgu->execute([

$_SESSION["user_id"]

]);


$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);



$rol = $kullanici["role_name"] ?? "";





/*
 TÜM ROLLER PERSONELLERİ GÖREBİLİR
*/


$personeller = $pdo->query("

SELECT

employees.*,

departments.department_name

FROM employees

LEFT JOIN departments

ON employees.departman_id = departments.id

ORDER BY employees.id DESC


")->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Personeller</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


</head>



<body class="bg-light">



<div class="container mt-5">



<div class="card shadow">



<div class="card-header">


<h4>

👥 Personel Listesi

</h4>


</div>



<div class="card-body">



<table class="table table-hover">



<thead>


<tr>

<th>ID</th>

<th>Personel No</th>

<th>Ad</th>

<th>Soyad</th>

<th>Departman</th>

<th>Pozisyon</th>

<th>İşlem</th>


</tr>


</thead>



<tbody>



<?php foreach($personeller as $p){ ?>



<tr>



<td>

<?= $p["id"] ?>

</td>



<td>

<?= htmlspecialchars($p["personel_no"]) ?>

</td>



<td>

<?= htmlspecialchars($p["ad"]) ?>

</td>



<td>

<?= htmlspecialchars($p["soyad"]) ?>

</td>



<td>

<?= htmlspecialchars($p["department_name"] ?? "-") ?>

</td>



<td>

<?= htmlspecialchars($p["pozisyon"]) ?>

</td>



<td>


<a href="employee-detail.php?id=<?= $p["id"] ?>" 

class="btn btn-primary btn-sm">

👁 Görüntüle

</a>




<?php if($duzenlemeYetkisi){ ?>


<a href="employee-edit.php?id=<?= $p["id"] ?>" 

class="btn btn-warning btn-sm">

✏ Düzenle

</a>


<?php } ?>





<?php if($silmeYetkisi){ ?>


<a href="employee-delete.php?id=<?= $p["id"] ?>"


class="btn btn-danger btn-sm"

onclick="return confirm('Bu personel silinsin mi?')">


🗑 Sil


</a>



<?php } ?>



</td>



</tr>



<?php } ?>



</tbody>



</table>



</div>



</div>



</div>



</body>


</html>