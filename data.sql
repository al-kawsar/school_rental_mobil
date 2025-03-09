-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2023 at 07:59 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codekop_free_rental_mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `ktp` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_tlp` varchar(15) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `lama_sewa` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `konfirmasi_pembayaran` varchar(255) NOT NULL,
  `tgl_input` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_login`, `id_mobil`, `ktp`, `nama`, `alamat`, `no_tlp`, `tanggal`, `lama_sewa`, `total_harga`, `konfirmasi_pembayaran`, `tgl_input`) VALUES
(1, '1576329294', 3, 5, '231423123', 'Krisna', 'Bekasi', '08132312321', '2019-12-28', 2, 400000, 'Pembayaran di terima', '2019-12-14'),
(2, '1576671989', 3, 5, '231423', 'Krisna Waskita', 'Bekasi Ujung Harapan', '082391273127', '2019-12-20', 2, 400525, 'Pembayaran di terima', '2019-12-18'),
(3, '1642998828', 3, 5, '1283821832813', 'Krisna', 'Bekasi', '089618173609', '2022-01-26', 4, 800743, 'Pembayaran di terima', '2022-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `infoweb`
--

CREATE TABLE `infoweb` (
  `id` int(11) NOT NULL,
  `nama_rental` varchar(255) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_rek` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `infoweb`
--

INSERT INTO `infoweb` (`id`, `nama_rental`, `telp`, `alamat`, `email`, `no_rek`, `updated_at`) VALUES
(1, 'Al Rental', '081298669897', 'JL GB Saraung LR 254/12 A', 'alkawsar@gmail.com', 'BRI A/N Andi Muh Raihan Alkawsar 123123213123', '2022-01-24 04:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL,
  `nama_pengguna` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id_login`, `nama_pengguna`, `username`, `password`, `level`) VALUES
(1, 'Alkawsar', 'admin', '202cb962ac59075b964b07152d234b70', 'admin'),
(3, 'Abd Rahman', 'bom', '202cb962ac59075b964b07152d234b70', 'pengguna');

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `no_plat` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `harga` int(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `gambar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `no_plat`, `merk`, `harga`, `deskripsi`, `status`, `gambar`) VALUES
(5, 'N34234', 'Avanza', 200000, 'Apa aja', 'Tidak Tersedia', 'avanza.jpg'),
(6, 'N 1232 BKT', 'New Xenia', 500000, 'Baru', 'Tersedia', 'xenia.jpg');

-- --------------------------------------------------------



--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_booking` int(255) NOT NULL,
  `no_rekening` int(255) NOT NULL,
  `nama_rekening` varchar(255) NOT NULL,
  `nominal` int(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) VALUES
(3, 1, 2131241, 'Fachri', 400000, '2019-12-14'),
(4, 2, 2131241, 'Fachri', 400525, '2019-12-18'),
(5, 3, 13213, 'Angga', 800743, '2022-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `denda` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
ADD PRIMARY KEY (`id_booking`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
ADD PRIMARY KEY (`id_mobil`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
ADD PRIMARY KEY (`id_pengembalian`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




-- Dummy data for login table (users)
INSERT INTO `login` (`nama_pengguna`, `username`, `password`, `level`) VALUES
('Budi Santoso', 'budi1', '202cb962ac59075b964b07152d234b70', 'pengguna'),
('Dewi Lestari', 'dewi2', '202cb962ac59075b964b07152d234b70', 'pengguna'),
('Agus Pratama', 'agus3', '202cb962ac59075b964b07152d234b70', 'pengguna'),
('Maya Sari', 'maya4', '202cb962ac59075b964b07152d234b70', 'pengguna');

-- Dummy data for mobil table (cars)
INSERT INTO `mobil` (`no_plat`, `merk`, `harga`, `deskripsi`, `status`, `gambar`) VALUES
('B 1234 CDE', 'Toyota Innova', 350000, 'Toyota Innova 2022 dengan transmisi otomatis, 7 kursi, AC, dan fitur keamanan lengkap.', 'Tersedia', 'innova.jpg'),
('B 5678 FGH', 'Honda Civic', 450000, 'Honda Civic terbaru, transmisi otomatis, 5 kursi nyaman, AC dual zone, dan system audio premium.', 'Tersedia', 'civic.jpg'),
('B 9012 IJK', 'Mitsubishi Pajero', 700000, 'SUV premium 7 kursi dengan transmisi otomatis, AC, sunroof, dan system keamanan lengkap.', 'Tersedia', 'pajero.jpg'),
('B 3456 LMN', 'Toyota Rush', 300000, 'SUV kompak 7 kursi, transmisi otomatis, AC, dan system keamanan standar.', 'Tersedia', 'rush.jpg'),
('B 7890 OPQ', 'Daihatsu Ayla', 200000, 'City car ekonomis 5 kursi dengan transmisi manual, AC, dan konsumsi BBM efisien.', 'Tersedia', 'ayla.jpg'),
('B 1357 RST', 'Suzuki Ertiga', 320000, 'MPV keluarga 7 kursi dengan transmisi otomatis, AC, dan fitur keamanan lengkap.', 'Tersedia', 'ertiga.jpg'),
('B 2468 UVW', 'Honda HR-V', 450000, 'SUV kompak modern dengan transmisi otomatis, 5 kursi, AC, dan sistem infotainment terbaru.', 'Tersedia', 'hrv.jpg'),
('B 1122 XYZ', 'Toyota Fortuner', 750000, 'SUV premium 7 kursi dengan transmisi otomatis, AC, sunroof, dan fitur mewah lainnya.', 'Tersedia', 'fortuner.jpg');

-- Dummy data for booking table
INSERT INTO `booking` (`kode_booking`, `id_login`, `id_mobil`, `ktp`, `nama`, `alamat`, `no_tlp`, `tanggal`, `lama_sewa`, `total_harga`, `konfirmasi_pembayaran`, `tgl_input`) VALUES
('1676543210', 4, 8, '3275012345678901', 'Maya Sari', 'Jl. Merdeka No. 45, Jakarta Selatan', '081234567890', '2023-01-15', 3, 2250000, 'Pembayaran di terima', '2023-01-10'),
('1676543211', 5, 7, '3275123456789012', 'Joko Widodo', 'Jl. Pahlawan No. 17, Jakarta Pusat', '082345678901', '2023-01-20', 2, 900000, 'Pembayaran di terima', '2023-01-15'),
('1676543212', 6, 6, '3275234567890123', 'Budi Santoso', 'Jl. Sudirman No. 25, Jakarta Pusat', '083456789012', '2023-01-25', 4, 1280000, 'Pembayaran di terima', '2023-01-20'),
('1676543213', 7, 5, '3275345678901234', 'Dewi Lestari', 'Jl. Gatot Subroto No. 32, Jakarta Selatan', '084567890123', '2023-01-28', 2, 400000, 'Pembayaran di terima', '2023-01-25'),
('1676543214', 8, 9, '3275456789012345', 'Agus Pratama', 'Jl. Asia Afrika No. 18, Jakarta Barat', '085678901234', '2023-02-05', 5, 1750000, 'Menunggu konfirmasi', '2023-02-01'),
('1676543215', 4, 10, '3275012345678901', 'Maya Sari', 'Jl. Merdeka No. 45, Jakarta Selatan', '081234567890', '2023-02-10', 3, 1350000, 'Menunggu konfirmasi', '2023-02-07'),
('1676543216', 6, 11, '3275234567890123', 'Budi Santoso', 'Jl. Sudirman No. 25, Jakarta Pusat', '083456789012', '2023-02-15', 1, 320000, 'Menunggu konfirmasi', '2023-02-12');

-- Dummy data for pembayaran table
INSERT INTO `pembayaran` (`id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) VALUES
(4, 87654321, 'Maya Sari', 2250000, '2023-01-10'),
(5, 76543210, 'Joko Widodo', 900000, '2023-01-16'),
(6, 65432109, 'Budi Santoso', 1280000, '2023-01-21'),
(7, 54321098, 'Dewi Lestari', 400000, '2023-01-26'),
(8, 43210987, 'Agus Pratama', 1750000, '2023-02-02');

-- Dummy data for pengembalian table
INSERT INTO `pengembalian` (`kode_booking`, `tanggal`, `denda`) VALUES
('1676543210', '2023-01-18', 0),
('1676543211', '2023-01-22', 0),
('1676543212', '2023-01-30', 150000),
('1676543213', '2023-01-30', 0);