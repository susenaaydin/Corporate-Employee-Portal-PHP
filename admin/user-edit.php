<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

require_once "../config/database.php";
require_once "../includes/auth.php";

adminKontrol();


$id=$_GET["id"] ?? 0;



$kullanici=$pdo->prepare("
SELECT *
FROM users
WHERE id=?
");

$kullanici->execute([$id]);

$user=$kullanici->fetch(PDO::FETCH_ASSOC);



if(!$user){

    die("Kullanıcı bulunamadı.");

}



$roller=$pdo->query("
SELECT * FROM roles
")->fetchAll(PDO::FETCH_ASSOC);



$departmanlar=$pdo->query("
SELECT * FROM departments
")->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST["kaydet"])){


$guncelle=$pdo->prepare("

UPDATE users SET

role_id=?,
department_id=?,
first_name=?,
last_name=?,
email=?,
phone=?,
job_title=?

WHERE id=?

");


$guncelle->execute([

$_POST["role_id"],
$_POST["department_id"],
$_POST["first_name"],
$_POST["last_name"],
$_POST["email"],
$_POST["phone"],
$_POST["job_title"],
$id

]);


header("Location: users.php");
exit;


}
<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Kullanıcı Düzenle</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


<div class="container mt-5">


<div class="card shadow">


<div class="card-header">

<h4>
✏ Kullanıcı Düzenle
</h4>

</div>


<div class="card-body">


<form method="POST">


<div class="row">


<div class="col-md-6 mb-3">

<label>Ad</label>

<input 
type="text"
name="first_name"
class="form-control"
value="<?= htmlspecialchars($user["first_name"]) ?>"
required>

</div>



<div class="col-md-6 mb-3">

<label>Soyad</label>

<input 
type="text"
name="last_name"
class="form-control"
value="<?= htmlspecialchars($user["last_name"]) ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label>E-Posta</label>

<input 
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($user["email"]) ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label>Telefon</label>

<input 
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($user["phone"]) ?>">

</div>




<div class="col-md-6 mb-3">

<label>Pozisyon</label>

<input 
type="text"
name="job_title"
class="form-control"
value="<?= htmlspecialchars($user["job_title"]) ?>">

</div>





<div class="col-md-6 mb-3">

<label>Rol</label>


<select name="role_id" class="form-select">


<?php foreach($roller as $r){ ?>


<option 
value="<?= $r["id"] ?>"
<?= $r["id"]==$user["role_id"] ? "selected":"" ?>
>

<?= htmlspecialchars($r["role_name"]) ?>

</option>


<?php } ?>


</select>


</div>





<div class="col-md-6 mb-3">

<label>Departman</label>


<select name="department_id" class="form-select">


<?php foreach($departmanlar as $d){ ?>


<option 

value="<?= $d["id"] ?>"

<?= $d["id"]==$user["department_id"] ? "selected":"" ?>

>


<?= htmlspecialchars($d["department_name"]) ?>


</option>


<?php } ?>


</select>


</div>



</div>




<button 
class="btn btn-success"
name="kaydet">

💾 Kaydet

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

?>