-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 03:25 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris1`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `kondisi` varchar(255) DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `id_kat_barang` bigint(20) DEFAULT NULL,
  `id_penyewaan` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `name`, `kode`, `kondisi`, `satuan`, `id_kat_barang`, `id_penyewaan`, `stok`, `jumlah_awal`, `jumlah_masuk`, `jumlah_keluar`) VALUES
(37, 'Oksigen Besar', 'BRG368', 'baik', 'pcs', 9, 4, 100, 100, 35, 35),
(38, 'LPG 50kg', 'BRG231', 'baik', 'pcs', 9, 4, 7, 5, 3, 1),
(39, 'Oksigen Besar', 'BRG598', 'baik', 'pcs', 9, 5, 3, 3, 1, 1),
(40, 'Acetylen', 'BRG913', 'baik', 'pcs', 9, 4, 5, 3, 2, 0),
(41, 'LPG 12kg', 'BRG293', 'baik', '100', 9, 4, -5, -5, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `id_jc` bigint(20) DEFAULT NULL,
  `id_user` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `kode`, `nama`, `email`, `telepon`, `alamat`, `id_jc`, `id_user`) VALUES
(5, 'CUS374', 'RSUD Painan', 'rsudpainan@gmail.com', '081234567892', 'Painan, Sumatera Barat', 2, 5),
(6, 'CUS397', 'Rina', 'rina123@gmail.com', '0807100221', 'Padang', 3, 0),
(8, 'CUS331', 'Yantoo', 'yanto@gmail.com', '0807100221', 'Padang', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_customer`
--

CREATE TABLE `jenis_customer` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jenis_customer`
--

INSERT INTO `jenis_customer` (`id`, `name`) VALUES
(2, 'Rumah sakit'),
(3, 'Individu'),
(7, 'Industri');

-- --------------------------------------------------------

--
-- Table structure for table `kat_barang`
--

CREATE TABLE `kat_barang` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kat_barang`
--

INSERT INTO `kat_barang` (`id`, `name`) VALUES
(9, 'Tabung'),
(11, 'Pen Tabung');

-- --------------------------------------------------------

--
-- Table structure for table `kat_penyewaan`
--

CREATE TABLE `kat_penyewaan` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kat_penyewaan`
--

INSERT INTO `kat_penyewaan` (`id`, `name`, `status`) VALUES
(4, 'Rumah Sakit', 0),
(5, 'Individu', 1),
(9, 'Industri', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint(20) NOT NULL,
  `id_permintaan` bigint(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `status` int(1) DEFAULT 1,
  `title` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `id_permintaan`, `url`, `ket`, `status`, `title`, `tanggal`) VALUES
(36, 47, 'proses_permintaan/proses/47', 'Ada permintaan dari customer  jumlah stok: 20', 0, NULL, '2025-03-03'),
(37, 48, 'proses_permintaan/proses/48', 'Ada permintaan dari customer  jumlah stok: 25', 0, NULL, '2025-03-10'),
(38, 49, 'proses_permintaan/proses/49', 'Ada permintaan dari customer  jumlah stok: 5', 0, NULL, '2025-03-10'),
(39, 50, 'proses_permintaan/proses/50', 'Ada permintaan dari customer  jumlah stok: 20', 0, NULL, '2025-03-10'),
(40, 51, 'proses_permintaan/proses/51', 'Ada permintaan dari customer  jumlah stok: 20', 0, NULL, '2025-03-10'),
(41, 52, 'proses_permintaan/proses/52', 'Ada permintaan dari customer  jumlah stok: 1', 0, NULL, '2025-03-10'),
(42, 53, 'proses_permintaan/proses/53', 'Ada permintaan dari customer  jumlah stok: 15', 0, NULL, '2025-03-11'),
(43, 54, 'proses_permintaan/proses/54', 'Ada permintaan dari customer  jumlah stok: 0', 1, NULL, '2025-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id` bigint(20) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_supplier` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `no_invoice` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id`, `id_barang`, `id_supplier`, `jumlah_awal`, `jumlah_masuk`, `jumlah_keluar`, `stok`, `tanggal`, `no_invoice`) VALUES
(18, 38, 4, 5, 3, 0, 8, '2025-03-10', 'INV-207'),
(19, 40, 4, 3, 2, 0, 5, '2025-03-11', 'INV-292');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_customer`
--

CREATE TABLE `pembelian_customer` (
  `id` bigint(20) NOT NULL,
  `id_penjualan` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `id_penyewaan` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `sisa` bigint(20) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pembelian_customer`
--

