-- MySQL dump 10.13  Distrib 26.10.0, for macos27.0 (arm64)
--
-- Host: localhost    Database: steel_erp
-- ------------------------------------------------------
-- Server version	26.10.0

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
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` int unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_module_action` (`module`,`action`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_actlog_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,1,'System','Login',1,'Administrator logged in to Steel ERP','127.0.0.1',NULL,'2026-10-28 16:14:10'),(2,2,'Quotations','Create',1,'Created quotation QTN-2627-001 for NTPC 800MW Project','127.0.0.1',NULL,'2026-10-04 17:14:10'),(3,1,'Sales Orders','Approve',1,'Approved Sales Order SO-2627-001 for NTPC Limited','127.0.0.1',NULL,'2026-10-08 17:14:10'),(4,4,'Production','Issue',1,'Issued 28 MT steel plates for Job JOB-2627-001','127.0.0.1',NULL,'2026-10-12 17:14:10'),(5,7,'Dispatch','Create',1,'Generated Delivery Challan DC-2627-001 with trailer RJ-14-GC-8842','127.0.0.1',NULL,'2026-10-22 17:14:10'),(6,5,'Invoices','Generate',1,'Generated GST Tax Invoice INV-2627-001 for ₹20,76,800','127.0.0.1',NULL,'2026-10-22 17:14:10'),(7,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:13:58'),(8,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:14:20'),(9,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:15:09'),(10,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:15:09'),(11,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:15:39'),(12,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:15:39'),(13,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:16:20'),(14,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:16:20'),(15,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:16:58'),(16,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:16:58'),(17,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:17:34'),(18,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:17:34'),(19,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:19:13'),(20,1,'GRN','Create',0,'Recorded inward GRN GRN-2627-004 (Challan: )','::1','Python-urllib/3.9','2026-10-28 12:19:13'),(21,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:19:19'),(22,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:19:48'),(23,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:19:55'),(24,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:20:52'),(25,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:21:11'),(26,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:21:24'),(27,1,'Clients','Create',7,'Added new client Tata Power Trombay Thermal Station (power_plant)','::1','Python-urllib/3.9','2026-10-28 12:21:24'),(28,1,'Production','Created Fabrication Job Order JOB-TEST-999',NULL,'','::1','Python-urllib/3.9','2026-10-28 12:21:24'),(29,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:21:42'),(30,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:22:18'),(31,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:23:43'),(32,1,'Dispatch','Generated Delivery Challan DC-TEST-888',NULL,'','::1','Python-urllib/3.9','2026-10-28 12:23:43'),(33,1,'Auth','Login',1,'User admin logged in successfully','::1','Python-urllib/3.9','2026-10-28 12:23:53'),(34,1,'Billing','Generated Tax Invoice INV-TEST-999 for ₹1,840,800.',NULL,'','::1','Python-urllib/3.9','2026-10-28 12:23:53'),(35,1,'Accounts','Recorded payment receipt RCT-TEST-999 of ₹1,840,80',NULL,'','::1','Python-urllib/3.9','2026-10-28 12:23:53');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
INSERT INTO `ci_sessions` VALUES ('ci_session:10107046e76af369984efee25ce15d61','::1','2026-10-28 12:13:09',_binary '__ci_last_regenerate|i:1793189589;_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";_ci_old_input|a:2:{s:3:\"get\";a:0:{}s:4:\"post\";a:3:{s:14:\"csrf_test_name\";s:32:\"e43e5d5401aedc316f271b5528cc0a71\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:9:\"Admin@123\";}}__ci_vars|a:2:{s:13:\"_ci_old_input\";s:3:\"old\";s:5:\"error\";s:3:\"old\";}error|s:41:\"Username/Email and Password are required.\";'),('ci_session:17c7514029d39fe7d478c444ab340b58','::1','2026-10-28 12:21:11',_binary '__ci_last_regenerate|i:1793190071;_ci_previous_url|s:47:\"http://localhost:8080/index.php/invoices/view/1\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}__ci_vars|a:0:{}'),('ci_session:1a223ba30d57f73d3883e2d356ff8c1f','::1','2026-10-28 12:17:34',_binary '__ci_last_regenerate|i:1793189853;_ci_previous_url|s:48:\"http://localhost:8080/index.php/employees/create\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:231dc8ba45c2f1a28c57ed52ff42dda8','::1','2026-10-28 12:15:39',_binary '__ci_last_regenerate|i:1793189739;_ci_previous_url|s:42:\"http://localhost:8080/index.php/dispatches\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:24dbff03ef26ad7e49991ee1ddb53c61','::1','2026-10-28 12:12:56',_binary '__ci_last_regenerate|i:1793189575;_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";redirect_url|s:40:\"http://localhost:8080/index.php/settings\";error|s:34:\"Please login to access the system.\";__ci_vars|a:1:{s:5:\"error\";s:3:\"old\";}'),('ci_session:2c78d7fe5ca47d04a2f3d173d031cd2e','::1','2026-10-28 12:15:09',_binary '__ci_last_regenerate|i:1793189709;_ci_previous_url|s:49:\"http://localhost:8080/index.php/production/issues\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:385fa4a1aa4da1a6d5eed12ab31f2f5a','::1','2026-10-28 12:20:52',_binary '__ci_last_regenerate|i:1793190052;_ci_previous_url|s:49:\"http://localhost:8080/index.php/dispatches/view/1\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:3d34a2b29cb5a7e082c87004b4488520','::1','2026-10-28 12:21:25',_binary '__ci_last_regenerate|i:1793190084;_ci_previous_url|s:49:\"http://localhost:8080/index.php/dispatches/create\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}_ci_old_input|a:2:{s:3:\"get\";a:0:{}s:4:\"post\";a:16:{s:14:\"csrf_test_name\";s:32:\"87b83143f265df84fdd7e6b8d2404ef4\";s:5:\"dc_no\";s:11:\"DC-TEST-999\";s:7:\"dc_date\";s:10:\"2026-10-28\";s:9:\"client_id\";s:1:\"1\";s:16:\"delivery_address\";s:52:\"NTPC Ramagundam Site Stage-IV, Karimnagar, Telangana\";s:10:\"vehicle_no\";s:13:\"MH 40 AK 8899\";s:11:\"transporter\";s:26:\"Jaipur Golden Transport Co\";s:5:\"lr_no\";s:9:\"JG-778899\";s:7:\"lr_date\";s:10:\"2026-10-28\";s:12:\"eway_bill_no\";s:12:\"281928374619\";s:12:\"total_weight\";s:6:\"24.500\";s:14:\"no_of_packages\";s:1:\"4\";s:16:\"item_description\";a:1:{i:0;s:35:\"Fabricated Heavy Box Columns DWG-01\";}s:13:\"item_quantity\";a:1:{i:0;s:1:\"4\";}s:9:\"item_unit\";a:1:{i:0;s:3:\"PCS\";}s:11:\"item_weight\";a:1:{i:0;s:6:\"24.500\";}}}__ci_vars|a:2:{s:13:\"_ci_old_input\";s:3:\"old\";s:5:\"error\";s:3:\"old\";}error|s:36:\"Failed to generate Delivery Challan.\";'),('ci_session:47ee3df6765ca6a055a4e36fdd770978','::1','2026-10-28 12:13:05',_binary '__ci_last_regenerate|i:1793189585;_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";_ci_old_input|a:2:{s:3:\"get\";a:0:{}s:4:\"post\";a:3:{s:14:\"csrf_test_name\";s:32:\"93f7c88a32dddffa664e79c9ef5465f8\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:9:\"Admin@123\";}}__ci_vars|a:2:{s:13:\"_ci_old_input\";s:3:\"old\";s:5:\"error\";s:3:\"old\";}error|s:41:\"Username/Email and Password are required.\";'),('ci_session:54994bb3386360b131c9c271c8a06fbf','::1','2026-10-28 12:16:20',_binary '__ci_last_regenerate|i:1793189780;_ci_previous_url|s:42:\"http://localhost:8080/index.php/dispatches\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:58343ea5340ad3ae2fde4dc9456b372e','::1','2026-10-28 12:19:13',_binary '__ci_last_regenerate|i:1793189953;_ci_previous_url|s:39:\"http://localhost:8080/index.php/profile\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}__ci_vars|a:0:{}'),('ci_session:644a6edba57d25ac11afec5280d34c1d','::1','2026-10-28 12:14:21',_binary '__ci_last_regenerate|i:1793189660;_ci_previous_url|s:39:\"http://localhost:8080/index.php/clients\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:7f2f9dc3e14c45dd73f1862328454813','::1','2026-10-28 12:23:43',_binary '__ci_last_regenerate|i:1793190223;_ci_previous_url|s:49:\"http://localhost:8080/index.php/dispatches/view/3\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}success|s:50:\"Delivery Challan DC-TEST-888 created successfully.\";__ci_vars|a:1:{s:7:\"success\";s:3:\"old\";}'),('ci_session:874e29798b746518d0a15a1e1604ef85','::1','2026-10-28 12:13:20',_binary '__ci_last_regenerate|i:1793189600;_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";redirect_url|s:40:\"http://localhost:8080/index.php/settings\";error|s:34:\"Please login to access the system.\";__ci_vars|a:1:{s:5:\"error\";s:3:\"old\";}'),('ci_session:8dadafeb319a6c808434ce33ff55bdb0','::1','2026-10-28 12:16:58',_binary '__ci_last_regenerate|i:1793189818;_ci_previous_url|s:48:\"http://localhost:8080/index.php/employees/create\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:9cda64a28f565adfbbc26089be78e331','::1','2026-10-28 12:19:19',_binary '__ci_last_regenerate|i:1793189959;_ci_previous_url|s:55:\"http://localhost:8080/index.php/purchase-orders/print/1\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:b4d5137f0c74f8fd7ec162f7b06cde3a','::1','2026-10-28 12:23:53',_binary '__ci_last_regenerate|i:1793190233;_ci_previous_url|s:49:\"http://localhost:8080/index.php/payments/received\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}success|s:51:\"Payment Receipt RCT-TEST-999 recorded successfully.\";__ci_vars|a:1:{s:7:\"success\";s:3:\"old\";}'),('ci_session:b53842322e545d61ab542242e9cf7b7f','::1','2026-10-28 12:19:48',_binary '__ci_last_regenerate|i:1793189987;_ci_previous_url|s:48:\"http://localhost:8080/index.php/invoices/print/1\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:bbbf156fbdf353a033bfb8598d983950','::1','2026-10-28 12:22:18',_binary '__ci_last_regenerate|i:1793190138;_ci_previous_url|s:49:\"http://localhost:8080/index.php/dispatches/create\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}_ci_old_input|a:2:{s:3:\"get\";a:0:{}s:4:\"post\";a:16:{s:14:\"csrf_test_name\";s:32:\"37efec8df31dc45aee8bf4f4ab38fb63\";s:5:\"dc_no\";s:11:\"DC-TEST-999\";s:7:\"dc_date\";s:10:\"2026-10-28\";s:9:\"client_id\";s:1:\"1\";s:16:\"delivery_address\";s:52:\"NTPC Ramagundam Site Stage-IV, Karimnagar, Telangana\";s:10:\"vehicle_no\";s:13:\"MH 40 AK 8899\";s:11:\"transporter\";s:26:\"Jaipur Golden Transport Co\";s:5:\"lr_no\";s:9:\"JG-778899\";s:7:\"lr_date\";s:10:\"2026-10-28\";s:12:\"eway_bill_no\";s:12:\"281928374619\";s:12:\"total_weight\";s:6:\"24.500\";s:14:\"no_of_packages\";s:1:\"4\";s:16:\"item_description\";a:1:{i:0;s:35:\"Fabricated Heavy Box Columns DWG-01\";}s:13:\"item_quantity\";a:1:{i:0;s:1:\"4\";}s:9:\"item_unit\";a:1:{i:0;s:3:\"PCS\";}s:11:\"item_weight\";a:1:{i:0;s:6:\"24.500\";}}}__ci_vars|a:2:{s:13:\"_ci_old_input\";s:3:\"old\";s:5:\"error\";s:3:\"old\";}error|s:36:\"Failed to generate Delivery Challan.\";'),('ci_session:beb07ed80e35d93d531ac53ef8ad598b','::1','2026-10-28 12:21:42',_binary '__ci_last_regenerate|i:1793190102;_ci_previous_url|s:49:\"http://localhost:8080/index.php/dispatches/create\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}_ci_old_input|a:2:{s:3:\"get\";a:0:{}s:4:\"post\";a:12:{s:14:\"csrf_test_name\";s:32:\"5eb250a7159d0fc0267f4552e8f3ab62\";s:5:\"dc_no\";s:11:\"DC-TEST-999\";s:7:\"dc_date\";s:10:\"2026-10-28\";s:9:\"client_id\";s:1:\"1\";s:16:\"delivery_address\";s:9:\"NTPC Site\";s:10:\"vehicle_no\";s:13:\"MH 40 AK 8899\";s:12:\"total_weight\";s:6:\"24.500\";s:14:\"no_of_packages\";s:1:\"4\";s:16:\"item_description\";a:1:{i:0;s:13:\"Heavy Columns\";}s:13:\"item_quantity\";a:1:{i:0;s:1:\"4\";}s:9:\"item_unit\";a:1:{i:0;s:3:\"PCS\";}s:11:\"item_weight\";a:1:{i:0;s:6:\"24.500\";}}}__ci_vars|a:2:{s:13:\"_ci_old_input\";s:3:\"old\";s:5:\"error\";s:3:\"old\";}error|s:36:\"Failed to generate Delivery Challan.\";'),('ci_session:c6536ac9ef63aac0430a12080d16b533','::1','2026-10-28 12:19:55',_binary '__ci_last_regenerate|i:1793189995;_ci_previous_url|s:54:\"http://localhost:8080/index.php/purchase-orders/view/1\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}'),('ci_session:c69940d66709f3efd96aab5c12d368df','::1','2026-10-28 12:13:58',_binary '__ci_last_regenerate|i:1793189638;_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";is_logged_in|b:1;user|a:6:{s:2:\"id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:18:\"admin@steelerp.com\";s:9:\"full_name\";s:20:\"System Administrator\";s:4:\"role\";s:5:\"admin\";s:6:\"avatar\";N;}success|s:35:\"Welcome back, System Administrator!\";__ci_vars|a:1:{s:7:\"success\";s:3:\"old\";}'),('ci_session:d5c64a3e718ceaa94fe59805b08af057','::1','2026-10-28 12:12:48',_binary '__ci_last_regenerate|i:1793189568;__ci_vars|a:0:{}_ci_previous_url|s:37:\"http://localhost:8080/index.php/login\";');
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_type` enum('power_plant','sugar_mill','cement_plant','steel_plant','chemical','oil_gas','infrastructure','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `gstin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(15,2) DEFAULT '0.00',
  `credit_days` int DEFAULT '30',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_client_code` (`client_code`),
  KEY `idx_industry` (`industry_type`),
  KEY `idx_state` (`state`),
  KEY `fk_clients_created_by` (`created_by`),
  CONSTRAINT `fk_clients_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'CLI-POW-001','NTPC Limited (Ramagundam STPP)','Er. K. Venkat Rao (Chief Engg)','procurement.ramagundam@ntpc.co.in','08728-272101','9440112233','power_plant','Super Thermal Power Station','Jyothi Nagar','Ramagundam','Telangana','505215','India','36AAACN0255D1ZQ','AAACN0255D',25000000.00,45,'PSU Thermal Power Client - High Grade SA516 Boiler Structures',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(2,'CLI-POW-002','Adani Power (Mundra) Limited','Suresh Patel (GM - Projects)','spatel@adanipower.com','02838-255000','9898011223','power_plant','Adani House, Village Tunda & Wandh','Taluka Mundra','Mundra, Kutch','Gujarat','370435','India','24AABCA2984K1ZH','AABCA2984K',18000000.00,30,'Ultra Supercritical Thermal Power Station ESP Casings and Girders',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(3,'CLI-SUG-001','Bajaj Hindusthan Sugar Limited','Maheshwar Mishra (Mill Engr)','purchases.gola@bajajhindusthan.com','05876-222123','9415088776','sugar_mill','Gola Gokarannath Sugar Complex','Dist. Lakhimpur Kheri','Gola Gokarannath','Uttar Pradesh','262802','India','09AAACB3134J1ZA','AAACB3134J',12000000.00,30,'Sugar Mill Evaporator Bodies, Vacuum Pans and Cane Carrier Structures',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(4,'CLI-SUG-002','Shree Renuka Sugars Limited','Vinayak Kulkarni (VP Tech)','vkulkarni@renukasugars.com','0831-2404000','9845033445','sugar_mill','Munoli Sugar Mill Unit','Taluk Saundatti','Belagavi','Karnataka','591117','India','29AABCS8891P1ZX','AABCS8891P',15000000.00,45,'Refinery Crystallizers and High-Pressure Steam Piping Supports',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(5,'CLI-CEM-001','UltraTech Cement Ltd (Birla White)','Anil Jodhpurkar (AGM Engg)','procurement.bw@adityabirla.com','02930-264000','9414022334','cement_plant','Kharia Khangar Works','Bhopalgarh Tehsil','Jodhpur','Rajasthan','342606','India','08AAACU4998K1ZT','AAACU4998K',20000000.00,30,'Kiln Preheater Cyclones, Baghouse Structures & Clinker Coolers (Intra-state)',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(6,'CLI-CEM-002','Shree Cement Limited','Dharmendra Joshi (DGM Mech)','djoshi@shreecement.com','01462-228101','9829088990','cement_plant','Bangur Nagar, Ras Works','Tehsil Jaitaran','Beawar','Rajasthan','306107','India','08AAACS1363Q1ZU','AAACS1363Q',22000000.00,30,'Raw Mill Ducting, Silo Structural Supports and Conveyor Galleries',1,1,'2026-10-28 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_settings`
--

DROP TABLE IF EXISTS `company_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pincode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gstin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pan` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ifsc` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `financial_year` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. 2026-27',
  `invoice_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'INV',
  `quotation_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'QTN',
  `po_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'PO',
  `so_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'SO',
  `dc_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'DC',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_settings`
--

LOCK TABLES `company_settings` WRITE;
/*!40000 ALTER TABLE `company_settings` DISABLE KEYS */;
INSERT INTO `company_settings` VALUES (1,'Heavy Steel Fabricators Pvt. Ltd.','Quality Steel Fabrication for Power, Sugar & Cement Plants','Plot No. 45, Industrial Area Phase-II',NULL,'Jaipur','Rajasthan','302022','India','0141-2345678','info@heavysteel.in',NULL,'08AABCU9603R1ZM','AABCU9603R',NULL,NULL,NULL,NULL,NULL,NULL,'2026-27','INV','QTN','PO','SO','DC','2026-10-28 16:54:24','2026-10-28 16:54:24');
/*!40000 ALTER TABLE `company_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Administration','General administration and management',1,'2026-10-28 16:54:24'),(2,'Sales & Marketing','Sales team and client relations',1,'2026-10-28 16:54:24'),(3,'Purchase','Procurement and vendor management',1,'2026-10-28 16:54:24'),(4,'Store','Inventory and warehouse management',1,'2026-10-28 16:54:24'),(5,'Production','Manufacturing and fabrication',1,'2026-10-28 16:54:24'),(6,'Quality Control','Quality assurance and testing',1,'2026-10-28 16:54:24'),(7,'Accounts','Finance, billing and accounting',1,'2026-10-28 16:54:24'),(8,'Dispatch','Logistics and delivery',1,'2026-10-28 16:54:24'),(9,'HR','Human resources',1,'2026-10-28 16:54:24');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatch_items`
--

DROP TABLE IF EXISTS `dispatch_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispatch_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `dispatch_id` int unsigned NOT NULL,
  `so_item_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `weight` decimal(14,3) DEFAULT '0.000',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_dispitem_disp` (`dispatch_id`),
  KEY `fk_dispitem_soitem` (`so_item_id`),
  CONSTRAINT `fk_dispitem_disp` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_dispitem_soitem` FOREIGN KEY (`so_item_id`) REFERENCES `sales_order_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatch_items`
--

LOCK TABLES `dispatch_items` WRITE;
/*!40000 ALTER TABLE `dispatch_items` DISABLE KEYS */;
INSERT INTO `dispatch_items` VALUES (1,1,1,'ESP Casing Shell Panels Type-A (Batch 1)',20000.000,'kg',20000.000,'8 panels loaded. Mill TC and NDT report attached.'),(2,2,4,'Preheater Cyclone Body Conical Shell (Shell 1 & 2)',14000.000,'kg',14000.000,'Overdimensional consignment. Police escort engaged.');
/*!40000 ALTER TABLE `dispatch_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatches`
--

DROP TABLE IF EXISTS `dispatches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispatches` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `dc_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dc_date` date NOT NULL,
  `sales_order_id` int unsigned DEFAULT NULL,
  `client_id` int unsigned NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `transporter` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lr_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lorry Receipt No',
  `lr_date` date DEFAULT NULL,
  `eway_bill_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_weight` decimal(14,3) DEFAULT '0.000',
  `no_of_packages` int DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','dispatched','in_transit','delivered','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `delivered_date` date DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_dc_no` (`dc_no`),
  KEY `fk_disp_so` (`sales_order_id`),
  KEY `fk_disp_client` (`client_id`),
  KEY `fk_disp_created` (`created_by`),
  CONSTRAINT `fk_disp_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_disp_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_disp_so` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatches`
--

LOCK TABLES `dispatches` WRITE;
/*!40000 ALTER TABLE `dispatches` DISABLE KEYS */;
INSERT INTO `dispatches` VALUES (1,'DC-2627-001','2026-10-22',1,1,'NTPC Super Thermal Power Project, Ramagundam, Telangana - 505215','Jaipur Golden Transport Co.','RJ-14-GC-8842','Balram Gurjar','9829988112','JG-JPR-2026-904','2026-10-22','241890334812',20000.000,8,'Consignment safely dispatched on 40ft high-bed multi-axle trailer. E-Way bill valid for 5 days.','in_transit',NULL,7,'2026-10-22 17:14:10','2026-10-28 17:14:10'),(2,'DC-2627-002','2026-10-25',2,5,'UltraTech Cement Ltd, Kharia Khangar, Jodhpur, Rajasthan - 342606','Shree Marwar Freight Carrier','RJ-19-GA-3321','Kishan Lal Bishnoi','9414433221','SM-JPR-4412','2026-10-25','281890455913',14000.000,2,'Delivered at UltraTech central store. Material receipt endorsed on LR copy.','delivered','2026-10-26',7,'2026-10-25 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `dispatches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `emp_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int unsigned DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `aadhar_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ifsc` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basic_salary` decimal(12,2) DEFAULT '0.00',
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_emp_code` (`emp_code`),
  KEY `fk_emp_dept` (`department_id`),
  CONSTRAINT `fk_emp_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'EMP-001','Er. Arvind Verma','General Manager (Works)',5,'arvind.verma@heavysteel.in','9829045678','2020-04-01',NULL,NULL,'ABEPV1234C',NULL,NULL,125000.00,'B-14, Vaishali Nagar, Jaipur','9829000001',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(2,'EMP-002','Rajesh Sharma','Head of Sales & BD',2,'rajesh.sharma@heavysteel.in','9829012345','2021-06-15',NULL,NULL,'BCDPS5678D',NULL,NULL,110000.00,'Plot 82, Mansarovar, Jaipur','9829000002',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(3,'EMP-003','Vikram Singh','Purchase Manager',3,'vikram.singh@heavysteel.in','9829023456','2021-09-01',NULL,NULL,'CDEPS9012E',NULL,NULL,85000.00,'45, Malviya Nagar, Jaipur','9829000003',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(4,'EMP-004','Ramesh Yadav','Chief Store Officer',4,'ramesh.yadav@heavysteel.in','9829034567','2019-11-10',NULL,NULL,'DEFPS3456F',NULL,NULL,65000.00,'12, Jagatpura, Jaipur','9829000004',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(5,'EMP-005','CA Manoj Agrawal','Chief Financial Officer',7,'manoj.agrawal@heavysteel.in','9829056789','2020-01-15',NULL,NULL,'EFGPA7890G',NULL,NULL,135000.00,'Flat 402, C-Scheme, Jaipur','9829000005',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(6,'EMP-006','Dinesh Kumar Meena','QC & NDT Level-II Inspector',6,'dinesh.qc@heavysteel.in','9829077889','2022-03-01',NULL,NULL,'FGHPM1234H',NULL,NULL,70000.00,'77, Sanganer, Jaipur','9829000006',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(7,'EMP-007','Sunil Choudhary','Dispatch & Logistics Officer',8,'sunil.c@heavysteel.in','9829067890','2022-08-15',NULL,NULL,'GHIJC5678J',NULL,NULL,55000.00,'23, Sitapura Ind. Area, Jaipur','9829000007',NULL,1,'2026-10-28 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiries`
--

DROP TABLE IF EXISTS `enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enquiries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `enquiry_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enquiry_date` date NOT NULL,
  `client_id` int unsigned DEFAULT NULL,
  `client_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'for walk-in / new clients',
  `contact_person` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` enum('direct','reference','tender','email','phone','website','other') COLLATE utf8mb4_unicode_ci DEFAULT 'direct',
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plant_type` enum('power','sugar','cement','steel','chemical','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `estimated_value` decimal(15,2) DEFAULT '0.00',
  `expected_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('new','in_review','quoted','converted','lost','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `assigned_to` int unsigned DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_enquiry_no` (`enquiry_no`),
  KEY `idx_status` (`status`),
  KEY `idx_date` (`enquiry_date`),
  KEY `fk_enq_client` (`client_id`),
  KEY `fk_enq_assigned` (`assigned_to`),
  KEY `fk_enq_created` (`created_by`),
  CONSTRAINT `fk_enq_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_enq_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_enq_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiries`
--

LOCK TABLES `enquiries` WRITE;
/*!40000 ALTER TABLE `enquiries` DISABLE KEYS */;
INSERT INTO `enquiries` VALUES (1,'ENQ-2627-001','2026-09-30',1,'NTPC Limited','Er. K. Venkat Rao','9440112233','procurement.ramagundam@ntpc.co.in','tender','800MW Stage-IV Boiler Structure & ESP Ducting','power','Fabrication & supply of heavy ducting, ESP casings, stiffeners and supporting structural steel columns in IS2062 E250 / SA516 Gr 70 plates.',8500000.00,'2026-12-12','high','converted',2,NULL,1,'2026-09-30 17:14:10','2026-10-28 17:14:10'),(2,'ENQ-2627-002','2026-10-06',5,'UltraTech Cement Ltd (Birla White)','Anil Jodhpurkar','9414022334','procurement.bw@adityabirla.com','direct','White Cement Kiln-2 Cyclone Preheater Ducting','cement','Supply and fabrication of cyclone ducting, riser pipes, expansion bellows structural frames and damper frames as per drawing UTCL-KK-2026-ME-04.',4200000.00,'2026-11-27','high','converted',2,NULL,1,'2026-10-06 17:14:10','2026-10-28 17:14:10'),(3,'ENQ-2627-003','2026-10-13',3,'Bajaj Hindusthan Sugar Limited','Maheshwar Mishra','9415088776','purchases.gola@bajajhindusthan.com','email','Gola Sugar Unit Quadruple Effect Evaporator Body','sugar','Heavy fabricated MS evaporator calandria shell and vapor piping with SA516 Gr70 tube plates for sugar juice concentration.',3800000.00,'2026-12-27','medium','quoted',2,NULL,1,'2026-10-13 17:14:10','2026-10-28 17:14:10'),(4,'ENQ-2627-004','2026-10-20',6,'Shree Cement Limited','Dharmendra Joshi','9829088990','djoshi@shreecement.com','website','Clinker Cooler Exhaust Ducting & Bag House Structure','cement','Supply of heavy structural steel trusses, trestles, walk-ways and ducting for clinker cooler air pollution control system.',6200000.00,'2027-01-26','high','in_review',2,NULL,1,'2026-10-20 17:14:10','2026-10-28 17:14:10'),(5,'ENQ-2627-005','2026-10-25',2,'Adani Power (Mundra) Limited','Suresh Patel','9898011223','spatel@adanipower.com','tender','Turbine Hall Heavy Crane Girders & Column Bracings','power','Heavy built-up I-girders with 32mm flange and 20mm web plates, ISMB 400 bracings and high-tensile connection plates.',12500000.00,'2027-02-25','urgent','new',2,NULL,1,'2026-10-25 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `enquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiry_items`
--

DROP TABLE IF EXISTS `enquiry_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enquiry_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `enquiry_id` int unsigned NOT NULL,
  `material_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_enqitem_enq` (`enquiry_id`),
  KEY `fk_enqitem_mat` (`material_id`),
  CONSTRAINT `fk_enqitem_enq` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_enqitem_mat` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiry_items`
--

LOCK TABLES `enquiry_items` WRITE;
/*!40000 ALTER TABLE `enquiry_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `enquiry_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grn`
--

DROP TABLE IF EXISTS `grn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `grn_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grn_date` date NOT NULL,
  `purchase_order_id` int unsigned NOT NULL,
  `vendor_id` int unsigned NOT NULL,
  `challan_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challan_date` date DEFAULT NULL,
  `vehicle_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','inspected','accepted','rejected','partial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_grn_no` (`grn_no`),
  KEY `fk_grn_po` (`purchase_order_id`),
  KEY `fk_grn_vendor` (`vendor_id`),
  KEY `fk_grn_created` (`created_by`),
  CONSTRAINT `fk_grn_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_grn_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_grn_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grn`
--

LOCK TABLES `grn` WRITE;
/*!40000 ALTER TABLE `grn` DISABLE KEYS */;
INSERT INTO `grn` VALUES (1,'GRN-2627-001','2026-10-12',1,1,'SAIL/JPR/INV/1044','2026-10-11','RJ-14-GH-4589','Weighment at Plant Weighbridge done. Mill TC verified by QC Er. Dinesh.','accepted',4,'2026-10-12 17:14:10'),(2,'GRN-2627-002','2026-10-16',2,2,'JSPL/DEL/DC/8892','2026-10-15','HR-55-AB-9921','JSPL structural beams inspected. Physical dimensions checked.','accepted',4,'2026-10-16 17:14:10'),(3,'GRN-2627-003','2026-10-23',3,3,'ESAB/FBD/CH-3321','2026-10-22','DL-1L-AA-2204','Electrodes and MIG spools received in intact vacuum packs.','partial',4,'2026-10-23 17:14:10');
/*!40000 ALTER TABLE `grn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grn_items`
--

DROP TABLE IF EXISTS `grn_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grn_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `grn_id` int unsigned NOT NULL,
  `po_item_id` int unsigned NOT NULL,
  `material_id` int unsigned NOT NULL,
  `ordered_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `received_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `accepted_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `rejected_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_grnitem_grn` (`grn_id`),
  KEY `fk_grnitem_poitem` (`po_item_id`),
  KEY `fk_grnitem_mat` (`material_id`),
  CONSTRAINT `fk_grnitem_grn` FOREIGN KEY (`grn_id`) REFERENCES `grn` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_grnitem_mat` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_grnitem_poitem` FOREIGN KEY (`po_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grn_items`
--

LOCK TABLES `grn_items` WRITE;
/*!40000 ALTER TABLE `grn_items` DISABLE KEYS */;
INSERT INTO `grn_items` VALUES (1,1,1,1,30000.000,30000.000,30000.000,0.000,62.50,'Accepted in full. Heat No: SAIL-K-4921'),(2,1,2,3,50000.000,50000.000,50000.000,0.000,64.20,'Accepted in full. Heat No: SAIL-R-8812'),(3,1,3,4,22700.000,22700.000,22700.000,0.000,65.50,'Accepted in full. Heat No: SAIL-B-3104'),(4,2,1,8,25000.000,25000.000,25000.000,0.000,64.00,'JSPL Raigarh Mill Heat 7882. Ultrasonic OK.'),(5,3,1,16,3500.000,2000.000,2000.000,0.000,145.00,'Part shipment accepted. Balance 1500kg pending.'),(6,3,2,17,1200.000,1200.000,1200.000,0.000,115.00,'All spools intact.');
/*!40000 ALTER TABLE `grn_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indian_states`
--

DROP TABLE IF EXISTS `indian_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `indian_states` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `state_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_ut` tinyint(1) DEFAULT '0' COMMENT 'Union Territory',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_state_code` (`state_code`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indian_states`
--

LOCK TABLES `indian_states` WRITE;
/*!40000 ALTER TABLE `indian_states` DISABLE KEYS */;
INSERT INTO `indian_states` VALUES (1,'01','Jammu & Kashmir',1),(2,'02','Himachal Pradesh',0),(3,'03','Punjab',0),(4,'04','Chandigarh',1),(5,'05','Uttarakhand',0),(6,'06','Haryana',0),(7,'07','Delhi',1),(8,'08','Rajasthan',0),(9,'09','Uttar Pradesh',0),(10,'10','Bihar',0),(11,'11','Sikkim',0),(12,'12','Arunachal Pradesh',0),(13,'13','Nagaland',0),(14,'14','Manipur',0),(15,'15','Mizoram',0),(16,'16','Tripura',0),(17,'17','Meghalaya',0),(18,'18','Assam',0),(19,'19','West Bengal',0),(20,'20','Jharkhand',0),(21,'21','Odisha',0),(22,'22','Chhattisgarh',0),(23,'23','Madhya Pradesh',0),(24,'24','Gujarat',0),(25,'25','Daman & Diu',1),(26,'26','Dadra & Nagar Haveli',1),(27,'27','Maharashtra',0),(28,'28','Andhra Pradesh (Old)',0),(29,'29','Karnataka',0),(30,'30','Goa',0),(31,'31','Lakshadweep',1),(32,'32','Kerala',0),(33,'33','Tamil Nadu',0),(34,'34','Puducherry',1),(35,'35','Andaman & Nicobar',1),(36,'36','Telangana',0),(37,'37','Andhra Pradesh',0),(38,'38','Ladakh',1);
/*!40000 ALTER TABLE `indian_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `material_id` int unsigned NOT NULL,
  `warehouse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'main',
  `location_rack` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(14,3) NOT NULL DEFAULT '0.000',
  `reserved_qty` decimal(14,3) NOT NULL DEFAULT '0.000',
  `available_qty` decimal(14,3) GENERATED ALWAYS AS ((`quantity` - `reserved_qty`)) STORED,
  `avg_rate` decimal(12,2) DEFAULT '0.00',
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_material_warehouse` (`material_id`,`warehouse`),
  CONSTRAINT `fk_inv_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` (`id`, `material_id`, `warehouse`, `location_rack`, `quantity`, `reserved_qty`, `avg_rate`, `last_updated`) VALUES (1,1,'Yard-A (Plates)','Rack-PL-10',48500.000,15000.000,62.50,'2026-10-28 17:14:10'),(2,2,'Yard-A (Plates)','Rack-PL-12',36200.000,12000.000,63.00,'2026-10-28 17:14:10'),(3,3,'Yard-A (Plates)','Rack-PL-16',52000.000,20000.000,64.20,'2026-10-28 17:14:10'),(4,4,'Yard-A (Plates)','Rack-PL-20',28400.000,8000.000,65.50,'2026-10-28 17:14:10'),(5,5,'Yard-A (Plates)','Rack-PL-25',18600.000,6000.000,66.80,'2026-10-28 17:14:10'),(6,6,'Yard-B (Boiler Quality)','Rack-BQ-32',14500.000,5000.000,78.50,'2026-10-28 17:14:10'),(7,7,'Structural Bay 1','Bay-BM-200',16800.000,4000.000,63.50,'2026-10-28 17:14:10'),(8,8,'Structural Bay 1','Bay-BM-300',22500.000,8500.000,64.00,'2026-10-28 17:14:10'),(9,9,'Structural Bay 1','Bay-BM-400',19400.000,6000.000,65.20,'2026-10-28 17:14:10'),(10,10,'Structural Bay 2','Bay-CH-150',12300.000,3000.000,62.00,'2026-10-28 17:14:10'),(11,11,'Structural Bay 2','Bay-CH-200',14200.000,4500.000,62.80,'2026-10-28 17:14:10'),(12,12,'Structural Bay 2','Bay-ANG-75',8600.000,2000.000,61.50,'2026-10-28 17:14:10'),(13,13,'Structural Bay 2','Bay-ANG-100',9800.000,2500.000,62.20,'2026-10-28 17:14:10'),(14,14,'Pipes Yard','Rack-P-100',1450.000,300.000,1250.00,'2026-10-28 17:14:10'),(15,15,'Pipes Yard','Rack-P-150',980.000,200.000,1850.00,'2026-10-28 17:14:10'),(16,16,'Consumables Store','Shelf-WLD-1',4200.000,800.000,145.00,'2026-10-28 17:14:10'),(17,17,'Consumables Store','Shelf-WLD-2',2800.000,600.000,115.00,'2026-10-28 17:14:10'),(18,18,'Chemical & Paint Bay','Drum-PNT-1',650.000,150.000,210.00,'2026-10-28 17:14:10'),(19,19,'Chemical & Paint Bay','Drum-PNT-2',480.000,100.000,260.00,'2026-10-28 17:14:10');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int unsigned NOT NULL,
  `sl_no` int NOT NULL DEFAULT '1',
  `so_item_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hsn_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_rate` decimal(5,2) DEFAULT '18.00',
  `cgst_amt` decimal(12,2) DEFAULT '0.00',
  `sgst_amt` decimal(12,2) DEFAULT '0.00',
  `igst_amt` decimal(12,2) DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_invitem_inv` (`invoice_id`),
  CONSTRAINT `fk_invitem_inv` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,1,1,'Fabricated ESP Casing Shell Panels Type-A with Stiffeners','73089090',20000.000,'kg',88.00,1760000.00,18.00,0.00,0.00,316800.00,2076800.00),(2,2,1,4,'Preheater Cyclone Conical Shell Sections','73089090',14000.000,'kg',90.00,1260000.00,18.00,113400.00,113400.00,0.00,1486800.00);
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_type` enum('tax_invoice','proforma','credit_note','debit_note') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tax_invoice',
  `sales_order_id` int unsigned DEFAULT NULL,
  `dispatch_id` int unsigned DEFAULT NULL,
  `client_id` int unsigned NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `place_of_supply` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'State for GST',
  `is_igst` tinyint(1) DEFAULT '0' COMMENT '1 = inter-state (IGST), 0 = intra-state (CGST+SGST)',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amt` decimal(15,2) DEFAULT '0.00',
  `taxable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cgst_amount` decimal(15,2) DEFAULT '0.00',
  `sgst_amount` decimal(15,2) DEFAULT '0.00',
  `igst_amount` decimal(15,2) DEFAULT '0.00',
  `total_tax` decimal(15,2) DEFAULT '0.00',
  `round_off` decimal(5,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_in_words` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `bank_details` text COLLATE utf8mb4_unicode_ci,
  `terms_conditions` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','sent','partially_paid','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_amount` decimal(15,2) DEFAULT '0.00',
  `balance_amount` decimal(15,2) DEFAULT '0.00',
  `irn` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e-Invoice Reference Number',
  `qr_code` text COLLATE utf8mb4_unicode_ci COMMENT 'e-Invoice QR',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_invoice_no` (`invoice_no`),
  KEY `idx_status` (`status`),
  KEY `idx_date` (`invoice_date`),
  KEY `fk_inv_so` (`sales_order_id`),
  KEY `fk_inv_disp` (`dispatch_id`),
  KEY `fk_inv_client` (`client_id`),
  KEY `fk_inv_created` (`created_by`),
  CONSTRAINT `fk_inv_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_inv_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_inv_disp` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_inv_so` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,'INV-2627-001','2026-10-22','tax_invoice',1,1,1,'NTPC Limited, NTPC Bhawan, SCOPE Complex, 7 Institutional Area, Lodhi Road, New Delhi - 110003 (GSTIN: 36AAACN0255D1ZQ)','NTPC Super Thermal Power Project, Ramagundam, Telangana - 505215','36-Telangana',1,1760000.00,0.00,1760000.00,0.00,0.00,316800.00,316800.00,0.00,2076800.00,'Rupees Twenty Lakh Seventy Six Thousand Eight Hundred Only','Against Dispatch / 30 Days Credit','2026-11-21','HDFC Bank, MI Road Branch, Jaipur | A/C: 50200088991122 | IFSC: HDFC0000054','1. Interest @ 18% p.a. will be charged if payment not received within due date.\n2. Goods once sold will not be taken back.\n3. All disputes subject to Jaipur jurisdiction.','Invoice against Dispatch DC-2627-001 for Batch 1 ESP Casings','partially_paid',1147000.00,929800.00,'e87f2b1a9c3d4e5f60718293a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1a2b3',NULL,5,'2026-10-22 17:14:10','2026-10-28 17:14:10'),(2,'INV-2627-002','2026-10-25','tax_invoice',2,2,5,'UltraTech Cement Ltd (Birla White), Kharia Khangar, Jodhpur, Rajasthan - 342606 (GSTIN: 08AAACU4998K1ZT)','Same as billing address','08-Rajasthan',0,1260000.00,0.00,1260000.00,113400.00,113400.00,0.00,226800.00,0.00,1486800.00,'Rupees Fourteen Lakh Eighty Six Thousand Eight Hundred Only','Against Delivery / 30 Days Credit','2026-11-24','HDFC Bank, MI Road Branch, Jaipur | A/C: 50200088991122 | IFSC: HDFC0000054','1. Interest @ 18% p.a. if unpaid after due date.\n2. Jurisdiction: Jaipur Courts only.','Intra-state supply with CGST (9%) + SGST (9%)','sent',0.00,1486800.00,'f19a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7c8d9e0f1',NULL,5,'2026-10-25 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_categories`
--

DROP TABLE IF EXISTS `material_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `material_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_matcat_parent` (`parent_id`),
  CONSTRAINT `fk_matcat_parent` FOREIGN KEY (`parent_id`) REFERENCES `material_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_categories`
--

LOCK TABLES `material_categories` WRITE;
/*!40000 ALTER TABLE `material_categories` DISABLE KEYS */;
INSERT INTO `material_categories` VALUES (1,'MS Plates','Mild Steel Plates of various grades and thicknesses',NULL,1,'2026-10-28 16:54:24'),(2,'MS Beams','I-Beams, H-Beams structural steel',NULL,1,'2026-10-28 16:54:24'),(3,'MS Channels','C-Channels, U-Channels',NULL,1,'2026-10-28 16:54:24'),(4,'MS Angles','Equal and unequal angles',NULL,1,'2026-10-28 16:54:24'),(5,'MS Pipes','Seamless and welded pipes',NULL,1,'2026-10-28 16:54:24'),(6,'MS Flats','Flat bars',NULL,1,'2026-10-28 16:54:24'),(7,'MS Rounds','Round bars',NULL,1,'2026-10-28 16:54:24'),(8,'MS Sheets','HR and CR sheets',NULL,1,'2026-10-28 16:54:24'),(9,'Flanges','Pipe flanges - slip-on, weld neck, blind',NULL,1,'2026-10-28 16:54:24'),(10,'Fasteners','Bolts, nuts, washers, studs',NULL,1,'2026-10-28 16:54:24'),(11,'Welding Consumables','Electrodes, wires, flux',NULL,1,'2026-10-28 16:54:24'),(12,'Painting Materials','Primers, paints, thinners',NULL,1,'2026-10-28 16:54:24'),(13,'Gaskets & Seals','Gaskets, O-rings, seals',NULL,1,'2026-10-28 16:54:24');
/*!40000 ALTER TABLE `material_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_issues`
--

DROP TABLE IF EXISTS `material_issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `material_issues` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `issue_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `job_id` int unsigned NOT NULL,
  `material_id` int unsigned NOT NULL,
  `quantity` decimal(12,3) NOT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `rate` decimal(12,2) DEFAULT '0.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `issued_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_issue_no` (`issue_no`),
  KEY `fk_mi_job` (`job_id`),
  KEY `fk_mi_material` (`material_id`),
  KEY `fk_mi_issued` (`issued_by`),
  CONSTRAINT `fk_mi_issued` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_mi_job` FOREIGN KEY (`job_id`) REFERENCES `production_jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_mi_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_issues`
--

LOCK TABLES `material_issues` WRITE;
/*!40000 ALTER TABLE `material_issues` DISABLE KEYS */;
INSERT INTO `material_issues` VALUES (1,'ISS-2627-001','2026-10-12',1,3,28000.000,'kg',64.20,'Issued 16mm plates for ESP casing shell panels',4,'2026-10-12 17:14:10'),(2,'ISS-2627-002','2026-10-13',1,16,500.000,'kg',145.00,'Issued E7018 electrodes to Bay-1 welding team',4,'2026-10-13 17:14:10'),(3,'ISS-2627-003','2026-10-18',2,2,18000.000,'kg',63.00,'Issued 12mm plates for UltraTech cyclone rolling',4,'2026-10-18 17:14:10'),(4,'ISS-2627-004','2026-10-20',1,8,16000.000,'kg',64.00,'Issued ISMB 300 beams for support column fabrication',4,'2026-10-20 17:14:10'),(5,'ISS-2627-005','2026-10-22',1,18,120.000,'ltr',210.00,'Issued Zinc Phosphate Primer to paint shop',4,'2026-10-22 17:14:10');
/*!40000 ALTER TABLE `material_issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `material_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int unsigned DEFAULT NULL,
  `hsn_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` enum('kg','mt','nos','mtr','sqm','sqft','ltr','set','lot') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `grade` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. IS2062 E250, SA516 Gr70',
  `specification` text COLLATE utf8mb4_unicode_ci,
  `thickness` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diameter` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_per_unit` decimal(12,4) DEFAULT NULL COMMENT 'kg per unit',
  `min_stock` decimal(12,3) DEFAULT '0.000',
  `max_stock` decimal(12,3) DEFAULT '0.000',
  `reorder_level` decimal(12,3) DEFAULT '0.000',
  `last_purchase_rate` decimal(12,2) DEFAULT '0.00',
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_material_code` (`material_code`),
  KEY `idx_hsn` (`hsn_code`),
  KEY `fk_materials_cat` (`category_id`),
  CONSTRAINT `fk_materials_cat` FOREIGN KEY (`category_id`) REFERENCES `material_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'MS-PLT-10-IS2062','MS Plate 10mm IS2062 E250',1,'72085100','kg','IS2062 E250','As per IS 2062:2011','10mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(2,'MS-PLT-12-IS2062','MS Plate 12mm IS2062 E250',1,'72085100','kg','IS2062 E250','As per IS 2062:2011','12mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(3,'MS-PLT-16-IS2062','MS Plate 16mm IS2062 E250',1,'72085100','kg','IS2062 E250','As per IS 2062:2011','16mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(4,'MS-PLT-20-IS2062','MS Plate 20mm IS2062 E250',1,'72085100','kg','IS2062 E250','As per IS 2062:2011','20mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(5,'MS-PLT-25-IS2062','MS Plate 25mm IS2062 E250',1,'72085100','kg','IS2062 E250','As per IS 2062:2011','25mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(6,'MS-PLT-32-SA516','MS Plate 32mm SA516 Gr70',1,'72085100','kg','SA516 Gr70','As per ASME SA516','32mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(7,'MS-BM-200','ISMB 200',2,'72163200','kg','IS2062 E250','Indian Standard Medium Beam 200mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(8,'MS-BM-300','ISMB 300',2,'72163200','kg','IS2062 E250','Indian Standard Medium Beam 300mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(9,'MS-BM-400','ISMB 400',2,'72163200','kg','IS2062 E250','Indian Standard Medium Beam 400mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(10,'MS-CH-150','ISMC 150',3,'72163100','kg','IS2062 E250','Indian Standard Medium Channel 150mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(11,'MS-CH-200','ISMC 200',3,'72163100','kg','IS2062 E250','Indian Standard Medium Channel 200mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(12,'MS-ANG-75','ISA 75x75x6',4,'72162100','kg','IS2062 E250','Equal Angle 75x75x6mm','6mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(13,'MS-ANG-100','ISA 100x100x8',4,'72162100','kg','IS2062 E250','Equal Angle 100x100x8mm','8mm',NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(14,'MS-PIPE-100-SCH40','MS Pipe 100 NB SCH 40',5,'73051100','mtr','IS1239','Seamless Pipe 100mm NB Schedule 40',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(15,'MS-PIPE-150-SCH40','MS Pipe 150 NB SCH 40',5,'73051100','mtr','IS1239','Seamless Pipe 150mm NB Schedule 40',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(16,'WLD-E7018','Welding Electrode E7018 4mm',11,'83112000','kg','AWS A5.1','Low Hydrogen Electrode 4mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(17,'WLD-WIRE-1.2','MIG Wire 1.2mm ER70S-6',11,'83112000','kg','AWS A5.18','MIG/MAG Solid Wire 1.2mm',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(18,'PNT-PRIMER','Zinc Phosphate Primer',12,'32091000','ltr',NULL,'Red Oxide Zinc Phosphate Primer',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24'),(19,'PNT-FINISH','Synthetic Enamel Paint',12,'32091000','ltr',NULL,'Industrial Grade Enamel Paint',NULL,NULL,NULL,NULL,NULL,0.000,0.000,0.000,0.00,18.00,1,'2026-10-28 16:54:24','2026-10-28 16:54:24');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `type` enum('info','success','warning','danger') COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_read` (`user_id`,`is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'New Enquiry Received','Adani Power submitted urgent enquiry for Turbine Hall Crane Girders (₹1.25 Cr)','info','enquiries/view/5',0,'2026-10-25 17:14:10'),(2,1,'Material Reorder Alert','MIG Wire ER70S-6 stock is approaching reorder level (2,200 kg remaining)','warning','inventory',0,'2026-10-27 17:14:10'),(3,1,'Payment Received','NTPC Limited credited ₹11,47,000 via RTGS against INV-2627-001','success','invoices/view/1',0,'2026-10-26 17:14:10'),(4,1,'Production Milestone','Job JOB-2627-001 has reached 65% completion. Painting stage underway.','info','production/view/1',0,'2026-10-27 17:14:10');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_made`
--

DROP TABLE IF EXISTS `payments_made`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments_made` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `payment_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `vendor_id` int unsigned NOT NULL,
  `purchase_order_id` int unsigned DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` enum('cash','cheque','neft','rtgs','upi','dd','lc','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'neft',
  `reference_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_amount` decimal(12,2) DEFAULT '0.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('paid','cleared','bounced','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_payment_no` (`payment_no`),
  KEY `fk_pm_vendor` (`vendor_id`),
  KEY `fk_pm_po` (`purchase_order_id`),
  KEY `fk_pm_created` (`created_by`),
  CONSTRAINT `fk_pm_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pm_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pm_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_made`
--

LOCK TABLES `payments_made` WRITE;
/*!40000 ALTER TABLE `payments_made` DISABLE KEYS */;
INSERT INTO `payments_made` VALUES (1,'PMT-2627-001','2026-10-03',1,1,7838780.00,'rtgs','HDFCR52026100123','HDFC Bank',0.00,'100% advance RTGS to SAIL for raw steel plate order','cleared',5,'2026-10-03 17:14:10'),(2,'PMT-2627-002','2026-10-08',2,2,1933000.00,'rtgs','HDFCR52026100456','HDFC Bank',0.00,'RTGS payment to JSPL for ISMB structural beams','cleared',5,'2026-10-08 17:14:10');
/*!40000 ALTER TABLE `payments_made` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_received`
--

DROP TABLE IF EXISTS `payments_received`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments_received` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `receipt_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `client_id` int unsigned NOT NULL,
  `invoice_id` int unsigned DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_mode` enum('cash','cheque','neft','rtgs','upi','dd','lc','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'neft',
  `reference_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cheque/UTR/Transaction No',
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_amount` decimal(12,2) DEFAULT '0.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('received','cleared','bounced','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_receipt_no` (`receipt_no`),
  KEY `fk_pr_client` (`client_id`),
  KEY `fk_pr_invoice` (`invoice_id`),
  KEY `fk_pr_created` (`created_by`),
  CONSTRAINT `fk_pr_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pr_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pr_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_received`
--

LOCK TABLES `payments_received` WRITE;
/*!40000 ALTER TABLE `payments_received` DISABLE KEYS */;
INSERT INTO `payments_received` VALUES (1,'REC-2627-001','2026-10-26',1,1,1147000.00,'rtgs','NTPCRTGS261028001','State Bank of India',22940.00,'Advance / Part payment against INV-2627-001 after 2% IT-TDS deduction','cleared',5,'2026-10-26 17:14:10');
/*!40000 ALTER TABLE `payments_received` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_job_items`
--

DROP TABLE IF EXISTS `production_job_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_job_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int unsigned NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drawing_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'nos',
  `weight_each` decimal(12,3) DEFAULT '0.000' COMMENT 'kg per piece',
  `total_weight` decimal(14,3) DEFAULT '0.000',
  `process` enum('cutting','drilling','welding','machining','assembly','painting','testing','other') COLLATE utf8mb4_unicode_ci DEFAULT 'cutting',
  `status` enum('pending','in_progress','completed','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `completed_qty` decimal(12,3) DEFAULT '0.000',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_jobitem_job` (`job_id`),
  CONSTRAINT `fk_jobitem_job` FOREIGN KEY (`job_id`) REFERENCES `production_jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_job_items`
--

LOCK TABLES `production_job_items` WRITE;
/*!40000 ALTER TABLE `production_job_items` DISABLE KEYS */;
INSERT INTO `production_job_items` VALUES (1,1,'ESP Casing Side Panels Type-A','HSF/NTPC/DWG-01/R2',12.000,'nos',2250.000,27000.000,'painting','completed',12.000,'SA 2.5 blasted and red oxide primed'),(2,1,'ESP Casing Roof Structure & Hopper','HSF/NTPC/DWG-02/R1',8.000,'nos',2250.000,18000.000,'welding','in_progress',5.000,'Submerged arc welding on web-flange joints'),(3,1,'Heavy Support Columns ISMB 300','HSF/NTPC/DWG-03/R0',24.000,'nos',1333.330,32000.000,'assembly','in_progress',14.000,'Fit-up and tack welding with gussets'),(4,1,'Connecting Ducting & Expansion Bellows','HSF/NTPC/DWG-04/R1',10.000,'nos',1000.000,10000.000,'testing','in_progress',4.000,'100% DP testing on weld joints completed'),(5,2,'Kiln Cyclone Body Conical Shell','HSF/UTCL/DWG-C1',4.000,'nos',7000.000,28000.000,'assembly','in_progress',2.000,'Shell 1 & 2 dispatched; Shell 3 & 4 in assembly'),(6,2,'Riser Duct Sections 10mm Plate','HSF/UTCL/DWG-R1',15.000,'nos',1000.000,15000.000,'welding','in_progress',8.000,'MIG welding of longitudinal seams ongoing');
/*!40000 ALTER TABLE `production_job_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_jobs`
--

DROP TABLE IF EXISTS `production_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_jobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `job_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_date` date NOT NULL,
  `sales_order_id` int unsigned DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('planned','material_issued','in_progress','quality_check','completed','on_hold','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `total_weight` decimal(14,3) DEFAULT '0.000' COMMENT 'Total weight in MT',
  `completion_pct` decimal(5,2) DEFAULT '0.00',
  `assigned_to` int unsigned DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_job_no` (`job_no`),
  KEY `idx_status` (`status`),
  KEY `fk_job_so` (`sales_order_id`),
  KEY `fk_job_assigned` (`assigned_to`),
  KEY `fk_job_created` (`created_by`),
  CONSTRAINT `fk_job_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_job_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_job_so` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_jobs`
--

LOCK TABLES `production_jobs` WRITE;
/*!40000 ALTER TABLE `production_jobs` DISABLE KEYS */;
INSERT INTO `production_jobs` VALUES (1,'JOB-2627-001','2026-10-10',1,'800MW Stage-IV Boiler Structure & ESP Ducting','Heavy fabrication of ESP casing panels, internal baffle grids, hopper supports and ductwork for NTPC Ramagundam.','2026-10-11','2026-12-02',NULL,'high','in_progress',87000.000,65.00,1,'First batch of 20 MT dispatched. Second batch under final blasting and painting.',1,'2026-10-10 17:14:10','2026-10-28 17:14:10'),(2,'JOB-2627-002','2026-10-16',2,'White Cement Kiln-2 Cyclone Preheater Ducting','Rolling and fabrication of cyclone conical bodies, riser ducts and refractory retaining lugs for UltraTech Cement.','2026-10-17','2026-11-17',NULL,'urgent','in_progress',43000.000,50.00,1,'Phase 1 (14 MT) dispatched. Phase 2 welding and DP testing in progress in Bay-2.',1,'2026-10-16 17:14:10','2026-10-28 17:14:10'),(3,'JOB-2627-003','2026-10-24',NULL,'Stock Structural Standard Trusses & Girders','Fabrication of standard 12m roof trusses and conveyor gallery modules for stock and upcoming sugar plant expansions.','2026-10-25','2026-11-22',NULL,'medium','material_issued',15000.000,20.00,1,'CNC gas cutting of chord angles and gusset plates ongoing.',1,'2026-10-24 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `production_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int unsigned NOT NULL,
  `sl_no` int NOT NULL DEFAULT '1',
  `material_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `hsn_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `received_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_rate` decimal(5,2) DEFAULT '18.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_poitem_po` (`purchase_order_id`),
  KEY `fk_poitem_mat` (`material_id`),
  CONSTRAINT `fk_poitem_mat` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_poitem_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
INSERT INTO `purchase_order_items` VALUES (1,1,1,1,'MS Plate 10mm IS2062 E250','2500x10000mm','72085100',30000.000,30000.000,'kg',62.50,1875000.00,18.00,'Prime quality SAIL Plates'),(2,1,2,3,'MS Plate 16mm IS2062 E250','2500x12000mm','72085100',50000.000,50000.000,'kg',64.20,3210000.00,18.00,'For NTPC ESP Casing'),(3,1,3,4,'MS Plate 20mm IS2062 E250','2500x10000mm','72085100',22700.000,22700.000,'kg',65.50,1486000.00,18.00,'For Base Plates'),(4,2,1,8,'ISMB 300 Structural Beam','Standard Length 12 Mtr','72163200',25000.000,25000.000,'kg',64.00,1600000.00,18.00,'JSPL Universal Beam'),(5,3,1,16,'Welding Electrode E7018 4mm','AWS A5.1 Low Hydrogen','83112000',3500.000,2000.000,'kg',145.00,507500.00,18.00,'ESAB OK 48.00'),(6,3,2,17,'MIG Wire 1.2mm ER70S-6','AWS A5.18 15kg spool','83112000',1200.000,1200.000,'kg',115.00,138000.00,18.00,'Copper coated MIG Wire');
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `po_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_date` date NOT NULL,
  `vendor_id` int unsigned NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `reference_so` int unsigned DEFAULT NULL COMMENT 'Against which sales order',
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amt` decimal(15,2) DEFAULT '0.00',
  `taxable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cgst_amount` decimal(15,2) DEFAULT '0.00',
  `sgst_amount` decimal(15,2) DEFAULT '0.00',
  `igst_amount` decimal(15,2) DEFAULT '0.00',
  `total_tax` decimal(15,2) DEFAULT '0.00',
  `freight` decimal(12,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_in_words` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_conditions` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','sent','acknowledged','partially_received','received','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `approved_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_po_no` (`po_no`),
  KEY `idx_status` (`status`),
  KEY `fk_po_vendor` (`vendor_id`),
  KEY `fk_po_so` (`reference_so`),
  KEY `fk_po_created` (`created_by`),
  KEY `fk_po_approved` (`approved_by`),
  CONSTRAINT `fk_po_approved` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_po_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_po_so` FOREIGN KEY (`reference_so`) REFERENCES `sales_orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_po_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (1,'PO-2627-001','2026-10-02',1,'2026-10-12','Heavy Steel Fabricators Yard, Jaipur Works',1,'100% Advance against Proforma Invoice',6571000.00,0.00,6571000.00,591390.00,591390.00,0.00,1182780.00,85000.00,7838780.00,'Rupees Seventy Eight Lakh Thirty Eight Thousand Seven Hundred Eighty Only','1. Material test certificates (TC) must accompany dispatch.\n2. Plates must be ultrasonic tested as per ASTM A435.\n3. Dimension tolerances as per IS 1852.','received','Received in full. GRN-2627-001 created.',3,1,'2026-10-02 17:14:10','2026-10-28 17:14:10'),(2,'PO-2627-002','2026-10-07',2,'2026-10-16','Heavy Steel Fabricators Yard, Jaipur Works',1,'100% against PI / RTGS',1600000.00,0.00,1600000.00,0.00,0.00,288000.00,288000.00,45000.00,1933000.00,'Rupees Nineteen Lakh Thirty Three Thousand Only','1. Beams ISMB 300 & 400 with test certificates.\n2. Supply from Raigarh plant.','received','Received. GRN-2627-002 created.',3,1,'2026-10-07 17:14:10','2026-10-28 17:14:10'),(3,'PO-2627-003','2026-10-16',3,'2026-10-30','Heavy Steel Fabricators Consumables Store, Jaipur',2,'30 Days Credit',650000.00,15000.00,635000.00,0.00,0.00,114300.00,114300.00,12000.00,761300.00,'Rupees Seven Lakh Sixty One Thousand Three Hundred Only','1. AWS E7018 low-hydrogen electrodes in vacuum packs.\n2. MIG wire spools in sealed cartons.','acknowledged','Partially received. Awaiting balance delivery.',3,1,'2026-10-16 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotation_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` int unsigned NOT NULL,
  `sl_no` int NOT NULL DEFAULT '1',
  `material_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `hsn_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_rate` decimal(5,2) DEFAULT '18.00',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_quotitem_quot` (`quotation_id`),
  KEY `fk_quotitem_mat` (`material_id`),
  CONSTRAINT `fk_quotitem_mat` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_quotitem_quot` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

LOCK TABLES `quotation_items` WRITE;
/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
INSERT INTO `quotation_items` VALUES (1,1,1,3,'ESP Casing Shell Panels with Stiffeners','Fabricated from 16mm IS2062 E250 plates with ISA 75x75x6 stiffeners','73089090',45000.000,'kg',88.00,3960000.00,18.00,'Complete with lifting lugs'),(2,1,2,8,'Heavy Support Columns & Bracings','Built up from ISMB 300 and ISMC 200 sections','73089090',32000.000,'kg',92.00,2944000.00,18.00,'Base plates machined'),(3,1,3,14,'Flanged Connecting Ducting Sections','100 NB to 400 NB seamless pipes and rolled ducting','73089090',10000.000,'kg',89.60,896000.00,18.00,'Flange drilling as per ASME B16.5'),(4,2,1,2,'Preheater Cyclone Body Shells','Rolled 12mm IS2062 E250 plates with internal wear liners','73089090',28000.000,'kg',90.00,2520000.00,18.00,'UltraTech Project Grade'),(5,2,2,1,'Riser Duct Sections and Transition Pieces','10mm IS2062 E250 fabricated conical pieces','73089090',15000.000,'kg',92.00,1380000.00,18.00,'Supplied with companion flanges'),(6,3,1,6,'Evaporator Calandria Shell Body','32mm SA516 Gr 70 boiler quality steel plate rolled and welded','73089090',25000.000,'kg',110.00,2750000.00,18.00,'Boiler Quality Plate'),(7,3,2,2,'Vapor Dome and Juice Piping Connections','12mm IS2062 with internal baffle plates','73089090',8000.000,'kg',100.00,800000.00,18.00,'Tested at 3.5 bar');
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `quotation_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `revision` int NOT NULL DEFAULT '0',
  `enquiry_id` int unsigned DEFAULT NULL,
  `client_id` int unsigned NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_period` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `freight_charges` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex-works, FOR, etc.',
  `taxes_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_pct` decimal(5,2) DEFAULT '0.00',
  `discount_amt` decimal(15,2) DEFAULT '0.00',
  `taxable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cgst_amount` decimal(15,2) DEFAULT '0.00',
  `sgst_amount` decimal(15,2) DEFAULT '0.00',
  `igst_amount` decimal(15,2) DEFAULT '0.00',
  `total_tax` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_in_words` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_conditions` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','sent','revised','accepted','rejected','expired','converted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` int unsigned DEFAULT NULL,
  `approved_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_quotation_no` (`quotation_no`),
  KEY `idx_status` (`status`),
  KEY `fk_quot_enq` (`enquiry_id`),
  KEY `fk_quot_client` (`client_id`),
  KEY `fk_quot_created` (`created_by`),
  KEY `fk_quot_approved` (`approved_by`),
  CONSTRAINT `fk_quot_approved` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_quot_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_quot_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_quot_enq` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
INSERT INTO `quotations` VALUES (1,'QTN-2627-001','2026-10-04','2026-12-03',0,1,1,'800MW Stage-IV Boiler Structure & ESP Ducting','Quotation for Heavy Steel Fabrication and Supply of ESP Ducting & Casing','15% Advance with PO, 75% Against Proforma Invoice before dispatch, 10% on successful site fitment within 30 days','Ex-Works Jaipur / Transportation by heavy hydraulic trailer','8 to 10 Weeks from approval of fabrication drawings','250000.00','IGST @ 18% applicable as supply is from Rajasthan to Telangana',7800000.00,2.00,156000.00,7644000.00,0.00,0.00,1375920.00,1375920.00,9019920.00,'Rupees Ninety Lakh Nineteen Thousand Nine Hundred Twenty Only','1. Material test certificates (Mill TC) will be furnished along with supply.\n2. Inspection by NTPC authorized third party (EIL/TUV) at our works.\n3. Shot blasting to SA 2.5 and Red Oxide primer applied before dispatch.','Client confirmed acceptance via LOI dated 12th','accepted',2,1,'2026-10-04 17:14:10','2026-10-28 17:14:10'),(2,'QTN-2627-002','2026-10-10','2026-12-09',0,2,5,'White Cement Kiln-2 Cyclone Preheater Ducting','Quotation for Cyclone Ducting, Riser Pipes and Structural Frames for UltraTech Cement','20% Advance, 80% against dispatch documents / 30 days credit','FOR Kharia Khangar Works, Jodhpur (Intra-state)','6 Weeks from PO','120000.00','CGST 9% + SGST 9% applicable for Rajasthan Intra-State sale',3900000.00,0.00,0.00,3900000.00,351000.00,351000.00,0.00,702000.00,4602000.00,'Rupees Forty Six Lakh Two Thousand Only','1. Price includes raw material, cutting, rolling, welding, NDT testing and primer.\n2. Delivery strictly as per project shutdown schedule.\n3. Liquidated damages @ 0.5% per week subject to max 5%.','Purchase Order released by UltraTech','accepted',2,1,'2026-10-10 17:14:10','2026-10-28 17:14:10'),(3,'QTN-2627-003','2026-10-18','2026-11-17',0,3,3,'Gola Sugar Unit Quadruple Effect Evaporator Body','Quotation for Heavy Fabricated MS Evaporator Calandria and Vapor Piping','25% Advance, 75% against dispatch','Ex-Works Jaipur (Inter-State UP)','10 Weeks','180000.00','IGST @ 18% applicable (Rajasthan to Uttar Pradesh)',3550000.00,1.50,53250.00,3496750.00,0.00,0.00,629415.00,629415.00,4126165.00,'Rupees Forty One Lakh Twenty Six Thousand One Hundred Sixty Five Only','1. Shell rolling and submerged arc longitudinal & circumferential welding.\n2. Hydraulic test at 4.5 kg/cm2 pressure before painting.\n3. Food grade epoxy coating for inner surfaces if required.','Under final negotiation with mill technical team','sent',2,1,'2026-10-18 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order_items`
--

DROP TABLE IF EXISTS `sales_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sales_order_id` int unsigned NOT NULL,
  `sl_no` int NOT NULL DEFAULT '1',
  `material_id` int unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `hsn_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,3) NOT NULL DEFAULT '0.000',
  `dispatched_qty` decimal(12,3) NOT NULL DEFAULT '0.000',
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `rate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gst_rate` decimal(5,2) DEFAULT '18.00',
  `status` enum('pending','in_production','ready','dispatched') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_soitem_so` (`sales_order_id`),
  KEY `fk_soitem_mat` (`material_id`),
  CONSTRAINT `fk_soitem_mat` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_soitem_so` FOREIGN KEY (`sales_order_id`) REFERENCES `sales_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order_items`
--

LOCK TABLES `sales_order_items` WRITE;
/*!40000 ALTER TABLE `sales_order_items` DISABLE KEYS */;
INSERT INTO `sales_order_items` VALUES (1,1,1,3,'ESP Casing Shell Panels with Stiffeners','Fabricated from 16mm IS2062 E250 plates with ISA 75x75x6 stiffeners','73089090',45000.000,20000.000,'kg',88.00,3960000.00,18.00,'in_production','Part batch dispatched under DC-2627-001'),(2,1,2,8,'Heavy Support Columns & Bracings','Built up from ISMB 300 and ISMC 200 sections','73089090',32000.000,0.000,'kg',92.00,2944000.00,18.00,'in_production','Shop floor fit-up ongoing'),(3,1,3,14,'Flanged Connecting Ducting Sections','100 NB to 400 NB seamless pipes and rolled ducting','73089090',10000.000,0.000,'kg',89.60,896000.00,18.00,'in_production','Flange welding under inspection'),(4,2,1,2,'Preheater Cyclone Body Shells','Rolled 12mm IS2062 E250 plates with internal wear liners','73089090',28000.000,14000.000,'kg',90.00,2520000.00,18.00,'in_production','Phase 1 dispatched'),(5,2,2,1,'Riser Duct Sections and Transition Pieces','10mm IS2062 E250 fabricated conical pieces','73089090',15000.000,0.000,'kg',92.00,1380000.00,18.00,'in_production','Rolling and edge beveling completed');
/*!40000 ALTER TABLE `sales_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_orders`
--

DROP TABLE IF EXISTS `sales_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `so_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_date` date NOT NULL,
  `quotation_id` int unsigned DEFAULT NULL,
  `client_id` int unsigned NOT NULL,
  `client_po_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Client purchase order reference',
  `client_po_date` date DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amt` decimal(15,2) DEFAULT '0.00',
  `taxable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cgst_amount` decimal(15,2) DEFAULT '0.00',
  `sgst_amount` decimal(15,2) DEFAULT '0.00',
  `igst_amount` decimal(15,2) DEFAULT '0.00',
  `total_tax` decimal(15,2) DEFAULT '0.00',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_in_words` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','confirmed','in_production','partially_dispatched','dispatched','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_so_no` (`so_no`),
  KEY `idx_status` (`status`),
  KEY `fk_so_quot` (`quotation_id`),
  KEY `fk_so_client` (`client_id`),
  KEY `fk_so_created` (`created_by`),
  CONSTRAINT `fk_so_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_so_created` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_so_quot` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_orders`
--

LOCK TABLES `sales_orders` WRITE;
/*!40000 ALTER TABLE `sales_orders` DISABLE KEYS */;
INSERT INTO `sales_orders` VALUES (1,'SO-2627-001','2026-10-08',1,1,'NTPC/RAM/PUR/2026/884','2026-10-07','800MW Stage-IV Boiler Structure & ESP Ducting','Site Store, NTPC Super Thermal Power Project, Ramagundam, Dist. Peddapalli, Telangana - 505215','15% Advance, 75% Proforma, 10% Site fitment','2026-12-07',7800000.00,156000.00,7644000.00,0.00,0.00,1375920.00,1375920.00,9019920.00,'Rupees Ninety Lakh Nineteen Thousand Nine Hundred Twenty Only','in_production','Priority client. Advance received. Job Order JOB-2627-001 issued.',2,'2026-10-08 17:14:10','2026-10-28 17:14:10'),(2,'SO-2627-002','2026-10-14',2,5,'UTCL/BW/2026/MECH-102','2026-10-13','White Cement Kiln-2 Cyclone Preheater Ducting','Central Store, UltraTech Cement Ltd, Kharia Khangar, Tehsil Bhopalgarh, Jodhpur, Rajasthan - 342606','20% Advance, 80% against dispatch','2026-11-22',3900000.00,0.00,3900000.00,351000.00,351000.00,0.00,702000.00,4602000.00,'Rupees Forty Six Lakh Two Thousand Only','in_production','Shutdown scheduled next month. High priority fabrication.',2,'2026-10-14 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `sales_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `material_id` int unsigned NOT NULL,
  `transaction_type` enum('purchase_in','production_issue','production_return','sales_out','adjustment_in','adjustment_out','transfer','opening') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'purchase_order, production_job, sales_order, etc.',
  `reference_id` int unsigned DEFAULT NULL,
  `quantity` decimal(14,3) NOT NULL,
  `rate` decimal(12,2) DEFAULT '0.00',
  `warehouse` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'main',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_material` (`material_id`),
  KEY `idx_txn_type` (`transaction_type`),
  KEY `idx_ref` (`reference_type`,`reference_id`),
  KEY `fk_stktxn_user` (`created_by`),
  CONSTRAINT `fk_stktxn_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stktxn_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
INSERT INTO `stock_transactions` VALUES (1,1,'purchase_in','GRN',1,50000.000,62.50,'Yard-A (Plates)','Initial Stock Receipt against SAIL Mill Invoice',1,'2026-10-03 17:14:10'),(2,3,'purchase_in','GRN',1,55000.000,64.20,'Yard-A (Plates)','Initial Stock Receipt against SAIL Mill Invoice',1,'2026-10-03 17:14:10'),(3,8,'purchase_in','GRN',2,25000.000,64.00,'Structural Bay 1','Receipt from JSPL for Power Plant Columns',1,'2026-10-08 17:14:10'),(4,16,'purchase_in','GRN',3,5000.000,145.00,'Consumables Store','ESAB Electrodes E7018 Batch E2604',1,'2026-10-10 17:14:10'),(5,1,'production_issue','Material Issue',1,1500.000,62.50,'Yard-A (Plates)','Issued for NTPC ESP Casing Flanges',4,'2026-10-18 17:14:10'),(6,8,'production_issue','Material Issue',1,2500.000,64.00,'Structural Bay 1','Issued for UltraTech Cyclone Duct Girders',4,'2026-10-20 17:14:10');
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','manager','sales','purchase','store','production','accounts','dispatch','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','System Administrator','9876543210','admin',NULL,1,'2026-10-28 12:23:53',NULL,NULL,'2026-10-28 16:54:24','2026-10-28 12:23:53'),(2,'sales_head','sales@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','Rajesh Sharma (Sales Head)','9829012345','sales',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42'),(3,'purchase_mgr','purchase@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','Vikram Singh (Purchase Mgr)','9829023456','purchase',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42'),(4,'store_keeper','store@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','Ramesh Yadav (Store Incharge)','9829034567','store',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42'),(5,'prod_head','production@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','Er. Arvind Verma (Works Mgr)','9829045678','production',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42'),(6,'accounts_mgr','accounts@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','CA Manoj Agrawal (Finance)','9829056789','accounts',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42'),(7,'dispatch_exec','dispatch@steelerp.com','$2y$12$FPaOyPpeQQkkuxRIN0.xFuJeZjj3aKVv8MMHyDb/SaLb8dWdc5WmW','Sunil Choudhary (Logistics)','9829067890','dispatch',NULL,1,NULL,NULL,NULL,'2026-10-28 17:14:10','2026-10-28 17:43:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vendor_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_type` enum('raw_material','consumables','services','transport','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'raw_material',
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pincode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'India',
  `gstin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ifsc` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating` tinyint DEFAULT '0' COMMENT '1-5 star rating',
  `created_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_vendor_code` (`vendor_code`),
  KEY `idx_vendor_type` (`vendor_type`),
  KEY `fk_vendors_created_by` (`created_by`),
  CONSTRAINT `fk_vendors_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'VEN-STL-001','Steel Authority of India Ltd (SAIL)','B. K. Mohapatra (Branch Mgr)','bso.jaipur@sail.in','0141-2367788','9437012345','raw_material','BSO Jaipur, Nehru Place','Tonk Road','Jaipur','Rajasthan','302015','India','08AAACS4115G1ZS','AAACS4115G','LC at sight / 100% Advance against PI','State Bank of India','30012345678','SBIN0004128','Primary Steel Producer - IS2062 E250 / E350 Plates and Structural Beams',1,5,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(2,'VEN-STL-002','Jindal Steel & Power Ltd (JSPL)','Amit Singhal (Key Account Mgr)','delhi.sales@jindalsteel.com','011-41462000','9810055443','raw_material','Jindal Centre, 12 Bhikaiji Cama Place','Sector 1','New Delhi','Delhi','110066','India','07AAACJ0732A1Z1','AAACJ0732A','100% against Proforma / Advance','HDFC Bank','50200012345678','HDFC0000003','Heavy structural columns, Universal Beams and SA516 Gr 70 boiler plates',1,5,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(3,'VEN-WLD-001','ESAB India Limited','C. Sundaram (Area Sales Mgr)','north.sales@esab.co.in','0129-4081000','9818044332','consumables','Plot 13, Sector 6, Industrial Area','Mathura Road','Faridabad','Haryana','121006','India','06AAACE0371E1Z0','AAACE0371E','30 Days Credit','Citibank NA','0345678901','CITI0000002','AWS E7018 Electrodes, MIG Spools ER70S-6, Submerged Arc Welding Flux',1,5,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(4,'VEN-PNT-001','Asian Paints Industrial Coatings','Deepak Arora (Technical Rep)','industrial.north@asianpaints.com','01274-245000','9811099887','consumables','Plot 55, Bawal Industrial Area','Phase 1','Rewari','Haryana','123501','India','06AAACA2144P1Z3','AAACA2144P','30 Days Net','ICICI Bank','000705001234','ICIC0000007','High performance Red Oxide Zinc Phosphate Primer, Epoxy MIO, PU top coats',1,4,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(5,'VEN-FST-001','Unbrako (Deepak Fasteners Ltd)','Gurpreet Singh (Sales Head)','sales@deepakfasteners.com','0161-5182000','9814033221','consumables','4th Floor, First Mall','The Mall','Ludhiana','Punjab','141001','India','03AAACD3396D1ZZ','AAACD3396D','45 Days Credit','Punjab National Bank','0123002100045678','PUNB0012300','High Tensile Grade 8.8, 10.9 Foundation Bolts, Hex Bolts and Structural Nuts',1,5,1,'2026-10-28 17:14:10','2026-10-28 17:14:10'),(6,'VEN-LOG-001','Jaipur Golden Transport Co. Pvt. Ltd.','Rakesh Sharma (Regional Mgr)','jaipur@jaipurgolden.com','0141-2334455','9829099881','transport','Transport Nagar, Agra Road','Bay 14-16','Jaipur','Rajasthan','302003','India','08AAACJ1288P1Z6','AAACJ1288P','15 Days from delivery','Bank of Baroda','12340200001234','BARB0JAIPUR','Heavy Hydraulic multi-axle trailers & 40ft flat-bed trailers for oversized steel girders',1,4,1,'2026-10-28 17:14:10','2026-10-28 17:14:10');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-28 17:54:07
