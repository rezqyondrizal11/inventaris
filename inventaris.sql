/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.13-MariaDB-log : Database - inventaris
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `barang` */

DROP TABLE IF EXISTS `barang`;

CREATE TABLE `barang` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `kondisi` varchar(255) DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `id_kat_barang` bigint(20) DEFAULT NULL,
  `id_penyewaan` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kat_barang` (`id_kat_barang`),
  KEY `id_penyewaan` (`id_penyewaan`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kat_barang`) REFERENCES `kat_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_penyewaan`) REFERENCES `kat_penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `barang` */

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `id_jc` bigint(20) DEFAULT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_jc` (`id_jc`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_jc`) REFERENCES `jenis_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `customer` */

insert  into `customer`(`id`,`kode`,`nama`,`email`,`telepon`,`alamat`,`id_jc`,`id_user`) values 
(2,'CUS308','Rezqy ondrizal','rezqyondrizal@gmail.com','082391369677','jalan purus 2 no 13 rt003 rw 003',2,5);

/*Table structure for table `jenis_customer` */

DROP TABLE IF EXISTS `jenis_customer`;

CREATE TABLE `jenis_customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `jenis_customer` */

insert  into `jenis_customer`(`id`,`name`) values 
(2,'rumah sakit');

/*Table structure for table `kat_barang` */

DROP TABLE IF EXISTS `kat_barang`;

CREATE TABLE `kat_barang` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `kat_barang` */

insert  into `kat_barang`(`id`,`name`) values 
(3,'Tabung'),
(4,'Selang');

/*Table structure for table `kat_penyewaan` */

DROP TABLE IF EXISTS `kat_penyewaan`;

CREATE TABLE `kat_penyewaan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `kat_penyewaan` */

insert  into `kat_penyewaan`(`id`,`name`) values 
(3,'Tidak Termasuk'),
(4,'Berisi'),
(5,'Kosong');

/*Table structure for table `pembelian` */

DROP TABLE IF EXISTS `pembelian`;

CREATE TABLE `pembelian` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_supplier` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_supplier` (`id_supplier`),
  CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `pembelian` */

/*Table structure for table `pembelian_customer` */

DROP TABLE IF EXISTS `pembelian_customer`;

CREATE TABLE `pembelian_customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_penjualan` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `id_supir` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `sisa` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_penjualan` (`id_penjualan`),
  KEY `id_customer` (`id_customer`),
  CONSTRAINT `pembelian_customer_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembelian_customer_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `pembelian_customer` */

/*Table structure for table `penjemputan` */

DROP TABLE IF EXISTS `penjemputan`;

CREATE TABLE `penjemputan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `id_cat_sewa` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_customer` (`id_customer`),
  KEY `id_cat_sewa` (`id_cat_sewa`),
  CONSTRAINT `penjemputan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penjemputan_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penjemputan_ibfk_3` FOREIGN KEY (`id_cat_sewa`) REFERENCES `kat_penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `penjemputan` */

/*Table structure for table `penjualan` */

DROP TABLE IF EXISTS `penjualan`;

CREATE TABLE `penjualan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_supir` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `jumlah_awal` bigint(20) DEFAULT NULL,
  `jumlah_masuk` bigint(20) DEFAULT NULL,
  `jumlah_keluar` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_supir` (`id_supir`),
  CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`id_supir`) REFERENCES `supir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `penjualan` */

/*Table structure for table `penyewaan` */

DROP TABLE IF EXISTS `penyewaan`;

CREATE TABLE `penyewaan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `tanggal_selesai` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_supir` (`id_supir`),
  KEY `id_customer` (`id_customer`),
  KEY `id_cat_sewa` (`id_cat_sewa`),
  CONSTRAINT `penyewaan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penyewaan_ibfk_2` FOREIGN KEY (`id_supir`) REFERENCES `supir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penyewaan_ibfk_3` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penyewaan_ibfk_4` FOREIGN KEY (`id_cat_sewa`) REFERENCES `kat_penyewaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `penyewaan` */

/*Table structure for table `permintaan` */

DROP TABLE IF EXISTS `permintaan`;

CREATE TABLE `permintaan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_customer` bigint(20) DEFAULT NULL,
  `stok` bigint(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_barang` (`id_barang`),
  KEY `id_customer` (`id_customer`),
  CONSTRAINT `permintaan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permintaan_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `permintaan` */

/*Table structure for table `supir` */

DROP TABLE IF EXISTS `supir`;

CREATE TABLE `supir` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `supir` */

insert  into `supir`(`id`,`kode`,`nama`,`email`,`telepon`,`alamat`) values 
(3,'SPR257','ronal','ronal@gmail.com','2312312312','jl piris 2');

/*Table structure for table `supplier` */

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `supplier` */

insert  into `supplier`(`id`,`kode`,`nama`,`email`,`telepon`,`alamat`) values 
(3,'SPPR181','pt abadi jaya','abadijaya@gmail.cpm','32113sd','dsadas');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`role`) values 
(2,'admin','admin1@gmail.com','$2y$10$GktHPAsc2blh0L0iGZdyyOb658LxAGRuqAIlXjOCV7SbnArE/mQXW','admin'),
(3,'pegawai','pegawai@gmail.com','$2y$10$8YAwvcIj0e6LZC4ZtcMA2efsEcdQQFkZ2DJ95Qmve8Ch.a9d7Ay/2','pegawai'),
(4,'pemimpin','pemimpin@gmail.com','$2y$10$c55yoznsvFKqKDWLQpHmueudp4RMXKendZKlr5WyBqTBe.SSwlHR6','pemimpin'),
(5,'customer','customer@gmail.com','$2y$10$Vybz2v33YttwaJRpFl5.b.8bddSpzeW2eR/AA6b39mD/MnsmuhXOa','customer');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
