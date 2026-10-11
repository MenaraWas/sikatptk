-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: db_sikat_ptk
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatans`
--

DROP TABLE IF EXISTS `kegiatans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` bigint unsigned NOT NULL,
  `ptk_id` bigint unsigned DEFAULT NULL,
  `urutan` int unsigned NOT NULL DEFAULT '1',
  `jenis_pelatihan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_pelatihan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyelenggara` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `tahun_pelaksanaan` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_jpl` int unsigned NOT NULL DEFAULT '0',
  `file_sertifikat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned NOT NULL,
  `file_mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `status_verifikasi` enum('pending','terverifikasi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_verifikasi` text COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kegiatans_submission_id_foreign` (`submission_id`),
  KEY `kegiatans_ptk_id_foreign` (`ptk_id`),
  CONSTRAINT `kegiatans_ptk_id_foreign` FOREIGN KEY (`ptk_id`) REFERENCES `ptks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kegiatans_submission_id_foreign` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatans`
--

LOCK TABLES `kegiatans` WRITE;
/*!40000 ALTER TABLE `kegiatans` DISABLE KEYS */;
INSERT INTO `kegiatans` VALUES (1,1,34,1,'Online','tes pendidikan','dinas pendidikan','2026-10-07','2026',19,'sertifikat/2026/submission_1/kegiatan_1_1791520129_architecture-overview.pdf','architecture-overview.pdf',8020,'application/pdf',NULL,'pending',NULL,NULL,'2026-10-08 21:28:49','2026-10-08 21:28:49');
/*!40000 ALTER TABLE `kegiatans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_09_041058_create_ptks_table',2),(5,'2026_10_09_041059_create_submissions_table',2),(6,'2026_10_09_041100_create_kegiatans_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ptks`
--

DROP TABLE IF EXISTS `ptks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ptks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nuptk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kepegawaian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tugas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mapel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ptks_nip_index` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ptks`
--

LOCK TABLES `ptks` WRITE;
/*!40000 ALTER TABLE `ptks` DISABLE KEYS */;
INSERT INTO `ptks` VALUES (1,'ABDUL GHOFUR','196711211996031001','3471122111670001','6453745646200003','PNS','Laki-Laki','6281904045227','abd.ghofur2167@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(2,'ADINDA NORA KRISNA FEBRIZA','199802182025052009','3402095802980003',NULL,'PNS','Perempuan','6285156562410','adindafebriza@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(3,'AFIAH NUR AQINI S.Pd.',NULL,'3402064506950001',NULL,'Non PNS','Perempuan','6285876054323','nuraqiniafiah@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(4,'AGUS MURSIDI','197008082000031004','3402160808700003','2140748650200003','PNS','Laki-Laki','6281391197088','agusmursidi70@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(5,'AGUS RIFAT ASNAWAN S.Pd.I',NULL,NULL,NULL,NULL,NULL,'6281328041917',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(6,'AHMADAN',NULL,'3507101804910002',NULL,'Non PNS','Laki-Laki','6285604546779','ahmadan731@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(7,'AMALIA RAHMAWATI',NULL,'3402124504990001','0000000000000000','Non PNS','Perempuan','6289606927845','liamamalia87@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(8,'APRILIA RAHMAN S.Pd','199004232020122012','3402026304900001','0000000000000000','PNS','Perempuan','6285729043338','apriliarahman90@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(9,'ARIF SETIAWAN S.Pd.Jas.','198802282019031013','3401042802880001','6560766667130072','PNS','Laki-Laki','6285927434515','setiawan280288@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(10,'BUDI DWI PRAMONO',NULL,NULL,NULL,NULL,NULL,'6285200165327',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(11,'DWI NURCAHYO NUGROHO',NULL,NULL,NULL,NULL,NULL,'62895634361522',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(12,'DWI RATMININGSIH',NULL,NULL,NULL,NULL,NULL,'62',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(13,'DWI SYARIFUDIN',NULL,NULL,NULL,NULL,NULL,'6281802661033',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(14,'Dra. SITI NUR AINI','196711251998032001','3402016511670001','7457745647300003','PNS','Perempuan','6283836068958','sitinuraini582@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(15,'Drs. MUBTADIIN M.Pd.I.','196612122007011048','3402151212660004','45447446','PNS','Laki-Laki','628121596324','diinbjn@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(16,'Drs. PURWOSUSILO','196811121998031001','3404101211680001','84447466','PNS','Laki-Laki','6285643732110','Purwo.kalasan@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(17,'EDI SUMARNO','197002122005011002','3471131202700005','7534748650200023','PNS','Laki-Laki','6288225121794','edisumarno74102@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(18,'EDI SUSANTO S.H.','197504162024211004','3402161604750002',NULL,'PPPK','Laki-Laki','628985582267','data.pkn2021@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(19,'EKO BUDIARTO',NULL,NULL,NULL,NULL,NULL,'6288233808835',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(20,'FATKHURROZAK JOHAN MAULANA',NULL,'3315171612950001',NULL,'Non PNS','Laki-Laki','6285877367183','kazornahoj@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(21,'FATUR RAMA ARDIANSAH S.Kom,',NULL,NULL,NULL,NULL,NULL,'6285226470072',NULL,NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(22,'FITRIA ENDANG SUSANA S.Pd., M.Pd..','197908122006042009','3471075208790001','7144757658300023','PNS','Perempuan','628195059203','fit34end@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(23,'IKSAN TAUFIK HIDAYANTO S.Pd..','197907022005011002','3302020207790003','0033757658110063','PNS','Laki-Laki','628562646399','gurufisikaman@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(24,'IRANI TRISNANDA S.Pd,','199705052019032003','3401054505970001','0000000000000000','PNS','Perempuan','6281233919913','iranitrisnanda@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(25,'IS DWIYANTI','197305151999032004','3402085505730003','1847751652300022','PNS','Perempuan','6281328723554','isdwi2016@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(26,'ISNAN SHOBARI','197606142005011002','3402011406760001','8946754655200012','PNS','Laki-Laki','6283113167887','fadhluka76@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(27,'KARLINA S.Psi.','199208082020122028','3374064808920003','20400573','PNS','Perempuan','6285643143380','lacuzlina@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(28,'KEN AYU KARTIKANINGRUM S.Ag,','199706202020122022','3302136006970001','2040057319700200','PNS','Perempuan','6282324898202','ayuken97@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(29,'KHUZAIFAH','199207202023212054','3402136007920001','4052770671130083','PPPK','Perempuan','6285643611455','dexkhuza@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(30,'KURNIA OKTAVIANY S.PD.T','198510152023212043','3323095510850004',NULL,'PPPK','Perempuan','6285643605405','oktavianykurnia1585@gmail.com',NULL,NULL,1,'2026-10-08 21:13:09','2026-10-08 21:13:09'),(31,'MARJIYAH','196907271998032002','3402026707690003','4059747648300023','PNS','Perempuan','6287762455789','marjiyah784@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(32,'MARYANTO',NULL,NULL,NULL,NULL,NULL,'6285799258333',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(33,'MAS INDAH MURDANINGRUM','198012282005012011','3404046812800004','3560758660300013','PNS','Perempuan','6285643841282','alkhalifimaninda@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(34,'MENARA LINTANG WAS',NULL,'3404012703010004',NULL,'Non PNS','Laki-Laki','6287824390933','menaralintang7@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(35,'MUH BURHAN S.T..','197211112023211003','3402081111720001','20400573','PPPK','Laki-Laki','62895323286220','hanee_ir@yahoo.co.id',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(36,'MUHAMMAD NURUL HUDA',NULL,NULL,NULL,NULL,NULL,'6285524817652',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(37,'MURTI MADIYANTI','199102242025052002','3402066402910001','8556769670230222','PNS','Perempuan','628562924063','murti.madiyanti@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(38,'MUSLIMAH','198105092005012002','3402084905810001','7841759659300002','PNS','Perempuan','62895355519605','muslimahzaki6@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(39,'NASIRUDIN S.Ag.','197207242003121002','3402132407720002','7056750651200003','PNS','Laki-Laki','62882003716370','udin1972023@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(40,'NUR HASANAH RAHMAWATI','196901301994032002','3404087001690001','5462747648300022','PNS','Perempuan','6282323698228','nurhasanahrahmawati69@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(41,'NURHAYATI S.PD.','197104141999032002','3402015404710001','47467496','PNS','Perempuan',NULL,NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(42,'NURJANAH S.Pd..',NULL,NULL,NULL,NULL,NULL,'6282127001320',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(43,'PREMA FADKHUL JANNAH',NULL,NULL,NULL,NULL,NULL,'6288226414339',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(44,'PUJI LESTARI','197407021999032001','3402084207740001','7034752653300023','PNS','Perempuan','6281359193510','pujispd0@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(45,'QISTHI NUR FIANAFIS S.Pd..',NULL,'3402085810960001',NULL,'Non PNS','Perempuan','6288226687039','qistifiana96@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(46,'R. HARDI SANTOSO S.Pd.','196807272006041001','3402062707680001','20597466','PNS','Laki-Laki','6281392538063','santosohardi91@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(47,'RETNO MARUTI DIAH SETYORINI SIP',NULL,NULL,NULL,NULL,NULL,'6283195088988',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(48,'RIYADI SETYAWAN S.Sn,','199204042020121021','3309090404920001','2040057319200100','PNS','Laki-Laki','6285747233944','Riyadi.setyawan123@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(49,'RIYANNANDA MARWANTO','200001022025051008','3402020201000002',NULL,'PNS','Laki-Laki','6285727256320','riyannanda042@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(50,'ROSITA MAYSARAH S.Sos.I,',NULL,NULL,NULL,NULL,NULL,'62895328804477',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(51,'SAMSUDIN',NULL,NULL,NULL,NULL,NULL,'6287843193438',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(52,'SAPTI WAHYUNI, S.PD S.Pd., M.Pd','197509032005012002','3402014309750004','5235753654300013','PNS','Perempuan','6289608622223','saptiakmal1@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(53,'SARYANTO','196902201999031001','3402082002690003','5552747649200012','PNS','Laki-Laki','6282324586292','ssaryanto33@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(54,'SITI ISTIKOMATUN S.PD',NULL,NULL,NULL,NULL,NULL,'6281578705075',NULL,NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(55,'SRI SUHARTUN',NULL,'3402075706870002','4949765666130112','Non PNS','Perempuan','6287838956229','srisuhartun344@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(56,'SRI SUHARYANTI S.Pd..','198010072005012005','3471144710800001','93397586','PNS','Perempuan','6281343259801','dzakyanti@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(57,'SUBUR CAHYONO',NULL,'3315023110890003','0000000000000000','Non PNS','Laki-Laki','6282300034541','kang.suburcahyonoputra@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(58,'SUMARDIASIH S.Pd.','197601252005012002','3402016501760003','34577546','PNS','Perempuan','6289638814667','asiha6146@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(59,'TEGUH SANTOSO, S.PD S.Pd..','197110012000121001','3402090110710001','0333749652200013','PNS','Laki-Laki','62857870442959','teguhsantoso011071@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(60,'TRI NURYANTI S.Pd,',NULL,'3402086012830003','20400573','Non PNS','Perempuan','628981944674','nuryantitri105@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(61,'TRY NUR FAUZI IMANUDDIN','199305132024211024','3515081305930007',NULL,'PPPK','Laki-Laki','628998176571','trynurfauzy13@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(62,'TUJILAH S.Pd.','197311302005012001','3402087011730001','6462751652300003','PNS','Perempuan','6281328208440','atujilah@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(63,'TUTI MULYATI S.Pd..','199005192023212041','6171055905900009',NULL,'PPPK','Perempuan','6285245378774','tutimulyati1990@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(64,'UMI FATONAH PRIMASTUTI S.Pd.','197004252007012020','3402086504700001','47577486','PNS','Perempuan','62089606919662','fatonahumi52@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(65,'WAHYU NUR AFRITA','199904252025052004','3403156504990002','2757777678230092','PNS','Perempuan','6289657733663','wahyunurafrita@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(66,'YANUANITA WIDIYANINGRUM','197501082005012004','3402084801750004','6440753654300032','PNS','Perempuan','6281328747118','denita.widia@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10'),(67,'ZULI SUSANTI','197007081999032001','3402024807700001','0040748650300033','PNS','Perempuan','6281328822968','zulisanti.08@gmail.com',NULL,NULL,1,'2026-10-08 21:13:10','2026-10-08 21:13:10');
/*!40000 ALTER TABLE `ptks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5bcQvRwAvuVQCcWnwDbe0TjWvTB9unCatG9i4vgQ',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJIekxsY2o2dFZObkl3YTBLYVg2S3NiVG45MFhXRHd5aUpVRXFyUEZDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9kYXRhLWtlZ2lhdGFuIiwicm91dGUiOiJyZWthcC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1791520147);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ptk_id` bigint unsigned DEFAULT NULL,
  `nama_pengisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_kegiatan` int unsigned NOT NULL DEFAULT '1',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `submissions_batch_code_unique` (`batch_code`),
  KEY `submissions_ptk_id_foreign` (`ptk_id`),
  CONSTRAINT `submissions_ptk_id_foreign` FOREIGN KEY (`ptk_id`) REFERENCES `ptks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
INSERT INTO `submissions` VALUES (1,'SIKAT-20261009-FVWRQ',34,'MENARA LINTANG WAS',NULL,'Laki-Laki','6287824390933',NULL,1,NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','2026-10-08 21:28:49','2026-10-08 21:28:49');
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator MAN 2 Bantul','admin@man2bantul.sch.id',NULL,'$2y$12$dFw4PBquz2o33oGmjm6INuft6UfZwkE5SPts7rCvbMLwfv5Bsp6fS',NULL,'2026-10-08 21:13:09','2026-10-08 21:13:09');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-09 11:29:51
