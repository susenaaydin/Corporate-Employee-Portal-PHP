<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Giriş Kontrolü
|--------------------------------------------------------------------------
*/

function girisKontrol()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Rol Kontrolleri
|--------------------------------------------------------------------------
*/

function adminMi()
{
    return isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 1;
}

function ikMi()
{
    return isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 2;
}

function yoneticiMi()
{
    return isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 3;
}

function personelMi()
{
    return isset($_SESSION["role_id"]) && $_SESSION["role_id"] == 4;
}

/*
|--------------------------------------------------------------------------
| Rol Adını Getir
|--------------------------------------------------------------------------
*/

function kullaniciRol()
{
    if (adminMi()) {
        return "Admin";
    }

    if (ikMi()) {
        return "İK";
    }

    if (yoneticiMi()) {
        return "Yönetici";
    }

    if (personelMi()) {
        return "Personel";
    }

    return "Tanımsız";
}

/*
|--------------------------------------------------------------------------
| Yetkiler
|--------------------------------------------------------------------------
*/

function kullaniciYonetebilirMi()
{
    return adminMi();
}

function personelEkleyebilirMi()
{
    return adminMi() || ikMi();
}

function personelDuzenleyebilirMi()
{
    return adminMi() || ikMi();
}

function personelSilebilirMi()
{
    return adminMi();
}

function departmanYonetebilirMi()
{
    return adminMi();
}

function sertifikaYonetebilirMi()
{
    return adminMi() || ikMi();
}

function belgeYonetebilirMi()
{
    return adminMi() || ikMi();
}

function excelAktarabilirMi()
{
    return adminMi();
}

function raporGorebilirMi()
{
    return adminMi() || ikMi() || yoneticiMi();
}