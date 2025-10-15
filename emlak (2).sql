-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 16 Eki 2025, 00:01:31
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `emlak`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','moderator','editor') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$J.Sb9wNvW9ubdKM4cgTZDeTW9zXOGYxErZyUfmSYlv5Ow8uiFnWmm', 'admin@er.com', 'Sistem Yöneticisi', 'admin', 'active', '2025-10-15 22:45:20', '2025-10-15 17:14:29', '2025-10-15 19:45:20'),
(2, 'moderator', '$2y$10$.XncAmpvQeHDPxWwXap3S.mB8QjWqWO5aXAy6DYkQJBtNz/55CVTa', 'moderator@er.com', 'Moderatör', 'moderator', 'active', NULL, '2025-10-15 17:14:29', '2025-10-15 17:19:33');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `plate_code` varchar(2) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cities`
--

INSERT INTO `cities` (`id`, `name`, `slug`, `status`, `created_at`, `plate_code`, `region`) VALUES
(448, 'Adana', 'adana', 'active', '2025-10-15 18:42:37', '01', 'Akdeniz'),
(449, 'Adıyaman', 'adiyaman', 'active', '2025-10-15 18:42:37', '02', 'Güneydoğu Anadolu'),
(450, 'Afyonkarahisar', 'afyonkarahisar', 'active', '2025-10-15 18:42:37', '03', 'Ege'),
(451, 'Ağrı', 'agri', 'active', '2025-10-15 18:42:37', '04', 'Doğu Anadolu'),
(452, 'Amasya', 'amasya', 'active', '2025-10-15 18:42:37', '05', 'Karadeniz'),
(453, 'Ankara', 'ankara', 'active', '2025-10-15 18:42:37', '06', 'İç Anadolu'),
(454, 'Antalya', 'antalya', 'active', '2025-10-15 18:42:37', '07', 'Akdeniz'),
(455, 'Artvin', 'artvin', 'active', '2025-10-15 18:42:37', '08', 'Karadeniz'),
(456, 'Aydın', 'aydin', 'active', '2025-10-15 18:42:37', '09', 'Ege'),
(457, 'Balıkesir', 'balikesir', 'active', '2025-10-15 18:42:37', '10', 'Marmara'),
(458, 'Bilecik', 'bilecik', 'active', '2025-10-15 18:42:37', '11', 'Marmara'),
(459, 'Bingöl', 'bingol', 'active', '2025-10-15 18:42:37', '12', 'Doğu Anadolu'),
(460, 'Bitlis', 'bitlis', 'active', '2025-10-15 18:42:37', '13', 'Doğu Anadolu'),
(461, 'Bolu', 'bolu', 'active', '2025-10-15 18:42:37', '14', 'Karadeniz'),
(462, 'Burdur', 'burdur', 'active', '2025-10-15 18:42:37', '15', 'Akdeniz'),
(463, 'Bursa', 'bursa', 'active', '2025-10-15 18:42:37', '16', 'Marmara'),
(464, 'Çanakkale', 'canakkale', 'active', '2025-10-15 18:42:37', '17', 'Marmara'),
(465, 'Çankırı', 'cankiri', 'active', '2025-10-15 18:42:37', '18', 'İç Anadolu'),
(466, 'Çorum', 'corum', 'active', '2025-10-15 18:42:37', '19', 'Karadeniz'),
(467, 'Denizli', 'denizli', 'active', '2025-10-15 18:42:37', '20', 'Ege'),
(468, 'Diyarbakır', 'diyarbakir', 'active', '2025-10-15 18:42:37', '21', 'Güneydoğu Anadolu'),
(469, 'Edirne', 'edirne', 'active', '2025-10-15 18:42:37', '22', 'Marmara'),
(470, 'Elazığ', 'elazig', 'active', '2025-10-15 18:42:37', '23', 'Doğu Anadolu'),
(471, 'Erzincan', 'erzincan', 'active', '2025-10-15 18:42:37', '24', 'Doğu Anadolu'),
(472, 'Erzurum', 'erzurum', 'active', '2025-10-15 18:42:37', '25', 'Doğu Anadolu'),
(473, 'Eskişehir', 'eskisehir', 'active', '2025-10-15 18:42:37', '26', 'İç Anadolu'),
(474, 'Gaziantep', 'gaziantep', 'active', '2025-10-15 18:42:37', '27', 'Güneydoğu Anadolu'),
(475, 'Giresun', 'giresun', 'active', '2025-10-15 18:42:37', '28', 'Karadeniz'),
(476, 'Gümüşhane', 'gumushane', 'active', '2025-10-15 18:42:37', '29', 'Karadeniz'),
(477, 'Hakkâri', 'hakkari', 'active', '2025-10-15 18:42:37', '30', 'Doğu Anadolu'),
(478, 'Hatay', 'hatay', 'active', '2025-10-15 18:42:37', '31', 'Akdeniz'),
(479, 'Isparta', 'isparta', 'active', '2025-10-15 18:42:37', '32', 'Akdeniz'),
(480, 'Mersin', 'mersin', 'active', '2025-10-15 18:42:37', '33', 'Akdeniz'),
(481, 'İstanbul', 'istanbul', 'active', '2025-10-15 18:42:37', '34', 'Marmara'),
(482, 'İzmir', 'izmir', 'active', '2025-10-15 18:42:37', '35', 'Ege'),
(483, 'Kars', 'kars', 'active', '2025-10-15 18:42:37', '36', 'Doğu Anadolu'),
(484, 'Kastamonu', 'kastamonu', 'active', '2025-10-15 18:42:37', '37', 'Karadeniz'),
(485, 'Kayseri', 'kayseri', 'active', '2025-10-15 18:42:37', '38', 'İç Anadolu'),
(486, 'Kırklareli', 'kirklareli', 'active', '2025-10-15 18:42:37', '39', 'Marmara'),
(487, 'Kırşehir', 'kirsehir', 'active', '2025-10-15 18:42:37', '40', 'İç Anadolu'),
(488, 'Kocaeli', 'kocaeli', 'active', '2025-10-15 18:42:37', '41', 'Marmara'),
(489, 'Konya', 'konya', 'active', '2025-10-15 18:42:37', '42', 'İç Anadolu'),
(490, 'Kütahya', 'kutahya', 'active', '2025-10-15 18:42:37', '43', 'Ege'),
(491, 'Malatya', 'malatya', 'active', '2025-10-15 18:42:37', '44', 'Doğu Anadolu'),
(492, 'Manisa', 'manisa', 'active', '2025-10-15 18:42:37', '45', 'Ege'),
(493, 'Kahramanmaraş', 'kahramanmaras', 'active', '2025-10-15 18:42:37', '46', 'Akdeniz'),
(494, 'Mardin', 'mardin', 'active', '2025-10-15 18:42:37', '47', 'Güneydoğu Anadolu'),
(495, 'Muğla', 'mugla', 'active', '2025-10-15 18:42:37', '48', 'Ege'),
(496, 'Muş', 'mus', 'active', '2025-10-15 18:42:37', '49', 'Doğu Anadolu'),
(497, 'Nevşehir', 'nevsehir', 'active', '2025-10-15 18:42:37', '50', 'İç Anadolu'),
(498, 'Niğde', 'nigde', 'active', '2025-10-15 18:42:37', '51', 'İç Anadolu'),
(499, 'Ordu', 'ordu', 'active', '2025-10-15 18:42:37', '52', 'Karadeniz'),
(500, 'Rize', 'rize', 'active', '2025-10-15 18:42:37', '53', 'Karadeniz'),
(501, 'Sakarya', 'sakarya', 'active', '2025-10-15 18:42:37', '54', 'Marmara'),
(502, 'Samsun', 'samsun', 'active', '2025-10-15 18:42:37', '55', 'Karadeniz'),
(503, 'Siirt', 'siirt', 'active', '2025-10-15 18:42:37', '56', 'Güneydoğu Anadolu'),
(504, 'Sinop', 'sinop', 'active', '2025-10-15 18:42:37', '57', 'Karadeniz'),
(505, 'Sivas', 'sivas', 'active', '2025-10-15 18:42:37', '58', 'İç Anadolu'),
(506, 'Tekirdağ', 'tekirdag', 'active', '2025-10-15 18:42:37', '59', 'Marmara'),
(507, 'Tokat', 'tokat', 'active', '2025-10-15 18:42:37', '60', 'Karadeniz'),
(508, 'Trabzon', 'trabzon', 'active', '2025-10-15 18:42:37', '61', 'Karadeniz'),
(509, 'Tunceli', 'tunceli', 'active', '2025-10-15 18:42:37', '62', 'Doğu Anadolu'),
(510, 'Şanlıurfa', 'sanliurfa', 'active', '2025-10-15 18:42:37', '63', 'Güneydoğu Anadolu'),
(511, 'Uşak', 'usak', 'active', '2025-10-15 18:42:37', '64', 'Ege'),
(512, 'Van', 'van', 'active', '2025-10-15 18:42:37', '65', 'Doğu Anadolu'),
(513, 'Yozgat', 'yozgat', 'active', '2025-10-15 18:42:37', '66', 'İç Anadolu'),
(514, 'Zonguldak', 'zonguldak', 'active', '2025-10-15 18:42:37', '67', 'Karadeniz'),
(515, 'Aksaray', 'aksaray', 'active', '2025-10-15 18:42:37', '68', 'İç Anadolu'),
(516, 'Bayburt', 'bayburt', 'active', '2025-10-15 18:42:37', '69', 'Karadeniz'),
(517, 'Karaman', 'karaman', 'active', '2025-10-15 18:42:37', '70', 'İç Anadolu'),
(518, 'Kırıkkale', 'kirikkale', 'active', '2025-10-15 18:42:37', '71', 'İç Anadolu'),
(519, 'Batman', 'batman', 'active', '2025-10-15 18:42:37', '72', 'Güneydoğu Anadolu'),
(520, 'Şırnak', 'sirnak', 'active', '2025-10-15 18:42:37', '73', 'Güneydoğu Anadolu'),
(521, 'Bartın', 'bartin', 'active', '2025-10-15 18:42:37', '74', 'Karadeniz'),
(522, 'Ardahan', 'ardahan', 'active', '2025-10-15 18:42:37', '75', 'Doğu Anadolu'),
(523, 'Iğdır', 'igdir', 'active', '2025-10-15 18:42:37', '76', 'Doğu Anadolu'),
(524, 'Yalova', 'yalova', 'active', '2025-10-15 18:42:37', '77', 'Marmara'),
(525, 'Karabük', 'karabuk', 'active', '2025-10-15 18:42:37', '78', 'Karadeniz'),
(526, 'Kilis', 'kilis', 'active', '2025-10-15 18:42:37', '79', 'Güneydoğu Anadolu'),
(527, 'Osmaniye', 'osmaniye', 'active', '2025-10-15 18:42:37', '80', 'Akdeniz'),
(528, 'Düzce', 'duzce', 'active', '2025-10-15 18:42:37', '81', 'Karadeniz'),
(529, 'Deneme', '', '', '2025-10-15 19:35:37', '83', 'Akdeniz');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `districts`
--

INSERT INTO `districts` (`id`, `city_id`, `name`, `slug`, `status`, `created_at`) VALUES
(613, 481, 'Adalar', 'adalar', 'active', '2025-10-15 18:42:37'),
(614, 481, 'Arnavutköy', 'arnavutkoy', 'active', '2025-10-15 18:42:37'),
(615, 481, 'Ataşehir', 'atasehir', 'active', '2025-10-15 18:42:37'),
(616, 481, 'Avcılar', 'avcilar', 'active', '2025-10-15 18:42:37'),
(617, 481, 'Bağcılar', 'bagcilar', 'active', '2025-10-15 18:42:37'),
(618, 481, 'Bahçelievler', 'bahcelievler', 'active', '2025-10-15 18:42:37'),
(619, 481, 'Bakırköy', 'bakirkoy', 'active', '2025-10-15 18:42:37'),
(620, 481, 'Başakşehir', 'basaksehir', 'active', '2025-10-15 18:42:37'),
(621, 481, 'Bayrampaşa', 'bayrampasa', 'active', '2025-10-15 18:42:37'),
(622, 481, 'Beşiktaş', 'besiktas', 'active', '2025-10-15 18:42:37'),
(623, 481, 'Beykoz', 'beykoz', 'active', '2025-10-15 18:42:37'),
(624, 481, 'Beylikdüzü', 'beylikduzu', 'active', '2025-10-15 18:42:37'),
(625, 481, 'Beyoğlu', 'beyoglu', 'active', '2025-10-15 18:42:37'),
(626, 481, 'Büyükçekmece', 'buyukcekmece', 'active', '2025-10-15 18:42:37'),
(627, 481, 'Çatalca', 'catalca', 'active', '2025-10-15 18:42:37'),
(628, 481, 'Çekmeköy', 'cekmekoy', 'active', '2025-10-15 18:42:37'),
(629, 481, 'Esenler', 'esenler', 'active', '2025-10-15 18:42:37'),
(630, 481, 'Esenyurt', 'esenyurt', 'active', '2025-10-15 18:42:37'),
(631, 481, 'Eyüpsultan', 'eyupsultan', 'active', '2025-10-15 18:42:37'),
(632, 481, 'Fatih', 'fatih', 'active', '2025-10-15 18:42:37'),
(633, 481, 'Gaziosmanpaşa', 'gaziosmanpasa', 'active', '2025-10-15 18:42:37'),
(634, 481, 'Güngören', 'gungoren', 'active', '2025-10-15 18:42:37'),
(635, 481, 'Kadıköy', 'kadikoy', 'active', '2025-10-15 18:42:37'),
(636, 481, 'Kağıthane', 'kagithane', 'active', '2025-10-15 18:42:37'),
(637, 481, 'Kartal', 'kartal', 'active', '2025-10-15 18:42:37'),
(638, 481, 'Küçükçekmece', 'kucukcekmece', 'active', '2025-10-15 18:42:37'),
(639, 481, 'Maltepe', 'maltepe', 'active', '2025-10-15 18:42:37'),
(640, 481, 'Pendik', 'pendik', 'active', '2025-10-15 18:42:37'),
(641, 481, 'Sancaktepe', 'sancaktepe', 'active', '2025-10-15 18:42:37'),
(642, 481, 'Sarıyer', 'sariyer', 'active', '2025-10-15 18:42:37'),
(643, 481, 'Silivri', 'silivri', 'active', '2025-10-15 18:42:37'),
(644, 481, 'Sultanbeyli', 'sultanbeyli', 'active', '2025-10-15 18:42:37'),
(645, 481, 'Sultangazi', 'sultangazi', 'active', '2025-10-15 18:42:37'),
(646, 481, 'Şile', 'sile', 'active', '2025-10-15 18:42:37'),
(647, 481, 'Şişli', 'sisli', 'active', '2025-10-15 18:42:37'),
(648, 481, 'Tuzla', 'tuzla', 'active', '2025-10-15 18:42:37'),
(649, 481, 'Ümraniye', 'umraniye', 'active', '2025-10-15 18:42:37'),
(650, 481, 'Üsküdar', 'uskudar', 'active', '2025-10-15 18:42:37'),
(651, 481, 'Zeytinburnu', 'zeytinburnu', 'active', '2025-10-15 18:42:37'),
(652, 453, 'Akyurt', 'akyurt', 'active', '2025-10-15 18:42:37'),
(653, 453, 'Altındağ', 'altindag', 'active', '2025-10-15 18:42:37'),
(654, 453, 'Ayaş', 'ayas', 'active', '2025-10-15 18:42:37'),
(655, 453, 'Bala', 'bala', 'active', '2025-10-15 18:42:37'),
(656, 453, 'Beypazarı', 'beypazari', 'active', '2025-10-15 18:42:37'),
(657, 453, 'Çamlıdere', 'camlidere', 'active', '2025-10-15 18:42:37'),
(658, 453, 'Çankaya', 'cankaya', 'active', '2025-10-15 18:42:37'),
(659, 453, 'Çubuk', 'cubuk', 'active', '2025-10-15 18:42:37'),
(660, 453, 'Elmadağ', 'elmadag', 'active', '2025-10-15 18:42:37'),
(661, 453, 'Etimesgut', 'etimesgut', 'active', '2025-10-15 18:42:37'),
(662, 453, 'Evren', 'evren', 'active', '2025-10-15 18:42:37'),
(663, 453, 'Gölbaşı', 'golbasi', 'active', '2025-10-15 18:42:37'),
(664, 453, 'Güdül', 'gudul', 'active', '2025-10-15 18:42:37'),
(665, 453, 'Haymana', 'haymana', 'active', '2025-10-15 18:42:37'),
(666, 453, 'Kalecik', 'kalecik', 'active', '2025-10-15 18:42:37'),
(667, 453, 'Kazan', 'kazan', 'active', '2025-10-15 18:42:37'),
(668, 453, 'Keçiören', 'kecioren', 'active', '2025-10-15 18:42:37'),
(669, 453, 'Kızılcahamam', 'kizilcahamam', 'active', '2025-10-15 18:42:37'),
(670, 453, 'Mamak', 'mamak', 'active', '2025-10-15 18:42:37'),
(671, 453, 'Nallıhan', 'nallihan', 'active', '2025-10-15 18:42:37'),
(672, 453, 'Polatlı', 'polatli', 'active', '2025-10-15 18:42:37'),
(673, 453, 'Pursaklar', 'pursaklar', 'active', '2025-10-15 18:42:37'),
(674, 453, 'Sincan', 'sincan', 'active', '2025-10-15 18:42:37'),
(675, 453, 'Şereflikoçhisar', 'sereflikochisar', 'active', '2025-10-15 18:42:37'),
(676, 453, 'Yenimahalle', 'yenimahalle', 'active', '2025-10-15 18:42:37'),
(677, 482, 'Aliağa', 'aliaga', 'active', '2025-10-15 18:42:37'),
(678, 482, 'Balçova', 'balcova', 'active', '2025-10-15 18:42:37'),
(679, 482, 'Bayındır', 'bayindir', 'active', '2025-10-15 18:42:37'),
(680, 482, 'Bayraklı', 'bayrakli', 'active', '2025-10-15 18:42:37'),
(681, 482, 'Bergama', 'bergama', 'active', '2025-10-15 18:42:37'),
(682, 482, 'Beydağ', 'beydag', 'active', '2025-10-15 18:42:37'),
(683, 482, 'Bornova', 'bornova', 'active', '2025-10-15 18:42:37'),
(684, 482, 'Buca', 'buca', 'active', '2025-10-15 18:42:37'),
(685, 482, 'Çeşme', 'cesme', 'active', '2025-10-15 18:42:37'),
(686, 482, 'Çiğli', 'cigli', 'active', '2025-10-15 18:42:37'),
(687, 482, 'Dikili', 'dikili', 'active', '2025-10-15 18:42:37'),
(688, 482, 'Foça', 'foca', 'active', '2025-10-15 18:42:37'),
(689, 482, 'Gaziemir', 'gaziemir', 'active', '2025-10-15 18:42:37'),
(690, 482, 'Güzelbahçe', 'guzelbahce', 'active', '2025-10-15 18:42:37'),
(691, 482, 'Karabağlar', 'karabaglar', 'active', '2025-10-15 18:42:37'),
(692, 482, 'Karaburun', 'karaburun', 'active', '2025-10-15 18:42:37'),
(693, 482, 'Karşıyaka', 'karsiyaka', 'active', '2025-10-15 18:42:37'),
(694, 482, 'Kemalpaşa', 'kemalpasa', 'active', '2025-10-15 18:42:37'),
(695, 482, 'Kınık', 'kinik', 'active', '2025-10-15 18:42:37'),
(696, 482, 'Kiraz', 'kiraz', 'active', '2025-10-15 18:42:37'),
(697, 482, 'Konak', 'konak', 'active', '2025-10-15 18:42:37'),
(698, 482, 'Menderes', 'menderes', 'active', '2025-10-15 18:42:37'),
(699, 482, 'Menemen', 'menemen', 'active', '2025-10-15 18:42:37'),
(700, 482, 'Narlıdere', 'narlidere', 'active', '2025-10-15 18:42:37'),
(701, 482, 'Ödemiş', 'odemis', 'active', '2025-10-15 18:42:37'),
(702, 482, 'Seferihisar', 'seferihisar', 'active', '2025-10-15 18:42:37'),
(703, 482, 'Selçuk', 'selcuk', 'active', '2025-10-15 18:42:37'),
(704, 482, 'Tire', 'tire', 'active', '2025-10-15 18:42:37'),
(705, 482, 'Torbalı', 'torbali', 'active', '2025-10-15 18:42:37'),
(706, 482, 'Urla', 'urla', 'active', '2025-10-15 18:42:37'),
(707, 463, 'Büyükorhan', 'buyukorhan', 'active', '2025-10-15 18:42:37'),
(708, 463, 'Gemlik', 'gemlik', 'active', '2025-10-15 18:42:37'),
(709, 463, 'Gürsu', 'gursu', 'active', '2025-10-15 18:42:37'),
(710, 463, 'Harmancık', 'harmancik', 'active', '2025-10-15 18:42:37'),
(711, 463, 'İnegöl', 'inegol', 'active', '2025-10-15 18:42:37'),
(712, 463, 'İznik', 'iznik', 'active', '2025-10-15 18:42:37'),
(713, 463, 'Karacabey', 'karacabey', 'active', '2025-10-15 18:42:37'),
(714, 463, 'Keles', 'keles', 'active', '2025-10-15 18:42:37'),
(715, 463, 'Kestel', 'kestel', 'active', '2025-10-15 18:42:37'),
(716, 463, 'Mudanya', 'mudanya', 'active', '2025-10-15 18:42:37'),
(717, 463, 'Mustafakemalpaşa', 'mustafakemalpasa', 'active', '2025-10-15 18:42:37'),
(718, 463, 'Nilüfer', 'nilufer', 'active', '2025-10-15 18:42:37'),
(719, 463, 'Orhaneli', 'orhaneli', 'active', '2025-10-15 18:42:37'),
(720, 463, 'Orhangazi', 'orhangazi', 'active', '2025-10-15 18:42:37'),
(721, 463, 'Osmangazi', 'osmangazi', 'active', '2025-10-15 18:42:37'),
(722, 463, 'Yenişehir', 'yenisehir', 'active', '2025-10-15 18:42:37'),
(723, 463, 'Yıldırım', 'yildirim', 'active', '2025-10-15 18:42:37'),
(724, 454, 'Akseki', 'akseki', 'active', '2025-10-15 18:42:37'),
(725, 454, 'Aksu', 'aksu', 'active', '2025-10-15 18:42:37'),
(726, 454, 'Alanya', 'alanya', 'active', '2025-10-15 18:42:37'),
(727, 454, 'Demre', 'demre', 'active', '2025-10-15 18:42:37'),
(728, 454, 'Döşemealtı', 'dosemealti', 'active', '2025-10-15 18:42:37'),
(729, 454, 'Elmalı', 'elmali', 'active', '2025-10-15 18:42:37'),
(730, 454, 'Finike', 'finike', 'active', '2025-10-15 18:42:37'),
(731, 454, 'Gazipaşa', 'gazipasa', 'active', '2025-10-15 18:42:37'),
(732, 454, 'Gündoğmuş', 'gundogmus', 'active', '2025-10-15 18:42:37'),
(733, 454, 'İbradı', 'ibradi', 'active', '2025-10-15 18:42:37'),
(734, 454, 'Kaş', 'kas', 'active', '2025-10-15 18:42:37'),
(735, 454, 'Kemer', 'kemer', 'active', '2025-10-15 18:42:37'),
(736, 454, 'Kepez', 'kepez', 'active', '2025-10-15 18:42:37'),
(737, 454, 'Konyaaltı', 'konyaalti', 'active', '2025-10-15 18:42:37'),
(738, 454, 'Korkuteli', 'korkuteli', 'active', '2025-10-15 18:42:37'),
(739, 454, 'Kumluca', 'kumluca', 'active', '2025-10-15 18:42:37'),
(740, 454, 'Manavgat', 'manavgat', 'active', '2025-10-15 18:42:37'),
(741, 454, 'Muratpaşa', 'muratpasa', 'active', '2025-10-15 18:42:37'),
(742, 454, 'Serik', 'serik', 'active', '2025-10-15 18:42:37'),
(743, 448, 'Aladağ', 'aladag', 'active', '2025-10-15 18:49:44'),
(744, 448, 'Ceyhan', 'ceyhan', 'active', '2025-10-15 18:49:44'),
(745, 448, 'Çukurova', 'cukurova', 'active', '2025-10-15 18:49:44'),
(746, 448, 'Feke', 'feke', 'active', '2025-10-15 18:49:44'),
(747, 448, 'İmamoğlu', 'imamoglu', 'active', '2025-10-15 18:49:44'),
(748, 448, 'Karaisalı', 'karaisali', 'active', '2025-10-15 18:49:44'),
(749, 448, 'Karataş', 'karatas', 'active', '2025-10-15 18:49:44'),
(750, 448, 'Kozan', 'kozan', 'active', '2025-10-15 18:49:44'),
(751, 448, 'Pozantı', 'pozanti', 'active', '2025-10-15 18:49:44'),
(752, 448, 'Saimbeyli', 'saimbeyli', 'active', '2025-10-15 18:49:44'),
(753, 448, 'Sarıçam', 'saricam', 'active', '2025-10-15 18:49:44'),
(754, 448, 'Seyhan', 'seyhan', 'active', '2025-10-15 18:49:44'),
(755, 448, 'Tufanbeyli', 'tufanbeyli', 'active', '2025-10-15 18:49:44'),
(756, 448, 'Yumurtalık', 'yumurtalik', 'active', '2025-10-15 18:49:44'),
(757, 448, 'Yüreğir', 'yuregir', 'active', '2025-10-15 18:49:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `neighborhoods`
--

CREATE TABLE `neighborhoods` (
  `id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('active','deleted') DEFAULT 'active',
  `population` int(11) DEFAULT 0,
  `area` decimal(10,2) DEFAULT 0.00,
  `postal_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `neighborhoods`
--

INSERT INTO `neighborhoods` (`id`, `district_id`, `name`, `status`, `population`, `area`, `postal_code`, `created_at`, `updated_at`) VALUES
(212, 635, 'Acıbadem', 'active', 25000, 2.50, '34710', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(213, 635, 'Bostancı', 'active', 45000, 3.20, '34744', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(214, 635, 'Caddebostan', 'active', 35000, 2.80, '34728', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(215, 635, 'Erenköy', 'active', 28000, 2.10, '34738', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(216, 635, 'Fenerbahçe', 'active', 22000, 1.80, '34726', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(217, 635, 'Göztepe', 'active', 32000, 2.30, '34730', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(218, 635, 'Koşuyolu', 'active', 18000, 1.50, '34718', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(219, 635, 'Moda', 'active', 15000, 1.20, '34710', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(220, 635, 'Suadiye', 'active', 25000, 2.00, '34740', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(221, 622, 'Arnavutköy', 'active', 12000, 1.80, '34345', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(222, 622, 'Bebek', 'active', 8000, 1.20, '34342', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(223, 622, 'Etiler', 'active', 15000, 1.50, '34337', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(224, 622, 'Levent', 'active', 20000, 2.10, '34330', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(225, 622, 'Ortaköy', 'active', 10000, 1.00, '34347', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(226, 622, 'Ulus', 'active', 12000, 1.30, '34340', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(227, 647, 'Bomonti', 'active', 18000, 1.80, '34381', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(228, 647, 'Cumhuriyet', 'active', 15000, 1.50, '34380', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(229, 647, 'Esentepe', 'active', 12000, 1.20, '34394', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(230, 647, 'Harbiye', 'active', 10000, 1.00, '34367', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(231, 647, 'Mecidiyeköy', 'active', 25000, 2.50, '34387', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(232, 647, 'Nişantaşı', 'active', 8000, 0.80, '34365', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(233, 658, 'Çukurambar', 'active', 35000, 3.50, '06520', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(234, 658, 'Kızılay', 'active', 25000, 2.50, '06420', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(235, 658, 'Kocatepe', 'active', 20000, 2.00, '06420', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(236, 658, 'Kızılcahamam', 'active', 15000, 1.50, '06420', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(237, 658, 'Tunalı', 'active', 18000, 1.80, '06420', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(238, 658, 'Ümitköy', 'active', 30000, 3.00, '06810', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(239, 668, 'Etlik', 'active', 40000, 4.00, '06010', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(240, 668, 'Ovacık', 'active', 25000, 2.50, '06280', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(241, 668, 'Şenlik', 'active', 30000, 3.00, '06280', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(242, 668, 'Yenidoğan', 'active', 35000, 3.50, '06280', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(243, 697, 'Alsancak', 'active', 20000, 2.00, '35220', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(244, 697, 'Basmane', 'active', 15000, 1.50, '35240', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(245, 697, 'Çankaya', 'active', 12000, 1.20, '35220', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(246, 697, 'Güzelyalı', 'active', 18000, 1.80, '35290', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(247, 697, 'Kemeraltı', 'active', 10000, 1.00, '35250', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(248, 697, 'Pasaport', 'active', 8000, 0.80, '35220', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(249, 693, 'Alaybey', 'active', 15000, 1.50, '35580', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(250, 693, 'Bostanlı', 'active', 25000, 2.50, '35590', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(251, 693, 'Çiğli', 'active', 20000, 2.00, '35620', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(252, 693, 'Mavişehir', 'active', 30000, 3.00, '35590', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(253, 693, 'Mersinli', 'active', 18000, 1.80, '35580', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(254, 721, 'Çekirge', 'active', 25000, 2.50, '16020', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(255, 721, 'Emir Sultan', 'active', 20000, 2.00, '16330', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(256, 721, 'Hamitler', 'active', 30000, 3.00, '16120', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(257, 721, 'Soğanlı', 'active', 35000, 3.50, '16120', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(258, 721, 'Yıldırım', 'active', 40000, 4.00, '16330', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(259, 741, 'Altındağ', 'active', 30000, 3.00, '07100', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(260, 741, 'Çağlayan', 'active', 25000, 2.50, '07200', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(261, 741, 'Fener', 'active', 20000, 2.00, '07100', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(262, 741, 'Lara', 'active', 40000, 4.00, '07110', '2025-10-15 18:42:37', '2025-10-15 18:42:37'),
(263, 741, 'Meltem', 'active', 35000, 3.50, '07100', '2025-10-15 18:42:37', '2025-10-15 18:42:37');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `property_type` enum('daire','villa','arsa','isyeri','ofis','depo') NOT NULL,
  `transaction_type` enum('satilik','kiralik','gunluk-kiralik') NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `area` decimal(10,2) DEFAULT NULL,
  `room_count` int(11) DEFAULT NULL,
  `living_room_count` int(11) DEFAULT NULL,
  `bathroom_count` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `building_age` int(11) DEFAULT NULL,
  `heating_type` varchar(100) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `urgent` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','pending','sold','rented') DEFAULT 'pending',
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `featured_highlighted` tinyint(1) DEFAULT 0 COMMENT 'Ã–ne Ã§Ä±kan ilanlar iÃ§in iÅŸaretleme'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `properties`
--

INSERT INTO `properties` (`id`, `title`, `slug`, `description`, `price`, `property_type`, `transaction_type`, `city_id`, `district_id`, `address`, `area`, `room_count`, `living_room_count`, `bathroom_count`, `floor`, `building_age`, `heating_type`, `main_image`, `images`, `features`, `contact_name`, `contact_phone`, `contact_email`, `featured`, `urgent`, `status`, `views`, `created_at`, `updated_at`, `featured_highlighted`) VALUES
(28, 'Bahçeli Ev', 'bahceli-ev-konak', 'Geniş bahçeli, 4+1 bahçeli ev. Çocuklu aileler için ideal.', 1800000.00, 'daire', 'satilik', 482, 697, 'Alsancak Mahallesi, Konak/İzmir', 200.00, 4, 0, 2, 0, 0, '', 'http://localhost/emlak/uploads/properties/property_1760561724_0_68f00a3c270a1.jpg', '[\"https:\\/\\/images.unsplash.com\\/photo-1600585154526-990dced4db0d?w=800&h=600&fit=crop\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561724_0_68f00a3c270a1.jpg\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561724_1_68f00a3c272c4.jpg\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561724_2_68f00a3c274a2.jpg\"]', '', '', '', '', 0, 0, 'sold', 1, '2025-10-15 18:42:37', '2025-10-15 20:55:24', 0),
(29, 'Ofis Dairesi', 'ofis-dairesi-sisli', 'Modern ofis binasında, 2+1 ofis dairesi. İş merkezi konumunda.', 12000.00, 'ofis', 'kiralik', 481, 647, 'Mecidiyeköy Mahallesi, Şişli/İstanbul', 75.00, 2, NULL, 1, NULL, NULL, NULL, NULL, '[\"https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800&h=600&fit=crop\"]', '[\"İş Merkezi\", \"Metro Yakın\", \"Klima\", \"Güvenlik\"]', NULL, NULL, NULL, 0, 0, 'active', 2, '2025-10-15 18:42:37', '2025-10-15 20:55:35', 0),
(30, 'Arsa', 'arsa-nilufer', 'İmar planı uygun, yatırım için ideal arsa.', 450000.00, 'arsa', 'satilik', 463, 718, 'Görükle Mahallesi, Nilüfer/Bursa', 500.00, 0, NULL, 0, NULL, NULL, NULL, NULL, '[\"https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop\"]', '[\"İmar Uygun\", \"Yatırım\", \"Ulaşım\"]', NULL, NULL, NULL, 0, 0, 'active', 5, '2025-10-15 18:42:37', '2025-10-15 20:30:01', 0),
(32, 'Ticari İşyeri Satılık - 7', 'ticari-isyeri-satilik-7', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1056000.00, 'isyeri', 'satilik', 454, 733, 'İbradı, Antalya', 135.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, NULL, 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:56:53', '2025-10-15 20:56:53', 0),
(33, 'Depo Kiralık - 14', 'depo-kiralik-14', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 3605.00, 'depo', 'kiralik', 448, 749, 'Karataş, Adana', 330.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, NULL, 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 0, '2025-10-15 20:56:53', '2025-10-15 20:56:53', 0),
(34, 'Günlük Kiralık Villa - 21', 'gunluk-kiralik-villa-21', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 896.00, 'villa', 'gunluk-kiralik', 482, 701, 'Ödemiş, İzmir', 188.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, NULL, 'Ali Veli', '0536 567 89 01', 'ali@example.com', 1, 0, 'active', 0, '2025-10-15 20:56:53', '2025-10-15 21:41:29', 0),
(35, 'Günlük Kiralık Villa - 28', 'gunluk-kiralik-villa-28', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 872.00, 'villa', 'gunluk-kiralik', 481, 636, 'Kağıthane, İstanbul', 196.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, NULL, 'Ali Veli', '0536 567 89 01', 'ali@example.com', 1, 0, 'active', 0, '2025-10-15 20:56:53', '2025-10-15 21:41:29', 0),
(36, 'Arsa Satılık - 4', 'arsa-satilik-4', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 522000.00, 'arsa', 'satilik', 448, 757, 'Yüreğir, Adana', 450.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:11', '2025-10-15 20:57:11', 0),
(37, 'Ticari İşyeri Satılık - 10', 'ticari-isyeri-satilik-10', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 972000.00, 'isyeri', 'satilik', 482, 705, 'Torbalı, İzmir', 147.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:11', '2025-10-15 20:57:11', 0),
(38, 'Kiralık 2+1 Daire - 16', 'kiralik-21-daire-16', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 4320.00, 'daire', 'kiralik', 481, 636, 'Kağıthane, İstanbul', 78.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:11', '2025-10-15 20:57:11', 0),
(39, 'Ofis Kiralık - 22', 'ofis-kiralik-22', 'Modern ofis binasında, 2. katta, 3 odalı ofis. Toplantı salonu dahil.', 7670.00, 'ofis', 'kiralik', 481, 648, 'Tuzla, İstanbul', 127.00, 3, 0, 1, 2, 5, 'klima', NULL, NULL, '', 'Zeynep Ak', '0537 678 90 12', 'zeynep@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:11', '2025-10-15 20:57:11', 0),
(40, 'Günlük Kiralık Villa - 1', 'gunluk-kiralik-villa-1', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 864.00, 'villa', 'gunluk-kiralik', 448, 613, 'Adalar, Adana', 212.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, '', 'Ali Veli', '0536 567 89 01', 'ali@example.com', 1, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:43:50', 1),
(41, 'Lüks Villa Bahçeli - 2', 'luks-villa-bahceli-2', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2825000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 260.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 1, 1, 'active', 1, '2025-10-15 20:57:26', '2025-10-15 21:43:50', 1),
(42, 'Günlük Kiralık Villa - 3', 'gunluk-kiralik-villa-3', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 792.00, 'villa', 'gunluk-kiralik', 448, 613, 'Adalar, Adana', 188.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, '', 'Ali Veli', '0536 567 89 01', 'ali@example.com', 1, 0, 'active', 1, '2025-10-15 20:57:26', '2025-10-15 21:43:50', 1),
(43, 'Lüks Villa Bahçeli - 4', 'luks-villa-bahceli-4', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2200000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 269.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 1, 1, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:43:50', 1),
(44, 'Kiralık 2+1 Daire - 5', 'kiralik-21-daire-5', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 4905.00, 'daire', 'kiralik', 448, 613, 'Adalar, Adana', 88.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 1, 0, 'active', 1, '2025-10-15 20:57:26', '2025-10-15 21:41:29', 0),
(45, 'Lüks Villa Bahçeli - 6', 'luks-villa-bahceli-6', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2300000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 308.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 0, 1, 'active', 1, '2025-10-15 20:57:26', '2025-10-15 21:41:39', 0),
(46, 'Ticari İşyeri Satılık - 7', 'ticari-isyeri-satilik-7-1', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1440000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 165.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(47, 'Günlük Kiralık Villa - 8', 'gunluk-kiralik-villa-8', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 768.00, 'villa', 'gunluk-kiralik', 448, 613, 'Adalar, Adana', 202.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, '', 'Ali Veli', '0536 567 89 01', 'ali@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:27:30', 0),
(48, 'Ticari İşyeri Satılık - 9', 'ticari-isyeri-satilik-9', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1344000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 155.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(49, 'Depo Kiralık - 10', 'depo-kiralik-10', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 2870.00, 'depo', 'kiralik', 448, 613, 'Adalar, Adana', 318.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, '', 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 1, '2025-10-15 20:57:26', '2025-10-15 21:41:39', 0),
(50, 'Ticari İşyeri Satılık - 11', 'ticari-isyeri-satilik-11', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1236000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 141.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(51, 'Lüks Villa Bahçeli - 12', 'luks-villa-bahceli-12', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2325000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 294.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 0, 1, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:25:13', 0),
(52, 'Lüks Villa Bahçeli - 13', 'luks-villa-bahceli-13', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2700000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 252.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 0, 1, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:25:13', 0),
(53, 'Günlük Kiralık Villa - 14', 'gunluk-kiralik-villa-14', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 808.00, 'villa', 'gunluk-kiralik', 448, 613, 'Adalar, Adana', 194.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, '', 'Ali Veli', '0536 567 89 01', 'ali@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:25:13', 0),
(54, 'Ticari İşyeri Satılık - 15', 'ticari-isyeri-satilik-15', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1248000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 144.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(55, 'Arsa Satılık - 16', 'arsa-satilik-16', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 526500.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 480.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(56, 'Arsa Satılık - 17', 'arsa-satilik-17', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 495000.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 465.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(57, 'Arsa Satılık - 18', 'arsa-satilik-18', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 418500.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 545.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(58, 'Ofis Kiralık - 19', 'ofis-kiralik-19', 'Modern ofis binasında, 2. katta, 3 odalı ofis. Toplantı salonu dahil.', 5265.00, 'ofis', 'kiralik', 448, 613, 'Adalar, Adana', 125.00, 3, 0, 1, 2, 5, 'klima', NULL, NULL, '', 'Zeynep Ak', '0537 678 90 12', 'zeynep@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(59, 'Kiralık 2+1 Daire - 20', 'kiralik-21-daire-20', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 4500.00, 'daire', 'kiralik', 448, 613, 'Adalar, Adana', 83.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(60, 'Ofis Kiralık - 21', 'ofis-kiralik-21', 'Modern ofis binasında, 2. katta, 3 odalı ofis. Toplantı salonu dahil.', 6500.00, 'ofis', 'kiralik', 448, 613, 'Adalar, Adana', 114.00, 3, 0, 1, 2, 5, 'klima', NULL, NULL, '', 'Zeynep Ak', '0537 678 90 12', 'zeynep@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(61, 'Kiralık 2+1 Daire - 22', 'kiralik-21-daire-22', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 4365.00, 'daire', 'kiralik', 448, 613, 'Adalar, Adana', 83.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(62, 'Depo Kiralık - 23', 'depo-kiralik-23', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 3745.00, 'depo', 'kiralik', 448, 613, 'Adalar, Adana', 321.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, '', 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(63, 'Ticari İşyeri Satılık - 24', 'ticari-isyeri-satilik-24', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1068000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 162.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(64, 'Ticari İşyeri Satılık - 25', 'ticari-isyeri-satilik-25', 'Ana cadde üzerinde, vitrinli, ticari işyeri. Mağaza veya ofis olarak kullanılabilir.', 1248000.00, 'isyeri', 'satilik', 448, 613, 'Adalar, Adana', 147.00, 0, 0, 1, 1, 10, 'klima', NULL, NULL, '', 'Fatma Özkan', '0535 456 78 90', 'fatma@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(65, 'Arsa Satılık - 26', 'arsa-satilik-26', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 463500.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 515.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(66, 'Kiralık 2+1 Daire - 27', 'kiralik-21-daire-27', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 5400.00, 'daire', 'kiralik', 448, 613, 'Adalar, Adana', 90.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(67, 'Depo Kiralık - 28', 'depo-kiralik-28', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 3570.00, 'depo', 'kiralik', 448, 613, 'Adalar, Adana', 297.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, '', 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(68, 'Arsa Satılık - 29', 'arsa-satilik-29', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 405000.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 545.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(69, 'Ofis Kiralık - 30', 'ofis-kiralik-30', 'Modern ofis binasında, 2. katta, 3 odalı ofis. Toplantı salonu dahil.', 6890.00, 'ofis', 'kiralik', 448, 613, 'Adalar, Adana', 127.00, 3, 0, 1, 2, 5, 'klima', NULL, NULL, '', 'Zeynep Ak', '0537 678 90 12', 'zeynep@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(70, 'Lüks Villa Bahçeli - 31', 'luks-villa-bahceli-31', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2950000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 305.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 1, 1, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:41:29', 0),
(71, 'Ofis Kiralık - 32', 'ofis-kiralik-32', 'Modern ofis binasında, 2. katta, 3 odalı ofis. Toplantı salonu dahil.', 6890.00, 'ofis', 'kiralik', 448, 613, 'Adalar, Adana', 131.00, 3, 0, 1, 2, 5, 'klima', NULL, NULL, '', 'Zeynep Ak', '0537 678 90 12', 'zeynep@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(72, 'Arsa Satılık - 33', 'arsa-satilik-33', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 517500.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 475.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(73, 'Kiralık 2+1 Daire - 34', 'kiralik-21-daire-34', 'Temiz, kullanıma hazır 2+1 daire. Merkezi ısıtma, asansörlü.', 5085.00, 'daire', 'kiralik', 448, 613, 'Adalar, Adana', 83.00, 2, 1, 1, 3, 8, 'dogalgaz', NULL, NULL, '', 'Ayşe Demir', '0534 345 67 89', 'ayse@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(74, 'Arsa Satılık - 35', 'arsa-satilik-35', 'İmarı uygun, 500m² arsa. Konut yapımına uygun, elektrik ve su bağlantısı mevcut.', 445500.00, 'arsa', 'satilik', 448, 613, 'Adalar, Adana', 490.00, 0, 0, 0, 0, 0, '', NULL, NULL, '', 'Mustafa Çelik', '0538 789 01 23', 'mustafa@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(75, 'Depo Kiralık - 36', 'depo-kiralik-36', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 4025.00, 'depo', 'kiralik', 448, 613, 'Adalar, Adana', 309.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, '', 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(76, 'Depo Kiralık - 37', 'depo-kiralik-37', 'Büyük depo alanı, yükleme rampası mevcut. Ticari kullanım için ideal.', 2835.00, 'depo', 'kiralik', 448, 613, 'Adalar, Adana', 273.00, 0, 0, 1, 1, 12, 'yok', NULL, NULL, '', 'Hasan Yıldız', '0539 890 12 34', 'hasan@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 20:57:26', 0),
(77, 'Merkezi Konumda 3+1 Daire - 38', 'merkezi-konumda-31-daire-38', 'Şehrin merkezinde, ulaşım imkanlarına yakın, modern 3+1 daire. Balkonlu, güney cepheli.', 977500.00, 'daire', 'satilik', 448, 613, 'Adalar, Adana', 125.00, 3, 1, 2, 5, 5, 'dogalgaz', NULL, NULL, '', 'Ahmet Yılmaz', '0532 123 45 67', 'ahmet@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:27:30', 0),
(78, 'Günlük Kiralık Villa - 39', 'gunluk-kiralik-villa-39', 'Tatil için ideal, deniz manzaralı villa. Havuzlu, bahçeli, 6 kişilik.', 928.00, 'villa', 'gunluk-kiralik', 448, 613, 'Adalar, Adana', 214.00, 3, 1, 2, 2, 3, 'klima', NULL, NULL, '', 'Ali Veli', '0536 567 89 01', 'ali@example.com', 0, 0, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:27:30', 0),
(79, 'Lüks Villa Bahçeli - 40', 'luks-villa-bahceli-40', 'Geniş bahçeli, havuzlu, 4+1 villa. Doğa ile iç içe, sakin bir bölgede.', 2425000.00, 'villa', 'satilik', 448, 613, 'Adalar, Adana', 283.00, 4, 1, 3, 2, 2, 'kombi', NULL, NULL, '', 'Mehmet Kaya', '0533 234 56 78', 'mehmet@example.com', 0, 1, 'active', 0, '2025-10-15 20:57:26', '2025-10-15 21:27:30', 0),
(80, 'Deneme İLanı ', 'deneme-ilani', 'acıklama', 14500000.00, 'daire', 'satilik', 449, 449, '', 250.00, 5, 2, 2, 1, 1, 'Doğalgaz', 'http://localhost/emlak/uploads/properties/property_1760561921_0_68f00b01007f2.jpg', '[\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561921_0_68f00b01007f2.jpg\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561921_1_68f00b01009f2.jpg\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561921_2_68f00b0100bb3.jpg\",\"http:\\/\\/localhost\\/emlak\\/uploads\\/properties\\/property_1760561921_3_68f00b0100d5b.jpg\"]', '[\"Asans\\u00f6r\",\"E\\u015fyal\\u0131\"]', '', '', '', 1, 0, 'active', 8, '2025-10-15 20:58:41', '2025-10-15 21:56:54', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `property_contacts`
--

CREATE TABLE `property_contacts` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `quarters`
--

CREATE TABLE `quarters` (
  `id` int(11) NOT NULL,
  `neighborhood_id` int(11) DEFAULT NULL,
  `district_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `population` int(11) DEFAULT 0,
  `postal_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
  `category` varchar(50) DEFAULT 'general',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Emlak Sitesi', 'text', 'general', 'Site adÄ±', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(2, 'site_description', 'TÃ¼rkiye\'nin en gÃ¼venilir emlak platformu2', 'text', 'general', 'Site aÃ§Ä±klamasÄ±', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(3, 'site_keywords', 'emlak, satÄ±lÄ±k, kiralÄ±k, daire, villa, arsa', 'text', 'general', 'Site anahtar kelimeleri', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(4, 'items_per_page', '12', 'number', 'general', 'Sayfa baÅŸÄ±na ilan sayÄ±sÄ±', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(5, 'contact_phone', '5459039584', 'number', 'contact', 'Ä°letiÅŸim telefonu', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(6, 'contact_email', 'info@emlaksitesi.com', 'text', 'contact', 'Ä°letiÅŸim e-postasÄ±', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(7, 'contact_address', 'Merkez Mahallesi, Emlak Caddesi No:123, BeÅŸiktaÅŸ/Ä°stanbul', 'text', 'contact', 'Ä°letiÅŸim adresi', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(8, 'working_hours', '', 'text', 'contact', 'Ã‡alÄ±ÅŸma saatleri', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(9, 'facebook_url', '', 'text', 'social', 'Facebook sayfasÄ± URL', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(10, 'twitter_url', 'https://x.com/?lang=tr', 'text', 'social', 'Twitter sayfasÄ± URL', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(11, 'instagram_url', 'https://www.instagram.com/', 'text', 'social', 'Instagram sayfasÄ± URL', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(12, 'linkedin_url', 'https://www.linkedn.com/', 'text', 'social', 'LinkedIn sayfasÄ± URL', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(13, 'youtube_url', '', 'text', 'social', 'YouTube kanalÄ± URL', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(14, 'google_analytics', '', 'text', 'seo', 'Google Analytics kodu', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(15, 'google_maps_api', '', 'text', 'seo', 'Google Maps API anahtarÄ±', '2025-10-15 19:53:14', '2025-10-15 21:17:32'),
(16, 'maintenance_mode', '0', 'boolean', 'system', 'BakÄ±m modu', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(17, 'enable_comments', '1', 'boolean', 'system', 'Yorum sistemi', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(18, 'enable_newsletter', '0', 'boolean', 'system', 'BÃ¼lten sistemi', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(19, 'two_factor_auth', '0', 'boolean', 'system', 'Ä°ki faktÃ¶rlÃ¼ kimlik doÄŸrulama', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(20, 'backup_frequency', 'weekly', 'text', 'system', 'Yedekleme sÄ±klÄ±ÄŸÄ±', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(21, 'admin_email', 'admin@emlaksitesi.com', 'text', 'system', 'Admin e-posta adresi', '2025-10-15 19:53:14', '2025-10-15 19:53:14'),
(22, 'test_setting', 'Test Değer', 'text', 'general', NULL, '2025-10-15 19:55:26', '2025-10-15 19:55:26'),
(135, 'site_icon', 'http://localhost/emlak/assets/images/favicon.png', 'text', 'general', 'Site ikonu (favicon) dosya yolu', '2025-10-15 20:06:07', '2025-10-15 20:11:40'),
(318, 'google_maps_iframe', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2345.766349588362!2d30.658993790117677!3d36.913527643100316!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14c38f5f4c76d2e5%3A0x2a9ef8af3faecc3e!2zQUTEsEwgQUvDnCAtIEVOIFVZR1VOIEbEsFlBVCBHQVJBTlTEsFPEsA!5e0!3m2!1str!2str!4v1760563030835!5m2!1str!2str', 'text', 'seo', 'Google Maps iframe embed URL', '2025-10-15 21:05:32', '2025-10-15 21:17:32'),
(349, 'maps_location_title', 'Konumumuz', 'text', 'seo', 'Ä°letiÅŸim sayfasÄ±ndaki harita bÃ¶lÃ¼mÃ¼nÃ¼n baÅŸlÄ±ÄŸÄ±', '2025-10-15 21:07:23', '2025-10-15 21:17:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `button_text`, `button_url`, `image`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Deneme', 'Slide2', 'Buton ', 'http://localhost/emlak/', 'http://localhost/emlak/uploads/sliders/slider_1760550636_68efdeece38e0.jpg', 3, 'active', '2025-10-15 17:50:36', '2025-10-15 17:51:09'),
(6, 'Denem', 'Slide 4', 'Hemen Al', 'http://localhost/emlak/satilik', 'http://localhost/emlak/uploads/sliders/slider_1760550713_68efdf39a43ee.jpg', 4, 'active', '2025-10-15 17:51:53', '2025-10-15 17:51:53'),
(8, 'Lüks Villalar', 'Deniz manzaralı, özel havuzlu villalar şimdi sizleri bekliyor', 'Detaylı İncele', 'http://localhost/emlak/ilanlar', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&h=1080&fit=crop', 2, 'active', '2025-10-15 17:55:25', '2025-10-15 21:58:38'),
(9, 'Modern Yaşam Alanları', 'Şehrin kalbinde, konforlu ve modern daireler', 'Hemen Görüntüle', '/satilik-daire', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&h=1080&fit=crop', 3, 'active', '2025-10-15 17:55:25', '2025-10-15 17:55:25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Tablo için indeksler `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_district` (`city_id`,`slug`);

--
-- Tablo için indeksler `neighborhoods`
--
ALTER TABLE `neighborhoods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_district_id` (`district_id`),
  ADD KEY `idx_name` (`name`);

--
-- Tablo için indeksler `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_featured_highlighted` (`featured_highlighted`);

--
-- Tablo için indeksler `property_contacts`
--
ALTER TABLE `property_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_property_id` (`property_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Tablo için indeksler `quarters`
--
ALTER TABLE `quarters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_neighborhood_id` (`neighborhood_id`),
  ADD KEY `idx_district_id` (`district_id`),
  ADD KEY `idx_name` (`name`);

--
-- Tablo için indeksler `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Tablo için indeksler `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=531;

--
-- Tablo için AUTO_INCREMENT değeri `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=758;

--
-- Tablo için AUTO_INCREMENT değeri `neighborhoods`
--
ALTER TABLE `neighborhoods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- Tablo için AUTO_INCREMENT değeri `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Tablo için AUTO_INCREMENT değeri `property_contacts`
--
ALTER TABLE `property_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `quarters`
--
ALTER TABLE `quarters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

--
-- Tablo için AUTO_INCREMENT değeri `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `neighborhoods`
--
ALTER TABLE `neighborhoods`
  ADD CONSTRAINT `neighborhoods_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `property_contacts`
--
ALTER TABLE `property_contacts`
  ADD CONSTRAINT `property_contacts_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `quarters`
--
ALTER TABLE `quarters`
  ADD CONSTRAINT `quarters_ibfk_1` FOREIGN KEY (`neighborhood_id`) REFERENCES `neighborhoods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quarters_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
