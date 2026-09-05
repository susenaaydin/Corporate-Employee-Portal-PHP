<?php

global $pdo;

$rol = "";

if(isset($_SESSION["user_id"])){

    $sorgu = $pdo->prepare("

    SELECT roles.role_name

    FROM users

    LEFT JOIN roles

    ON users.role_id = roles.id

    WHERE users.id = ?

    ");

    $sorgu->execute([
        $_SESSION["user_id"]
    ]);

    $rol = $sorgu->fetchColumn();

}

?>


<style>

.sidebar{

position:fixed;

left:0;

top:0;

width:250px;

height:100vh;

background:#111827;

padding:20px;

color:white;

overflow-y:auto;

}


.sidebar a{

display:block;

color:white;

text-decoration:none;

padding:12px;

border-radius:10px;

margin-bottom:8px;

transition:.2s;

}


.sidebar a:hover{

background:#2563eb;

}


.logo{

font-size:22px;

font-weight:bold;

margin-bottom:30px;

}


.sidebar h6{

color:#9ca3af;

margin-top:20px;

margin-bottom:10px;

}

</style>



<div class="sidebar">


<div class="logo">

🏢 Kurumsal Portal

</div>



<!-- HERKES GÖREBİLİR -->

<a href="/KurumsalPortal/pages/dashboard.php">

🏠 Dashboard

</a>


<a href="/KurumsalPortal/pages/employees.php">

👥 Personeller

</a>


<a href="/KurumsalPortal/pages/search.php">

🔍 Personel Ara

</a>



<!-- ADMIN -->

<?php if($rol=="Admin"){ ?>


<hr>


<h6>

⚙ Yönetim

</h6>


<a href="/KurumsalPortal/pages/employee-add.php">

➕ Personel Ekle

</a>


<a href="/KurumsalPortal/pages/personel-import.php">

📥 Excel Aktarım

</a>


<a href="/KurumsalPortal/pages/certificates.php">

🏆 Sertifika Yönetimi

</a>


<a href="/KurumsalPortal/admin/users.php">

👤 Kullanıcı Yönetimi

</a>


<a href="/KurumsalPortal/pages/departments.php">

🏢 Departman Yönetimi

</a>


<?php } ?>



<!-- İK -->

<?php if($rol=="İK"){ ?>


<hr>


<h6>

👩‍💼 İnsan Kaynakları

</h6>


<a href="/KurumsalPortal/pages/employee-add.php">

➕ Personel Ekle

</a>


<a href="/KurumsalPortal/pages/personel-import.php">

📥 Excel Aktarım

</a>


<a href="/KurumsalPortal/pages/certificates.php">

🏆 Sertifikalar

</a>


<?php } ?>



<!-- YÖNETİCİ -->

<?php if($rol=="Yönetici"){ ?>


<hr>


<h6>

👔 Yönetici

</h6>


<a href="/KurumsalPortal/pages/my-department.php">

🏢 Departmanım

</a>


<?php } ?>



<!-- PERSONEL -->

<?php if($rol=="Personel"){ ?>


<hr>


<h6>

👤 Personel

</h6>


<a href="/KurumsalPortal/pages/profile.php">

📄 Profilim

</a>


<?php } ?>



<hr>


<a href="/KurumsalPortal/pages/logout.php" class="text-danger">

🚪 Çıkış

</a>



</div>