INSERT INTO `pembelian_customer` (`id`, `id_penjualan`, `id_customer`, `id_penyewaan`, `jumlah_masuk`, `jumlah_keluar`, `sisa`, `status`) VALUES
(49, 57, 5, NULL, 20, 20, 0, 2),
(52, 59, 5, NULL, 15, 15, 0, 2),
(53, 60, 6, NULL, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian_barang`
--

CREATE TABLE `pengembalian_barang` (
  `id` bigint(20) NOT NULL,
  `id_pc` bigint(20) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `stok_dikembalikan` bigint(20) DEFAULT NULL,
  `sisa` bigint(20) DEFAULT NULL,
  `id_supir` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pengembalian_barang`
--

INSERT INTO `pengembalian_barang` (`id`, `id_pc`, `id_barang`, `id_customer`, `stok_dikembalikan`, `sisa`, `id_supir`, `tanggal`, `status`, `volume`) VALUES
(39, 49, 37, 5, 20, 0, 4, '2025-03-10', 2, 'Kosong'),
(40, 52, 37, 5, 15, 0, 4, '2025-03-11', 2, 'Kosong');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` bigint(20) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_supir` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `id_barang`, `id_supir`, `id_customer`, `jumlah_awal`, `jumlah_masuk`, `jumlah_keluar`, `stok`, `tanggal`) VALUES
(57, 37, 4, 5, 100, 0, 20, 80, '2025-03-10'),
(59, 37, 4, 5, 100, 0, 15, 85, '2025-03-11'),
(60, 38, 3, 6, 8, 0, 1, 7, '2025-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id` bigint(20) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_supir` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `id_cat_sewa` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permintaan`
--

CREATE TABLE `permintaan` (
  `id` bigint(20) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `no_invoice` varchar(255) NOT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `ket` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `permintaan`
--

INSERT INTO `permintaan` (`id`, `id_barang`, `id_customer`, `no_invoice`, `stok`, `tanggal`, `status`, `ket`) VALUES
(51, 37, 5, 'INV-256', 20, '2025-03-10', 2, ''),
(53, 37, 5, 'INV-999', 15, '2025-03-11', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `supir`
--

CREATE TABLE `supir` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supir`
--

INSERT INTO `supir` (`id`, `kode`, `nama`, `email`, `telepon`, `alamat`) VALUES
(3, 'SPR257', 'Ronal', 'ronal@gmail.com', '2312312312', 'Jalan By Pass'),
(4, 'SPR737', 'Iyal', 'iyal@gmail.com', '0811111112', 'Padang');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `kode`, `nama`, `email`, `telepon`, `alamat`) VALUES
(4, 'SPPR347', 'Dedi', 'Dedi123@gmail.com', '0811111112', 'Padangg'),
(6, 'SPPR749', 'HSU', 'hsu@gmail.com', '082391369677', 'Padang');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(2, 'admin', 'admin1@gmail.com', '$2y$10$GktHPAsc2blh0L0iGZdyyOb658LxAGRuqAIlXjOCV7SbnArE/mQXW', 'admin'),
(3, 'pegawai', 'pegawai@gmail.com', '$2y$10$8YAwvcIj0e6LZC4ZtcMA2efsEcdQQFkZ2DJ95Qmve8Ch.a9d7Ay/2', 'pegawai'),
(4, 'pemimpin', 'pemimpin@gmail.com', '$2y$10$c55yoznsvFKqKDWLQpHmueudp4RMXKendZKlr5WyBqTBe.SSwlHR6', 'pemimpin'),
(5, 'RSUD Painan', 'customer@gmail.com', '$2y$10$Vybz2v33YttwaJRpFl5.b.8bddSpzeW2eR/AA6b39mD/MnsmuhXOa', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kat_barang` (`id_kat_barang`),
  ADD KEY `id_penyewaan` (`id_penyewaan`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jc` (`id_jc`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `jenis_customer`
--
ALTER TABLE `jenis_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kat_barang`
--
ALTER TABLE `kat_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kat_penyewaan`
--
ALTER TABLE `kat_penyewaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `pembelian_customer`
--
ALTER TABLE `pembelian_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_penyewaan` (`id_penyewaan`);

--
-- Indexes for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_supir` (`id_supir`);

--
-- Indexes for table `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_supir` (`id_supir`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_cat_sewa` (`id_cat_sewa`);

--
-- Indexes for table `permintaan`
--
ALTER TABLE `permintaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `supir`
--
ALTER TABLE `supir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jenis_customer`
--
ALTER TABLE `jenis_customer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kat_barang`
--
ALTER TABLE `kat_barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kat_penyewaan`
--
ALTER TABLE `kat_penyewaan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pembelian_customer`
--
ALTER TABLE `pembelian_customer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `permintaan`
--
ALTER TABLE `permintaan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `supir`
--
ALTER TABLE `supir`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kat_barang`) REFERENCES `kat_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_penyewaan`) REFERENCES `kat_penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_jc`) REFERENCES `jenis_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembelian_customer`
--
ALTER TABLE `pembelian_customer`
  ADD CONSTRAINT `pembelian_customer_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembelian_customer_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembelian_customer_ibfk_3` FOREIGN KEY (`id_penyewaan`) REFERENCES `penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`id_supir`) REFERENCES `supir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD CONSTRAINT `penyewaan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penyewaan_ibfk_2` FOREIGN KEY (`id_supir`) REFERENCES `supir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penyewaan_ibfk_3` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penyewaan_ibfk_4` FOREIGN KEY (`id_cat_sewa`) REFERENCES `kat_penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permintaan`
--
ALTER TABLE `permintaan`
  ADD CONSTRAINT `permintaan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permintaan_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
