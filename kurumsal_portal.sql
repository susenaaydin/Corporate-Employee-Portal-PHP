-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 20 Tem 2026, 13:25:54
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kurumsal_portal`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `sertifika_adi` varchar(100) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `certificates`
--

INSERT INTO `certificates` (`id`, `sertifika_adi`, `aciklama`, `olusturma_tarihi`) VALUES
(1, 'ISO 9001 Kalite Yönetim Sistemi', NULL, '2026-07-14 07:06:52'),
(2, 'ISO 13485 Tıbbi Cihazlar Kalite Yönetim Sistemi', NULL, '2026-07-14 07:06:52'),
(3, 'ISO 14001 Çevre Yönetim Sistemi', '', '2026-07-14 07:06:52'),
(4, 'ISO 45001 İş Sağlığı ve Güvenliği', NULL, '2026-07-14 07:06:52'),
(5, 'ISO 27001 Bilgi Güvenliği Yönetim Sistemi', NULL, '2026-07-14 07:06:52'),
(6, 'ISO 50001 Enerji Yönetim Sistemi', NULL, '2026-07-14 07:06:52'),
(7, 'GMP - İyi Üretim Uygulamaları', NULL, '2026-07-14 07:06:52'),
(8, 'GDP - İyi Dağıtım Uygulamaları', NULL, '2026-07-14 07:06:52'),
(9, 'GLP - İyi Laboratuvar Uygulamaları', NULL, '2026-07-14 07:06:52'),
(10, 'GCP - İyi Klinik Uygulamaları', NULL, '2026-07-14 07:06:52'),
(11, 'CE İşaretleme', '', '2026-07-14 07:06:52'),
(12, 'Medikal Cihaz Yönetmeliği Eğitimi', NULL, '2026-07-14 07:06:52'),
(13, 'Sterilizasyon Eğitimi', NULL, '2026-07-14 07:06:52'),
(14, 'Risk Yönetimi ISO 14971', NULL, '2026-07-14 07:06:52'),
(15, 'İç Denetçi Eğitimi', NULL, '2026-07-14 07:06:52'),
(16, 'Kalibrasyon Eğitimi', NULL, '2026-07-14 07:06:52'),
(17, 'Temel İş Sağlığı ve Güvenliği', NULL, '2026-07-14 07:06:52'),
(18, 'İlk Yardım Sertifikası', NULL, '2026-07-14 07:06:52'),
(19, 'Yangın Güvenliği Eğitimi', NULL, '2026-07-14 07:06:52'),
(20, 'Veri Gizliliği ve KVKK Eğitimi', NULL, '2026-07-14 07:06:52');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `created_at`) VALUES
(1, 'Departman 1', '2026-07-03 11:26:58'),
(2, 'Departman 2', '2026-07-03 11:26:58'),
(3, 'Departman 3', '2026-07-03 11:26:58'),
(4, 'Departman 4', '2026-07-03 11:26:58'),
(5, 'Departman 5', '2026-07-03 11:26:58'),
(6, 'Departman 6', '2026-07-03 11:26:58'),
(7, 'Departman 7', '2026-07-03 11:26:58'),
(8, 'Departman 8', '2026-07-03 11:26:58'),
(9, 'Departman 9', '2026-07-03 11:26:58');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `belge_adi` varchar(100) DEFAULT NULL,
  `dosya` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `personel_no` varchar(20) DEFAULT NULL,
  `ad` varchar(50) DEFAULT NULL,
  `soyad` varchar(50) DEFAULT NULL,
  `cinsiyet` varchar(10) DEFAULT NULL,
  `medeni_durum` varchar(20) DEFAULT NULL,
  `kan_grubu` varchar(5) DEFAULT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `ise_giris_tarihi` date DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `eposta` varchar(100) DEFAULT NULL,
  `departman_id` int(11) DEFAULT NULL,
  `pozisyon` varchar(100) DEFAULT NULL,
  `yonetici` varchar(100) DEFAULT NULL,
  `lokasyon` varchar(100) DEFAULT NULL,
  `egitim` varchar(100) DEFAULT NULL,
  `yabanci_dil` varchar(150) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `fotograf` varchar(255) DEFAULT NULL,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `employees`
--

INSERT INTO `employees` (`id`, `personel_no`, `ad`, `soyad`, `cinsiyet`, `medeni_durum`, `kan_grubu`, `dogum_tarihi`, `ise_giris_tarihi`, `telefon`, `eposta`, `departman_id`, `pozisyon`, `yonetici`, `lokasyon`, `egitim`, `yabanci_dil`, `aciklama`, `fotograf`, `olusturma_tarihi`) VALUES
(1, 'P1001', 'Su', 'Aydın', 'Kadın', 'Bekar', 'B+', '2001-04-11', '2026-07-01', '05555555555', 'su@portal.com', 1, 'Bilgisayar Mühendisi', 'Aydın', 'Antalya', 'Bilgisayar Mühendisliği Lisans', 'İngilizce İspanyolca', '', '', '2026-07-03 13:00:03'),
(3, 'P1002', 'Yusuf', 'M', 'Erkek', 'Bekar', '0+', '2003-06-01', '2022-08-12', '05555555555', 'yusuf@portal.com', 2, 'Elektrik Elektronik Mühendisi', 'Aydın', 'Antalya', 'Elektrik Elektronik Mühendisliği Lisans', 'İngilizce', '', '', '2026-07-13 11:25:39'),
(6, 'P1003', 'Ayşe', 'Demir', 'Kadın', 'Bekar', 'A+', '2000-04-11', '2025-07-01', '5555555555', 'ayse@portal.com', 1, 'Yazılım Uzmanı', 'Ahmet Yılmaz', 'Ankara', 'Bilgisayar Mühendisliği', 'İngilizce', 'Yeni', NULL, '2026-07-14 10:06:53'),
(7, 'P1004', 'Deniz', 'Z', 'Erkek', 'Evli', 'B-', '1999-08-12', '2020-02-05', '5555555555', 'deniz@portal.com', 3, 'Eczacı', 'Ahmet Yılmaz', 'Ankara', 'Eczacılık', 'İngilizce', '', NULL, '2026-07-14 10:06:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `employee_certificates`
--

CREATE TABLE `employee_certificates` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `sertifika_id` int(11) DEFAULT NULL,
  `alinma_tarihi` date DEFAULT NULL,
  `bitis_tarihi` date DEFAULT NULL,
  `dosya` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `employee_certificates`
--

INSERT INTO `employee_certificates` (`id`, `personel_id`, `sertifika_id`, `alinma_tarihi`, `bitis_tarihi`, `dosya`) VALUES
(1, 1, 9, '2026-07-13', '2030-07-13', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`) VALUES
(1, 'Admin', '2026-07-03 10:54:10'),
(2, 'İK', '2026-07-03 10:54:10'),
(3, 'Yönetici', '2026-07-03 10:54:10'),
(4, 'Personel', '2026-07-14 11:10:37');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `employee_id`, `role_id`, `department_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `profile_photo`, `job_title`, `created_at`, `status`) VALUES
(6, 1, 1, 1, 'Su', 'Aydın', 'admin@portal.com', '123456', NULL, NULL, NULL, '2026-07-14 12:10:53', 1),
(7, 3, 2, 2, 'Yusuf', 'M', 'ik@portal.com', '123456', NULL, NULL, NULL, '2026-07-14 12:10:53', 1),
(8, 6, 3, 1, 'Ayşe', 'Demir', 'yonetici@portal.com', '123456', NULL, NULL, NULL, '2026-07-14 12:10:53', 1),
(9, 7, 4, 3, 'Deniz', 'Z', 'personel@portal.com', '123456', '', NULL, NULL, '2026-07-14 12:10:53', 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `employee_certificates`
--
ALTER TABLE `employee_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `fk_user_employee` (`employee_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `employee_certificates`
--
ALTER TABLE `employee_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
