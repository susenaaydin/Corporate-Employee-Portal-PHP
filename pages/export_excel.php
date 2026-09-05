<?php
require_once "../config/database.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=personeller.xls");

$sql = "
SELECT employees.*, departments.department_name
FROM employees
LEFT JOIN departments ON employees.departman_id = departments.id
";

$stmt = $pdo->query($sql);
$personeller = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "ID\tAd\tSoyad\tNo\tDepartman\tPozisyon\tTelefon\n";

foreach ($personeller as $p) {
    echo $p["id"] . "\t";
    echo $p["ad"] . "\t";
    echo $p["soyad"] . "\t";
    echo $p["personel_no"] . "\t";
    echo $p["department_name"] . "\t";
    echo $p["pozisyon"] . "\t";
    echo $p["telefon"] . "\n";
}
?>