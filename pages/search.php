<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$ad = $_GET["ad"] ?? "";
$soyad = $_GET["soyad"] ?? "";
$personel_no = $_GET["personel_no"] ?? "";
$cinsiyet = $_GET["cinsiyet"] ?? "";
$departman = $_GET["departman"] ?? "";
$pozisyon = $_GET["pozisyon"] ?? "";
$yas = $_GET["yas"] ?? "";
$sertifika = $_GET["sertifika"] ?? "";

/* DEPARTMANLAR */
$departmanlar = $pdo->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);

/* SORGUNUN TEMELİ */
$sql = "
SELECT employees.*, departments.department_name
FROM employees
LEFT JOIN departments ON employees.departman_id = departments.id
LEFT JOIN employee_certificates ec ON employees.id = ec.personel_id
LEFT JOIN certificates c ON ec.sertifika_id = c.id
WHERE 1=1
";

$params = [];

/* FİLTRELER */
if ($ad != "") {
    $sql .= " AND employees.ad LIKE ?";
    $params[] = "%$ad%";
}

if ($soyad != "") {
    $sql .= " AND employees.soyad LIKE ?";
    $params[] = "%$soyad%";
}

if ($personel_no != "") {
    $sql .= " AND employees.personel_no LIKE ?";
    $params[] = "%$personel_no%";
}

if ($cinsiyet != "") {
    $sql .= " AND employees.cinsiyet = ?";
    $params[] = $cinsiyet;
}

if ($departman != "") {
    $sql .= " AND employees.departman_id = ?";
    $params[] = $departman;
}

if ($pozisyon != "") {
    $sql .= " AND employees.pozisyon LIKE ?";
    $params[] = "%$pozisyon%";
}

/* YAŞ ARALIĞI */
if ($yas != "") {
    if ($yas == "18-25") {
        $sql .= " AND TIMESTAMPDIFF(YEAR, employees.dogum_tarihi, CURDATE()) BETWEEN 18 AND 25";
    } elseif ($yas == "26-35") {
        $sql .= " AND TIMESTAMPDIFF(YEAR, employees.dogum_tarihi, CURDATE()) BETWEEN 26 AND 35";
    } elseif ($yas == "36-45") {
        $sql .= " AND TIMESTAMPDIFF(YEAR, employees.dogum_tarihi, CURDATE()) BETWEEN 36 AND 45";
    } elseif ($yas == "46-55") {
        $sql .= " AND TIMESTAMPDIFF(YEAR, employees.dogum_tarihi, CURDATE()) BETWEEN 46 AND 55";
    } elseif ($yas == "56+") {
        $sql .= " AND TIMESTAMPDIFF(YEAR, employees.dogum_tarihi, CURDATE()) >= 56";
    }
}

/* SERTİFİKA */
if ($sertifika != "") {
    $sql .= " AND c.sertifika_adi LIKE ?";
    $params[] = "%$sertifika%";
}

$sql .= " GROUP BY employees.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$personeller = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Personel Arama</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h2>🔍 Gelişmiş Personel Arama</h2>

<form method="GET" class="card p-3 mb-4">

<div class="row g-2">

<div class="col-md-2">
<input type="text" name="ad" class="form-control" placeholder="Ad" value="<?= $ad ?>">
</div>

<div class="col-md-2">
<input type="text" name="soyad" class="form-control" placeholder="Soyad" value="<?= $soyad ?>">
</div>

<div class="col-md-2">
<input type="text" name="personel_no" class="form-control" placeholder="No" value="<?= $personel_no ?>">
</div>

<div class="col-md-2">
<select name="cinsiyet" class="form-control">
<option value="">Cinsiyet</option>
<option value="Kadın" <?= $cinsiyet=="Kadın"?"selected":"" ?>>Kadın</option>
<option value="Erkek" <?= $cinsiyet=="Erkek"?"selected":"" ?>>Erkek</option>
</select>
</div>

<div class="col-md-2">
<select name="departman" class="form-control">
<option value="">Departman</option>
<?php foreach($departmanlar as $d){ ?>
<option value="<?= $d["id"] ?>" <?= $departman==$d["id"]?"selected":"" ?>>
<?= $d["department_name"] ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-2">
<select name="yas" class="form-control">
<option value="">Yaş</option>
<option value="18-25">18-25</option>
<option value="26-35">26-35</option>
<option value="36-45">36-45</option>
<option value="46-55">46-55</option>
<option value="56+">56+</option>
</select>
</div>

<div class="col-md-3 mt-2">
<input type="text" name="pozisyon" class="form-control" placeholder="Pozisyon" value="<?= $pozisyon ?>">
</div>

<div class="col-md-3 mt-2">
<input type="text" name="sertifika" class="form-control" placeholder="Sertifika" value="<?= $sertifika ?>">
</div>

<div class="col-md-6 mt-2">
<button class="btn btn-primary w-100">Ara</button>
</div>

</div>

</form>

<table class="table table-bordered table-hover bg-white">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Ad</th>
<th>Soyad</th>
<th>No</th>
<th>Departman</th>
<th>Pozisyon</th>
<th>Detay</th>
</tr>
</thead>

<tbody>

<?php foreach($personeller as $p){ ?>

<tr>
<td><?= $p["id"] ?></td>
<td><?= $p["ad"] ?></td>
<td><?= $p["soyad"] ?></td>
<td><?= $p["personel_no"] ?></td>
<td><?= $p["department_name"] ?></td>
<td><?= $p["pozisyon"] ?></td>
<td>
<a href="employee-detail.php?id=<?= $p["id"] ?>" class="btn btn-success btn-sm">
Detay
</a>
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>

<?php
