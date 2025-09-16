-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Waktu pembuatan: 16 Sep 2025 pada 19.55
-- Versi server: 8.0.35
-- Versi PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_vsm_anime`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anime`
--

CREATE TABLE `anime` (
  `id_anime` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `sinopsis` text,
  `genre` varchar(255) DEFAULT NULL COMMENT 'Genre bisa lebih dari satu, dipisahkan koma',
  `gambar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `anime`
--

INSERT INTO `anime` (`id_anime`, `judul`, `sinopsis`, `genre`, `gambar_url`, `created_at`) VALUES
(7, 'Jujutsu Kaisen', 'Penderitaan, penyesalan, dan rasa malu adalah perasaan negatif yang menjadi sumber Kutukan yang menghantui kehidupan manusia. Yuji Itadori adalah seorang siswa SMA dengan kekuatan fisik luar biasa. Suatu hari, untuk menyelamatkan temannya yang diserang Kutukan, ia menelan jari dari iblis kuat Ryomen Sukuna dan menjadi wadahnya. Yuji kemudian bergabung dengan sekolah penyihir Jujutsu untuk belajar mengendalikan kekuatannya dan melawan Kutukan.', 'Action,Demons,Supernatural,School,Shounen', '68c9b8c0daafb_jujutsu_kaisen_anime.jpg_514966513.jpg', '2025-09-16 19:23:28'),
(8, 'One Punch Man', 'Saitama adalah seorang pahlawan yang bisa mengalahkan musuh mana pun hanya dengan satu pukulan. Namun, karena kekuatannya yang luar biasa, ia menjadi bosan dan frustrasi karena tidak ada lagi tantangan dalam pertarungannya. Serial ini mengikuti kehidupan sehari-harinya yang lucu saat ia mencari lawan yang sepadan dan mencoba menemukan kembali sensasi dari sebuah pertarungan sengit.', 'Action,Sci-Fi,Comedy,Parody,Super Power,Supernatural', '68c9b8a340262_661649.png', '2025-09-16 19:23:28'),
(9, 'Violet Evergarden', 'Setelah perang besar berakhir, Violet Evergarden, seorang prajurit muda yang dibesarkan hanya untuk berperang, harus beradaptasi dengan kehidupan normal. Ia mulai bekerja sebagai \"Auto Memory Doll\", seorang penulis surat yang membantu orang lain mengungkapkan perasaan mereka. Melalui pekerjaannya, Violet memulai perjalanan untuk memahami emosi manusia dan arti dari kata-kata terakhir yang diucapkan oleh orang yang paling ia sayangi.', 'Slice of Life,Drama,Fantasy', '68c9b87e0e947_04d4e320d7a20603017b44ab89e3d080.jpg', '2025-09-16 19:23:28'),
(10, 'Code Geass: Lelouch of the Rebellion', 'Di dunia alternatif, Kekaisaran Suci Britannia telah menaklukkan Jepang, yang sekarang dikenal sebagai Area 11. Lelouch, seorang pangeran Britannia yang diasingkan, secara tidak sengaja mendapatkan kekuatan misterius bernama \"Geass\" yang memungkinkannya untuk memerintahkan siapa pun. Dengan kekuatan ini, ia memulai pemberontakan epik melawan Kekaisaran di bawah identitas rahasia \"Zero\" untuk menciptakan dunia baru bagi adiknya.', 'Action,Military,Sci-Fi,Super Power,Drama,Mecha,School', '68c9b85d0945a_analise-steins-gate-cover-1.jpg', '2025-09-16 19:23:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_tf_idf`
--

CREATE TABLE `bobot_tf_idf` (
  `id_anime` int NOT NULL,
  `id_kata` int NOT NULL,
  `bobot` decimal(10,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `bobot_tf_idf`
--

INSERT INTO `bobot_tf_idf` (`id_anime`, `id_kata`, `bobot`) VALUES
(7, 1, 0.60205999),
(7, 2, 0.60205999),
(7, 3, 0.60205999),
(7, 4, 0.30103000),
(7, 5, 0.60205999),
(7, 6, 0.60205999),
(7, 7, 1.80617997),
(7, 8, 0.60205999),
(7, 9, 0.12493874),
(7, 10, 0.30103000),
(7, 11, 1.20411998),
(7, 12, 0.60205999),
(7, 13, 0.60205999),
(7, 14, 0.60205999),
(7, 15, 0.30103000),
(7, 16, 0.60205999),
(7, 17, 0.60205999),
(7, 18, 0.60205999),
(7, 19, 0.60205999),
(7, 20, 0.60205999),
(7, 21, 0.60205999),
(7, 22, 0.60205999),
(7, 23, 0.60205999),
(7, 24, 0.60205999),
(7, 25, 0.60205999),
(7, 26, 0.60205999),
(7, 27, 0.60205999),
(7, 28, 0.60205999),
(7, 29, 0.60205999),
(7, 30, 0.60205999),
(7, 31, 0.60205999),
(7, 32, 0.60205999),
(7, 33, 0.30103000),
(7, 34, 0.30103000),
(8, 9, 0.12493874),
(8, 33, 0.30103000),
(8, 35, 0.60205999),
(8, 36, 0.60205999),
(8, 37, 0.60205999),
(8, 38, 0.60205999),
(8, 39, 0.60205999),
(8, 40, 0.60205999),
(8, 41, 0.60205999),
(8, 42, 0.60205999),
(8, 43, 0.60205999),
(8, 44, 0.60205999),
(8, 45, 0.60205999),
(8, 46, 0.60205999),
(8, 47, 0.60205999),
(8, 48, 0.60205999),
(8, 49, 0.60205999),
(8, 50, 0.60205999),
(8, 51, 0.60205999),
(8, 52, 0.60205999),
(8, 53, 0.60205999),
(8, 54, 0.60205999),
(8, 55, 0.60205999),
(8, 56, 0.60205999),
(8, 57, 0.60205999),
(9, 4, 0.30103000),
(9, 9, 0.12493874),
(9, 10, 0.30103000),
(9, 58, 0.60205999),
(9, 59, 0.60205999),
(9, 60, 1.20411998),
(9, 61, 0.60205999),
(9, 62, 0.60205999),
(9, 63, 0.60205999),
(9, 64, 0.60205999),
(9, 65, 0.60205999),
(9, 66, 0.60205999),
(9, 67, 0.60205999),
(9, 68, 0.60205999),
(9, 69, 0.60205999),
(9, 70, 0.60205999),
(9, 71, 0.60205999),
(9, 72, 0.60205999),
(9, 73, 0.60205999),
(9, 74, 0.60205999),
(9, 75, 1.20411998),
(9, 76, 0.60205999),
(9, 77, 0.60205999),
(9, 78, 0.60205999),
(9, 79, 0.60205999),
(9, 80, 0.60205999),
(9, 81, 0.60205999),
(9, 82, 0.60205999),
(10, 15, 0.60206000),
(10, 34, 0.30103000),
(10, 83, 1.20411998),
(10, 84, 0.60205999),
(10, 85, 1.20411998),
(10, 86, 0.60205999),
(10, 87, 1.20411998),
(10, 88, 0.60205999),
(10, 89, 0.60205999),
(10, 90, 0.60205999),
(10, 91, 0.60205999),
(10, 92, 0.60205999),
(10, 93, 0.60205999),
(10, 94, 0.60205999),
(10, 95, 0.60205999),
(10, 96, 0.60205999),
(10, 97, 0.60205999),
(10, 98, 0.60205999),
(10, 99, 0.60205999),
(10, 100, 0.60205999),
(10, 101, 0.60205999),
(10, 102, 0.60205999),
(10, 103, 0.60205999),
(10, 104, 0.60205999),
(10, 105, 0.60205999),
(10, 106, 0.60205999),
(10, 107, 0.60205999),
(10, 108, 0.60205999),
(10, 109, 0.60205999),
(10, 110, 0.60205999),
(10, 111, 0.60205999);

-- --------------------------------------------------------

--
-- Struktur dari tabel `document_frequency`
--

CREATE TABLE `document_frequency` (
  `id_kata` int NOT NULL,
  `jumlah_dokumen` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `document_frequency`
--

INSERT INTO `document_frequency` (`id_kata`, `jumlah_dokumen`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 3),
(10, 2),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 2),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 2),
(34, 2),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `inverse_document_frequency`
--

CREATE TABLE `inverse_document_frequency` (
  `id_kata` int NOT NULL,
  `nilai_idf` decimal(10,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `inverse_document_frequency`
--

INSERT INTO `inverse_document_frequency` (`id_kata`, `nilai_idf`) VALUES
(1, 0.60205999),
(2, 0.60205999),
(3, 0.60205999),
(4, 0.30103000),
(5, 0.60205999),
(6, 0.60205999),
(7, 0.60205999),
(8, 0.60205999),
(9, 0.12493874),
(10, 0.30103000),
(11, 0.60205999),
(12, 0.60205999),
(13, 0.60205999),
(14, 0.60205999),
(15, 0.30103000),
(16, 0.60205999),
(17, 0.60205999),
(18, 0.60205999),
(19, 0.60205999),
(20, 0.60205999),
(21, 0.60205999),
(22, 0.60205999),
(23, 0.60205999),
(24, 0.60205999),
(25, 0.60205999),
(26, 0.60205999),
(27, 0.60205999),
(28, 0.60205999),
(29, 0.60205999),
(30, 0.60205999),
(31, 0.60205999),
(32, 0.60205999),
(33, 0.30103000),
(34, 0.30103000),
(35, 0.60205999),
(36, 0.60205999),
(37, 0.60205999),
(38, 0.60205999),
(39, 0.60205999),
(40, 0.60205999),
(41, 0.60205999),
(42, 0.60205999),
(43, 0.60205999),
(44, 0.60205999),
(45, 0.60205999),
(46, 0.60205999),
(47, 0.60205999),
(48, 0.60205999),
(49, 0.60205999),
(50, 0.60205999),
(51, 0.60205999),
(52, 0.60205999),
(53, 0.60205999),
(54, 0.60205999),
(55, 0.60205999),
(56, 0.60205999),
(57, 0.60205999),
(58, 0.60205999),
(59, 0.60205999),
(60, 0.60205999),
(61, 0.60205999),
(62, 0.60205999),
(63, 0.60205999),
(64, 0.60205999),
(65, 0.60205999),
(66, 0.60205999),
(67, 0.60205999),
(68, 0.60205999),
(69, 0.60205999),
(70, 0.60205999),
(71, 0.60205999),
(72, 0.60205999),
(73, 0.60205999),
(74, 0.60205999),
(75, 0.60205999),
(76, 0.60205999),
(77, 0.60205999),
(78, 0.60205999),
(79, 0.60205999),
(80, 0.60205999),
(81, 0.60205999),
(82, 0.60205999),
(83, 0.60205999),
(84, 0.60205999),
(85, 0.60205999),
(86, 0.60205999),
(87, 0.60205999),
(88, 0.60205999),
(89, 0.60205999),
(90, 0.60205999),
(91, 0.60205999),
(92, 0.60205999),
(93, 0.60205999),
(94, 0.60205999),
(95, 0.60205999),
(96, 0.60205999),
(97, 0.60205999),
(98, 0.60205999),
(99, 0.60205999),
(100, 0.60205999),
(101, 0.60205999),
(102, 0.60205999),
(103, 0.60205999),
(104, 0.60205999),
(105, 0.60205999),
(106, 0.60205999),
(107, 0.60205999),
(108, 0.60205999),
(109, 0.60205999),
(110, 0.60205999),
(111, 0.60205999);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamus_kata`
--

CREATE TABLE `kamus_kata` (
  `id_kata` int NOT NULL,
  `kata` varchar(100) NOT NULL COMMENT 'Kata unik hasil tokenisasi, stemming, dan stopword removal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kamus_kata`
--

INSERT INTO `kamus_kata` (`id_kata`, `kata`) VALUES
(92, '11'),
(42, 'ada'),
(111, 'adiknya'),
(84, 'alternatif'),
(91, 'area'),
(81, 'arti'),
(69, 'auto'),
(110, 'baru'),
(105, 'bawah'),
(68, 'bekerja'),
(31, 'belajar'),
(66, 'beradaptasi'),
(59, 'berakhir'),
(27, 'bergabung'),
(99, 'bernama'),
(65, 'berperang'),
(40, 'bosan'),
(87, 'britannia'),
(95, 'diasingkan'),
(64, 'dibesarkan'),
(90, 'dikenal'),
(19, 'diserang'),
(71, 'doll'),
(83, 'dunia'),
(80, 'emosi'),
(104, 'epik'),
(61, 'evergarden'),
(16, 'fisik'),
(41, 'frustrasi'),
(100, 'geass'),
(48, 'harinya'),
(22, 'iblis'),
(106, 'identitas'),
(12, 'itadori'),
(21, 'jari'),
(89, 'jepang'),
(30, 'jujutsu'),
(9, 'kehidupan'),
(85, 'kekaisaran'),
(15, 'kekuatan'),
(33, 'kekuatannya'),
(23, 'kuat'),
(7, 'kutukan'),
(51, 'lawan'),
(93, 'lelouch'),
(49, 'lucu'),
(3, 'malu'),
(10, 'manusia'),
(34, 'melawan'),
(79, 'memahami'),
(74, 'membantu'),
(102, 'memerintahkan'),
(70, 'memory'),
(101, 'memungkinkannya'),
(88, 'menaklukkan'),
(50, 'mencari'),
(109, 'menciptakan'),
(53, 'mencoba'),
(97, 'mendapatkan'),
(20, 'menelan'),
(54, 'menemukan'),
(37, 'mengalahkan'),
(32, 'mengendalikan'),
(8, 'menghantui'),
(46, 'mengikuti'),
(76, 'mengungkapkan'),
(17, 'menyelamatkan'),
(98, 'misterius'),
(63, 'muda'),
(38, 'musuh'),
(5, 'negatif'),
(67, 'normal'),
(75, 'orang'),
(36, 'pahlawan'),
(94, 'pangeran'),
(77, 'pekerjaannya'),
(103, 'pemberontakan'),
(1, 'penderitaan'),
(72, 'penulis'),
(2, 'penyesalan'),
(29, 'penyihir'),
(58, 'perang'),
(4, 'perasaan'),
(78, 'perjalanan'),
(56, 'pertarungan'),
(44, 'pertarungannya'),
(62, 'prajurit'),
(39, 'pukulan'),
(107, 'rahasia'),
(24, 'ryomen'),
(35, 'saitama'),
(82, 'sayangi'),
(47, 'sehari'),
(28, 'sekolah'),
(96, 'sengaja'),
(57, 'sengit'),
(55, 'sensasi'),
(52, 'sepadan'),
(45, 'serial'),
(13, 'siswa'),
(14, 'sma'),
(86, 'suci'),
(25, 'sukuna'),
(6, 'sumber'),
(73, 'surat'),
(43, 'tantangan'),
(18, 'temannya'),
(60, 'violet'),
(26, 'wadahnya'),
(11, 'yuji'),
(108, 'zero');

-- --------------------------------------------------------

--
-- Struktur dari tabel `panjang_vektor`
--

CREATE TABLE `panjang_vektor` (
  `id_anime` int NOT NULL,
  `panjang` decimal(10,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `panjang_vektor`
--

INSERT INTO `panjang_vektor` (`id_anime`, `panjang`) VALUES
(7, 3.82168523),
(8, 2.90571542),
(9, 3.38136230),
(10, 3.77189503);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stemming_rules`
--

CREATE TABLE `stemming_rules` (
  `id_rule` int NOT NULL,
  `tipe` enum('prefix','suffix') NOT NULL,
  `rule` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `stemming_rules`
--

INSERT INTO `stemming_rules` (`id_rule`, `tipe`, `rule`) VALUES
(8, 'prefix', 'bel-'),
(7, 'prefix', 'ber-'),
(6, 'prefix', 'di-'),
(16, 'prefix', 'ke-'),
(1, 'prefix', 'me-'),
(3, 'prefix', 'mem-'),
(5, 'prefix', 'men-'),
(2, 'prefix', 'meng-'),
(4, 'prefix', 'meny-'),
(9, 'prefix', 'pe-'),
(11, 'prefix', 'pem-'),
(13, 'prefix', 'pen-'),
(10, 'prefix', 'peng-'),
(12, 'prefix', 'peny-'),
(17, 'prefix', 'per-'),
(15, 'prefix', 'se-'),
(14, 'prefix', 'ter-'),
(19, 'suffix', '-an'),
(20, 'suffix', '-i'),
(23, 'suffix', '-kah'),
(18, 'suffix', '-kan'),
(25, 'suffix', '-ku'),
(22, 'suffix', '-lah'),
(29, 'suffix', '-man'),
(26, 'suffix', '-mu'),
(21, 'suffix', '-nya'),
(24, 'suffix', '-pun'),
(27, 'suffix', '-wan'),
(28, 'suffix', '-wati');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stopwords`
--

CREATE TABLE `stopwords` (
  `id_stopword` int NOT NULL,
  `kata` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `stopwords`
--

INSERT INTO `stopwords` (`id_stopword`, `kata`) VALUES
(1, 'adalah'),
(2, 'adanya'),
(3, 'adapun'),
(4, 'agak'),
(5, 'agar'),
(6, 'akan'),
(7, 'akhir'),
(8, 'akhirnya'),
(9, 'aku'),
(10, 'akulah'),
(11, 'amat'),
(12, 'amatlah'),
(13, 'anda'),
(14, 'andalah'),
(15, 'antar'),
(16, 'antara'),
(17, 'antaranya'),
(18, 'apa'),
(19, 'apaan'),
(20, 'apabila'),
(21, 'apakah'),
(22, 'apalagi'),
(23, 'atas'),
(24, 'atau'),
(25, 'awalnya'),
(26, 'bagaimana'),
(27, 'bagaimanakah'),
(28, 'bagi'),
(29, 'bahkan'),
(30, 'bahwa'),
(31, 'bahwasanya'),
(32, 'banyak'),
(33, 'beberapa'),
(34, 'begini'),
(35, 'beginian'),
(36, 'beginikah'),
(37, 'beginilah'),
(38, 'begitu'),
(39, 'begitukah'),
(40, 'begitulah'),
(41, 'begitupun'),
(42, 'belum'),
(43, 'belumlah'),
(44, 'benar'),
(45, 'berapa'),
(46, 'berapakah'),
(47, 'berapalah'),
(48, 'berapapun'),
(49, 'berarti'),
(50, 'berawal'),
(51, 'berbagai'),
(52, 'berdatangan'),
(53, 'beri'),
(54, 'berikan'),
(55, 'berikut'),
(56, 'berikutnya'),
(57, 'berjumlah'),
(58, 'berkali-kali'),
(59, 'berkata'),
(60, 'berkehendak'),
(61, 'berkeinginan'),
(62, 'berkenaan'),
(63, 'berlainan'),
(64, 'berlalu'),
(65, 'berlangsung'),
(66, 'bermacam'),
(67, 'bermacam-macam'),
(68, 'bermaksud'),
(69, 'bermula'),
(70, 'bersama'),
(71, 'bersama-sama'),
(72, 'bersiap'),
(73, 'bersiap-siap'),
(74, 'bertanya'),
(75, 'bertanya-tanya'),
(76, 'berturut'),
(77, 'berturut-turut'),
(78, 'bertutur'),
(79, 'berujar'),
(80, 'berupa'),
(81, 'besar'),
(82, 'betul'),
(83, 'biasa'),
(84, 'biasanya'),
(85, 'bila'),
(86, 'bilakah'),
(87, 'bisa'),
(88, 'bisakah'),
(89, 'boleh'),
(90, 'bolehkah'),
(91, 'bolehlah'),
(92, 'buat'),
(93, 'bukan'),
(94, 'bukankah'),
(95, 'bukanlah'),
(96, 'bukannya'),
(97, 'cuma'),
(98, 'dahulu'),
(99, 'dalam'),
(100, 'dan'),
(101, 'dapat'),
(102, 'dari'),
(103, 'daripada'),
(104, 'datang'),
(105, 'demi'),
(106, 'demikian'),
(107, 'demikianlah'),
(108, 'dengan'),
(109, 'depan'),
(110, 'di'),
(111, 'dia'),
(112, 'diakhiri'),
(113, 'diakhirinya'),
(114, 'dialah'),
(115, 'diantara'),
(116, 'diantaranya'),
(117, 'diberi'),
(118, 'diberikan'),
(119, 'diberikannya'),
(120, 'dibuat'),
(121, 'dibuatnya'),
(122, 'didapat'),
(123, 'didatangkan'),
(124, 'digunakan'),
(125, 'diibaratkan'),
(126, 'diibaratkannya'),
(127, 'diingat'),
(128, 'diingatkan'),
(129, 'diinginkan'),
(130, 'dijawab'),
(131, 'dijelaskan'),
(132, 'dijelaskannya'),
(133, 'dikarenakan'),
(134, 'dikatakan'),
(135, 'dikatakannya'),
(136, 'dikerjakan'),
(137, 'diketahui'),
(138, 'diketahuinya'),
(139, 'dikirim'),
(140, 'dilakukan'),
(141, 'dilalui'),
(142, 'dilihat'),
(143, 'dilihatnya'),
(144, 'dimaksud'),
(145, 'dimaksudkan'),
(146, 'dimaksudkannya'),
(147, 'dimaksudnya'),
(148, 'diminta'),
(149, 'dimintai'),
(150, 'dimisalkan'),
(151, 'dimulai'),
(152, 'dimulailah'),
(153, 'dimulainya'),
(154, 'dimungkinkan'),
(155, 'dini'),
(156, 'dipastikan'),
(157, 'diperbuat'),
(158, 'diperbuatnya'),
(159, 'dipergunakan'),
(160, 'diperkirakan'),
(161, 'diperlihatkan'),
(162, 'diperlukan'),
(163, 'diperlukannya'),
(164, 'dipersoalkan'),
(165, 'dipertanyakan'),
(166, 'dipunyai'),
(167, 'diri'),
(168, 'dirinya'),
(169, 'disampaikan'),
(170, 'disebut'),
(171, 'disebutkan'),
(172, 'disebutkannya'),
(173, 'disini'),
(174, 'disinilah'),
(175, 'ditambahkan'),
(176, 'ditandaskan'),
(177, 'ditanya'),
(178, 'ditanyai'),
(179, 'ditanyakan'),
(180, 'ditegaskan'),
(181, 'ditujukan'),
(182, 'ditunjuk'),
(183, 'ditunjuki'),
(184, 'ditunjukkan'),
(185, 'ditunjukkannya'),
(186, 'dituntaskan'),
(187, 'diturunkan'),
(188, 'dituturkan'),
(189, 'dituturkannya'),
(190, 'diucapkan'),
(191, 'diucapkannya'),
(192, 'diungkapkan'),
(193, 'dong'),
(194, 'dulu'),
(195, 'enggak'),
(196, 'engkau'),
(197, 'engkaulah'),
(198, 'entah'),
(199, 'entahlah'),
(200, 'guna'),
(201, 'gunakan'),
(202, 'hal'),
(203, 'hampir'),
(204, 'hanya'),
(205, 'hanyalah'),
(206, 'hari'),
(207, 'harus'),
(208, 'haruslah'),
(209, 'harusnya'),
(210, 'hendak'),
(211, 'hendaklah'),
(212, 'hendaknya'),
(213, 'hingga'),
(214, 'ia'),
(215, 'ialah'),
(216, 'ibarat'),
(217, 'ibaratkan'),
(218, 'ibaratnya'),
(219, 'ikut'),
(220, 'ini'),
(221, 'inikah'),
(222, 'inilah'),
(223, 'itu'),
(224, 'itukah'),
(225, 'itulah'),
(226, 'jadi'),
(227, 'jadilah'),
(228, 'jadinya'),
(229, 'jangan'),
(230, 'jangankan'),
(231, 'janganlah'),
(232, 'jauh'),
(233, 'jawab'),
(234, 'jawaban'),
(235, 'jawabnya'),
(236, 'jelas'),
(237, 'jelaskan'),
(238, 'jelaslah'),
(239, 'jelasnya'),
(240, 'jika'),
(241, 'jikalau'),
(242, 'juga'),
(243, 'jumlah'),
(244, 'jumlahnya'),
(245, 'justru'),
(246, 'kala'),
(247, 'kalau'),
(248, 'kalaulah'),
(249, 'kalaupun'),
(250, 'kami'),
(251, 'kamilah'),
(252, 'kamu'),
(253, 'kamulah'),
(254, 'kan'),
(255, 'kapan'),
(256, 'kapankah'),
(257, 'kapanpun'),
(258, 'karena'),
(259, 'karenanya'),
(260, 'kata'),
(261, 'katakan'),
(262, 'katakanlah'),
(263, 'katanya'),
(264, 'ke'),
(265, 'keadaan'),
(266, 'kebetulan'),
(267, 'kecil'),
(268, 'kedua'),
(269, 'keduanya'),
(270, 'keinginan'),
(271, 'kelamaan'),
(272, 'kelihatan'),
(273, 'kelihatannya'),
(274, 'kelima'),
(275, 'keluar'),
(276, 'kembali'),
(277, 'kemudian'),
(278, 'kemungkinan'),
(279, 'kemungkinannya'),
(280, 'kenapa'),
(281, 'kepada'),
(282, 'kepadanya'),
(283, 'kesampaian'),
(284, 'keseluruhan'),
(285, 'keseluruhannya'),
(286, 'keterlaluan'),
(287, 'ketika'),
(288, 'khususnya'),
(289, 'kini'),
(290, 'kinilah'),
(291, 'kira'),
(292, 'kira-kira'),
(293, 'kiranya'),
(294, 'kita'),
(295, 'kitalah'),
(296, 'kok'),
(297, 'kurang'),
(298, 'lagi'),
(299, 'lagian'),
(300, 'lah'),
(301, 'lain'),
(302, 'lainnya'),
(303, 'lalu'),
(304, 'lama'),
(305, 'lamanya'),
(306, 'lanjut'),
(307, 'lanjutnya'),
(308, 'lebih'),
(309, 'lewat'),
(310, 'lima'),
(311, 'luar'),
(312, 'macam'),
(313, 'maka'),
(314, 'makanya'),
(315, 'makin'),
(316, 'malah'),
(317, 'malahan'),
(318, 'mampu'),
(319, 'mampukah'),
(320, 'mana'),
(321, 'manakala'),
(322, 'manalagi'),
(323, 'masih'),
(324, 'masihkah'),
(325, 'masing'),
(326, 'masing-masing'),
(327, 'mau'),
(328, 'maupun'),
(329, 'melainkan'),
(330, 'melakukan'),
(331, 'melalui'),
(332, 'melihat'),
(333, 'melihatnya'),
(334, 'memang'),
(335, 'memastikan'),
(336, 'memberi'),
(337, 'memberikan'),
(338, 'membuat'),
(339, 'memerlukan'),
(340, 'memintakan'),
(341, 'memisalkan'),
(342, 'memperbuat'),
(343, 'mempergunakan'),
(344, 'memperkirakan'),
(345, 'memperlihatkan'),
(346, 'mempersiapkan'),
(347, 'mempersoalkan'),
(348, 'mempertanyakan'),
(349, 'mempunyai'),
(350, 'memulai'),
(351, 'memungkinkan'),
(356, 'menanyakan'),
(352, 'menjadi'),
(353, 'menjelaskan'),
(354, 'menuju'),
(355, 'menurut'),
(357, 'menyampaikan'),
(358, 'menyeluruh'),
(359, 'menyiapkan'),
(360, 'merasa'),
(361, 'mereka'),
(362, 'merekalah'),
(363, 'merupakan'),
(364, 'meski'),
(365, 'meskipun'),
(366, 'meyakini'),
(367, 'meyakinkan'),
(368, 'minta'),
(369, 'mirip'),
(370, 'misal'),
(371, 'misalkan'),
(372, 'misalnya'),
(373, 'mula'),
(374, 'mulai'),
(375, 'mulailah'),
(376, 'mulanya'),
(377, 'mungkin'),
(378, 'mungkinkah'),
(379, 'nah'),
(380, 'naik'),
(381, 'namun'),
(382, 'nanti'),
(383, 'nantinya'),
(384, 'nya'),
(385, 'nyaris'),
(386, 'oleh'),
(387, 'olehnya'),
(388, 'pada'),
(389, 'padahal'),
(390, 'padanya'),
(391, 'pak'),
(392, 'paling'),
(393, 'panjang'),
(394, 'pantas'),
(395, 'para'),
(396, 'pasti'),
(397, 'pastilah'),
(398, 'penting'),
(399, 'pentingnya'),
(400, 'per'),
(401, 'percuma'),
(402, 'perlu'),
(403, 'perlukah'),
(404, 'perlunya'),
(405, 'pernah'),
(406, 'persoalan'),
(407, 'pertama'),
(408, 'pertama-tama'),
(409, 'pertanyaan'),
(410, 'pertanyakan'),
(411, 'pihak'),
(412, 'pihaknya'),
(413, 'pukul'),
(414, 'pula'),
(415, 'pun'),
(416, 'punya'),
(417, 'rasa'),
(418, 'rasanya'),
(419, 'rupa'),
(420, 'rupanya'),
(421, 'saat'),
(422, 'saatnya'),
(423, 'saja'),
(424, 'sajalah'),
(425, 'saling'),
(426, 'sama'),
(427, 'sama-sama'),
(428, 'sambil'),
(429, 'sampai'),
(430, 'sampai-sampai'),
(431, 'sana'),
(432, 'sangat'),
(433, 'sangatlah'),
(434, 'satu'),
(435, 'saya'),
(436, 'sayalah'),
(437, 'se'),
(438, 'sebab'),
(439, 'sebabnya'),
(440, 'sebagai'),
(441, 'sebagaimana'),
(442, 'sebagainya'),
(443, 'sebagian'),
(444, 'sebaik'),
(445, 'sebaik-baiknya'),
(446, 'sebaiknya'),
(447, 'sebaliknya'),
(448, 'sebanyak'),
(449, 'sebelum'),
(450, 'sebelumnya'),
(451, 'sebenarnya'),
(452, 'seberapa'),
(453, 'sebesar'),
(454, 'sebetulnya'),
(455, 'sebisanya'),
(456, 'sebuah'),
(457, 'sebut'),
(458, 'sebutlah'),
(459, 'sebutnya'),
(460, 'secara'),
(461, 'secukupnya'),
(462, 'sedang'),
(463, 'sedangkan'),
(464, 'sedemikian'),
(465, 'sedikit'),
(466, 'sedikitnya'),
(467, 'seenaknya'),
(468, 'segala'),
(469, 'segalanya'),
(470, 'segera'),
(471, 'seharusnya'),
(472, 'sehingga'),
(473, 'seingat'),
(474, 'sejak'),
(475, 'sejauh'),
(476, 'sejenak'),
(477, 'sejumlah'),
(478, 'sekadar'),
(479, 'sekadarnya'),
(480, 'sekali'),
(481, 'sekali-kali'),
(482, 'sekalian'),
(483, 'sekaligus'),
(484, 'sekalipun'),
(485, 'sekarang'),
(486, 'sekaranglah'),
(487, 'sekecil'),
(488, 'seketika'),
(489, 'sekiranya'),
(490, 'sekitar'),
(491, 'sekitarnya'),
(492, 'sela'),
(493, 'selain'),
(494, 'selaku'),
(495, 'selalu'),
(496, 'selama'),
(497, 'selama-lamanya'),
(498, 'selamanya'),
(499, 'selanjutnya'),
(500, 'seluruh'),
(501, 'seluruhnya'),
(502, 'semacam'),
(503, 'semakin'),
(504, 'semampu'),
(505, 'semampunya'),
(506, 'semasa'),
(507, 'semasih'),
(508, 'semata'),
(509, 'semata-mata'),
(510, 'semaunya'),
(511, 'sementara'),
(512, 'semisal'),
(513, 'semisalnya'),
(514, 'sempat'),
(515, 'semua'),
(516, 'semuanya'),
(517, 'semula'),
(518, 'sendiri'),
(519, 'sendirian'),
(520, 'sendirinya'),
(521, 'seolah'),
(522, 'seolah-olah'),
(523, 'seorang'),
(524, 'sepanjang'),
(525, 'sepantasnya'),
(526, 'sepantasnyalah'),
(527, 'seperlunya'),
(528, 'seperti'),
(529, 'sepertinya'),
(530, 'sepihak'),
(531, 'sering'),
(532, 'seringnya'),
(533, 'serta'),
(534, 'serupa'),
(535, 'sesaat'),
(536, 'sesama'),
(537, 'sesampai'),
(538, 'sesegera'),
(539, 'sesekali'),
(540, 'seseorang'),
(541, 'sesuatu'),
(542, 'sesuatunya'),
(543, 'sesudah'),
(544, 'sesudahnya'),
(545, 'setelah'),
(546, 'setempat'),
(547, 'setengah'),
(548, 'seterusnya'),
(549, 'setiap'),
(550, 'setiba'),
(551, 'setibanya'),
(552, 'setidak-tidaknya'),
(553, 'setidaknya'),
(554, 'setinggi'),
(555, 'seusai'),
(556, 'sewaktu'),
(557, 'siap'),
(558, 'siapa'),
(559, 'siapakah'),
(560, 'siapapun'),
(561, 'sini'),
(562, 'sinilah'),
(563, 'suatu'),
(564, 'sudah'),
(565, 'sudahkah'),
(566, 'sudahlah'),
(567, 'supaya'),
(568, 'tadi'),
(569, 'tadinya'),
(570, 'tahu'),
(571, 'tahun'),
(572, 'tak'),
(573, 'tanpa'),
(574, 'tanya'),
(575, 'tanyakan'),
(576, 'tanyanya'),
(577, 'tapi'),
(578, 'telah'),
(579, 'tempat'),
(580, 'tengah'),
(581, 'tentang'),
(582, 'tentu'),
(583, 'tentulah'),
(584, 'tentunya'),
(585, 'terakhir'),
(586, 'terasa'),
(587, 'terbanyak'),
(588, 'terdahulu'),
(589, 'terdapat'),
(590, 'terdiri'),
(591, 'terhadap'),
(592, 'terhadapnya'),
(593, 'teringat'),
(594, 'teringat-ingat'),
(595, 'terjadi'),
(596, 'terjadilah'),
(597, 'terjadinya'),
(598, 'terkira'),
(599, 'terlalu'),
(600, 'terlebih'),
(601, 'terlihat'),
(602, 'termasuk'),
(603, 'ternyata'),
(604, 'tersampaikan'),
(605, 'tersebut'),
(606, 'tersebutlah'),
(607, 'tertentu'),
(608, 'tertuju'),
(609, 'terus'),
(610, 'terutama'),
(611, 'tetapi'),
(612, 'tiap'),
(613, 'tidak'),
(614, 'tidakkah'),
(615, 'tidaklah'),
(616, 'toh'),
(617, 'turut'),
(618, 'untuk'),
(619, 'usah'),
(620, 'usai'),
(621, 'wah'),
(622, 'wahai'),
(623, 'walau'),
(624, 'walaupun'),
(625, 'wong'),
(626, 'yaitu'),
(627, 'yakin'),
(628, 'yakni'),
(629, 'yang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tanda_baca`
--

CREATE TABLE `tanda_baca` (
  `id_tanda_baca` int NOT NULL,
  `karakter` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tanda_baca`
--

INSERT INTO `tanda_baca` (`id_tanda_baca`, `karakter`) VALUES
(7, '_'),
(8, '-'),
(1, ','),
(5, ';'),
(6, ':'),
(3, '!'),
(4, '?'),
(2, '.'),
(18, '\''),
(17, '\"'),
(11, '('),
(12, ')'),
(15, '['),
(16, ']'),
(13, '{'),
(14, '}'),
(21, '@'),
(27, '*'),
(28, '/'),
(29, '\\'),
(26, '&'),
(22, '#'),
(24, '%'),
(19, '`'),
(25, '^'),
(9, '+'),
(31, '<'),
(10, '='),
(32, '>'),
(30, '|'),
(20, '~'),
(23, '$');

-- --------------------------------------------------------

--
-- Struktur dari tabel `term_frequency`
--

CREATE TABLE `term_frequency` (
  `id_anime` int NOT NULL,
  `id_kata` int NOT NULL,
  `frekuensi` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `term_frequency`
--

INSERT INTO `term_frequency` (`id_anime`, `id_kata`, `frekuensi`) VALUES
(7, 1, 1),
(7, 2, 1),
(7, 3, 1),
(7, 4, 1),
(7, 5, 1),
(7, 6, 1),
(7, 7, 3),
(7, 8, 1),
(7, 9, 1),
(7, 10, 1),
(7, 11, 2),
(7, 12, 1),
(7, 13, 1),
(7, 14, 1),
(7, 15, 1),
(7, 16, 1),
(7, 17, 1),
(7, 18, 1),
(7, 19, 1),
(7, 20, 1),
(7, 21, 1),
(7, 22, 1),
(7, 23, 1),
(7, 24, 1),
(7, 25, 1),
(7, 26, 1),
(7, 27, 1),
(7, 28, 1),
(7, 29, 1),
(7, 30, 1),
(7, 31, 1),
(7, 32, 1),
(7, 33, 1),
(7, 34, 1),
(8, 9, 1),
(8, 33, 1),
(8, 35, 1),
(8, 36, 1),
(8, 37, 1),
(8, 38, 1),
(8, 39, 1),
(8, 40, 1),
(8, 41, 1),
(8, 42, 1),
(8, 43, 1),
(8, 44, 1),
(8, 45, 1),
(8, 46, 1),
(8, 47, 1),
(8, 48, 1),
(8, 49, 1),
(8, 50, 1),
(8, 51, 1),
(8, 52, 1),
(8, 53, 1),
(8, 54, 1),
(8, 55, 1),
(8, 56, 1),
(8, 57, 1),
(9, 4, 1),
(9, 9, 1),
(9, 10, 1),
(9, 58, 1),
(9, 59, 1),
(9, 60, 2),
(9, 61, 1),
(9, 62, 1),
(9, 63, 1),
(9, 64, 1),
(9, 65, 1),
(9, 66, 1),
(9, 67, 1),
(9, 68, 1),
(9, 69, 1),
(9, 70, 1),
(9, 71, 1),
(9, 72, 1),
(9, 73, 1),
(9, 74, 1),
(9, 75, 2),
(9, 76, 1),
(9, 77, 1),
(9, 78, 1),
(9, 79, 1),
(9, 80, 1),
(9, 81, 1),
(9, 82, 1),
(10, 15, 2),
(10, 34, 1),
(10, 83, 2),
(10, 84, 1),
(10, 85, 2),
(10, 86, 1),
(10, 87, 2),
(10, 88, 1),
(10, 89, 1),
(10, 90, 1),
(10, 91, 1),
(10, 92, 1),
(10, 93, 1),
(10, 94, 1),
(10, 95, 1),
(10, 96, 1),
(10, 97, 1),
(10, 98, 1),
(10, 99, 1),
(10, 100, 1),
(10, 101, 1),
(10, 102, 1),
(10, 103, 1),
(10, 104, 1),
(10, 105, 1),
(10, 106, 1),
(10, 107, 1),
(10, 108, 1),
(10, 109, 1),
(10, 110, 1),
(10, 111, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$2k4CnI3E2bBg/Ggy7cQ8DOwAgRDuxSKXEsAlHB8Mz2GuhNlEKUW5e', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`id_anime`);

--
-- Indeks untuk tabel `bobot_tf_idf`
--
ALTER TABLE `bobot_tf_idf`
  ADD PRIMARY KEY (`id_anime`,`id_kata`),
  ADD KEY `id_kata` (`id_kata`);

--
-- Indeks untuk tabel `document_frequency`
--
ALTER TABLE `document_frequency`
  ADD PRIMARY KEY (`id_kata`);

--
-- Indeks untuk tabel `inverse_document_frequency`
--
ALTER TABLE `inverse_document_frequency`
  ADD PRIMARY KEY (`id_kata`);

--
-- Indeks untuk tabel `kamus_kata`
--
ALTER TABLE `kamus_kata`
  ADD PRIMARY KEY (`id_kata`),
  ADD UNIQUE KEY `kata` (`kata`);

--
-- Indeks untuk tabel `panjang_vektor`
--
ALTER TABLE `panjang_vektor`
  ADD PRIMARY KEY (`id_anime`);

--
-- Indeks untuk tabel `stemming_rules`
--
ALTER TABLE `stemming_rules`
  ADD PRIMARY KEY (`id_rule`),
  ADD UNIQUE KEY `tipe` (`tipe`,`rule`);

--
-- Indeks untuk tabel `stopwords`
--
ALTER TABLE `stopwords`
  ADD PRIMARY KEY (`id_stopword`),
  ADD UNIQUE KEY `kata` (`kata`);

--
-- Indeks untuk tabel `tanda_baca`
--
ALTER TABLE `tanda_baca`
  ADD PRIMARY KEY (`id_tanda_baca`),
  ADD UNIQUE KEY `karakter` (`karakter`);

--
-- Indeks untuk tabel `term_frequency`
--
ALTER TABLE `term_frequency`
  ADD PRIMARY KEY (`id_anime`,`id_kata`),
  ADD KEY `id_kata` (`id_kata`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anime`
--
ALTER TABLE `anime`
  MODIFY `id_anime` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kamus_kata`
--
ALTER TABLE `kamus_kata`
  MODIFY `id_kata` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT untuk tabel `stemming_rules`
--
ALTER TABLE `stemming_rules`
  MODIFY `id_rule` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `stopwords`
--
ALTER TABLE `stopwords`
  MODIFY `id_stopword` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=630;

--
-- AUTO_INCREMENT untuk tabel `tanda_baca`
--
ALTER TABLE `tanda_baca`
  MODIFY `id_tanda_baca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bobot_tf_idf`
--
ALTER TABLE `bobot_tf_idf`
  ADD CONSTRAINT `bobot_tf_idf_ibfk_1` FOREIGN KEY (`id_anime`) REFERENCES `anime` (`id_anime`) ON DELETE CASCADE,
  ADD CONSTRAINT `bobot_tf_idf_ibfk_2` FOREIGN KEY (`id_kata`) REFERENCES `kamus_kata` (`id_kata`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `document_frequency`
--
ALTER TABLE `document_frequency`
  ADD CONSTRAINT `document_frequency_ibfk_1` FOREIGN KEY (`id_kata`) REFERENCES `kamus_kata` (`id_kata`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `inverse_document_frequency`
--
ALTER TABLE `inverse_document_frequency`
  ADD CONSTRAINT `inverse_document_frequency_ibfk_1` FOREIGN KEY (`id_kata`) REFERENCES `kamus_kata` (`id_kata`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `panjang_vektor`
--
ALTER TABLE `panjang_vektor`
  ADD CONSTRAINT `panjang_vektor_ibfk_1` FOREIGN KEY (`id_anime`) REFERENCES `anime` (`id_anime`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `term_frequency`
--
ALTER TABLE `term_frequency`
  ADD CONSTRAINT `term_frequency_ibfk_1` FOREIGN KEY (`id_anime`) REFERENCES `anime` (`id_anime`) ON DELETE CASCADE,
  ADD CONSTRAINT `term_frequency_ibfk_2` FOREIGN KEY (`id_kata`) REFERENCES `kamus_kata` (`id_kata`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
