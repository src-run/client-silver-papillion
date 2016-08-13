-- MySQL dump 10.13  Distrib 5.6.31-77.0, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: src-silver-papillon
-- ------------------------------------------------------
-- Server version	5.6.31-77.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carousel_slide`
--

DROP TABLE IF EXISTS `carousel_slide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carousel_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL,
  `weight` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image` varchar(1020) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carousel_slide`
--

LOCK TABLES `carousel_slide` WRITE;
/*!40000 ALTER TABLE `carousel_slide` DISABLE KEYS */;
INSERT INTO `carousel_slide` VALUES (1,1,0,NULL,'01-store-front-sign.jpg'),(2,1,1,NULL,'02-store-front-table.jpg'),(3,1,2,NULL,'03-store-inside-display-01.jpg'),(5,1,4,NULL,'05-store-inside-cases-02.jpg'),(6,1,3,NULL,'04-store-inside-cases-01.jpg'),(7,1,5,NULL,'06-store-inside-wall-3.jpg'),(8,1,6,NULL,'07-nearby-bridge.jpg'),(9,1,7,NULL,'08-store-front-wide.jpg');
/*!40000 ALTER TABLE `carousel_slide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_block`
--

DROP TABLE IF EXISTS `content_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `properties` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:simple_array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_68D8C3F05E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_block`
--

LOCK TABLES `content_block` WRITE;
/*!40000 ALTER TABLE `content_block` DISABLE KEYS */;
INSERT INTO `content_block` VALUES (1,'about.copyright','Copyright','<p>&copy; 2016 Silver Papillon</p>',NULL),(3,'about.phone','Our <em>Phone</em>','<p>+1 (860)&nbsp;415-8737</p>',NULL),(4,'page.home.banner','Shop Now','<p>Browse our large collection of Wine Caddies and other items now!</p>','[route]=app_category_list'),(5,'page.feed.social','Social','<p>Looking for more news and information?! Join us on Facebook and be sure to like our page!</p>','[name]=Visit our Facebook Page,[link]=https://www.facebook.com/SilverPapillonOfMystic'),(6,'about.address','Our <em>Address</em>','<p>11 W Main St<br />\r\nMystic<br />\r\nConnecticut 06355</p>',NULL),(7,'about.description','<em>Silver Papillon</em>','<p>Located in the heart of historic downtown Mystic near the drawbridge. We carry a wide array of jewelry in a variety of prices and styles. We offer settings in larimar, mystic topaz, moonstone, opal and so many other gems. Our popular wine caddies offer something for everyone. Other selections are scarfs, Lyme Logo shirts, handbags, pictures, clocks, glasses&hellip; and so much more! There are many nautical items to choose from as well.</p>',NULL),(8,'about.contact','Contact <em>Us</em>','<p>Have questions, comments, or want to simply send a general inquary? Complete the below form and we will get back you within 48 hours.</p>',NULL);
/*!40000 ALTER TABLE `content_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order`
--

DROP TABLE IF EXISTS `customer_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_order` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `shipped_on` datetime DEFAULT NULL,
  `shipping` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:object)',
  PRIMARY KEY (`id`),
  KEY `IDX_3B1CE6A3A76ED395` (`user_id`),
  CONSTRAINT `FK_3B1CE6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_order`
--

LOCK TABLES `customer_order` WRITE;
/*!40000 ALTER TABLE `customer_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order_item`
--

DROP TABLE IF EXISTS `customer_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `order_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` smallint(6) NOT NULL,
  `tax_rate` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AF231B8B4584665A` (`product_id`),
  KEY `IDX_AF231B8B8D9F6D38` (`order_id`),
  CONSTRAINT `FK_AF231B8B4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_AF231B8B8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `customer_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_order_item`
--

LOCK TABLES `customer_order_item` WRITE;
/*!40000 ALTER TABLE `customer_order_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_order_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hours`
--

DROP TABLE IF EXISTS `hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dow` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `timeOpen` time DEFAULT NULL,
  `timeClose` time DEFAULT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hours`
--

LOCK TABLES `hours` WRITE;
/*!40000 ALTER TABLE `hours` DISABLE KEYS */;
INSERT INTO `hours` VALUES (2,'Monday',0,'11:00:00','20:00:00',1),(3,'Tueaday',0,'11:00:00','20:00:00',2),(4,'Wednesday',0,'11:00:00','20:00:00',3),(5,'Thursday',0,'11:00:00','20:00:00',4),(6,'Friday',0,'11:00:00','20:00:00',5),(7,'Saturday',0,'11:00:00','20:00:00',6),(8,'Sunday',0,'11:00:00','20:00:00',7);
/*!40000 ALTER TABLE `hours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20160729111431'),('20160729131821'),('20160729153307'),('20160729153514'),('20160729155803');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `tags` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:simple_array)',
  `enabled` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04ADA77A0A8C` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1258 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1102,1,'2016-08-13 18:30:12','2016-08-13 18:30:12','19 Holes',NULL,NULL,0,0,'658545-19-holes.png',45,'658545'),(1103,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','5-bottle Wine Basket',NULL,NULL,0,0,'659597-5-bottle-wine-basket.jpg',45,'659597'),(1104,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Admin Assistant',NULL,NULL,0,0,'658323-admin-assistant.png',45,'658323'),(1105,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Airplane Pilot',NULL,NULL,0,0,'641196-airplane-pilot.png',45,'641196'),(1106,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Airplane With Pilot',NULL,NULL,0,0,'658538-airplane-with-pilot.jpg',45,'658538'),(1107,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Angel',NULL,NULL,0,0,'659597-angel.png',45,'659597'),(1108,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Angel With Halo',NULL,NULL,1,0,'643039-angel-with-halo.png',45,'643039'),(1109,1,'2016-08-13 18:30:13','2016-08-13 18:30:13','Architect',NULL,NULL,0,0,'659832-architect.jpg',45,'659832'),(1110,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Artist With Easel',NULL,NULL,1,0,'641172-artist-with-easel.jpg',45,'641172'),(1111,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Baker',NULL,NULL,0,0,'641370-baker.jpg',45,'641370'),(1112,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Barber',NULL,NULL,0,0,'658545-barber.png',45,'658545'),(1113,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Bartender',NULL,NULL,0,0,'659832-bartender.png',45,'659832'),(1114,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Bartender With Glass Tower',NULL,NULL,0,0,'658538-bartender-with-glass-tower.jpg',45,'658538'),(1115,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Bartender With Stools',NULL,NULL,1,0,'641196-bartender-with-stools.png',45,'641196'),(1116,1,'2016-08-13 18:30:14','2016-08-13 18:30:14','Bbq Cook Silver',NULL,NULL,1,0,'641448-bbq-cook-silver.png',45,'641448'),(1117,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Big Rig Tractor',NULL,NULL,0,0,'658484-big-rig-tractor.jpg',45,'658484'),(1118,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Biker',NULL,NULL,0,0,'641370-biker.png',45,'641370'),(1119,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Birthday Girl With Balloon',NULL,NULL,0,0,'658491-birthday-girl-with-balloon.jpg',45,'658491'),(1120,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Boxer',NULL,NULL,0,0,'659832-boxer.jpg',45,'659832'),(1121,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Captain',NULL,NULL,1,1,'641448-captain.png',45,'641448'),(1122,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Captain Painted',NULL,NULL,1,1,'658484-captain-painted.png',45,'658484'),(1123,1,'2016-08-13 18:30:15','2016-08-13 18:30:15','Captain Silver',NULL,NULL,1,0,'658491-captain-silver.png',45,'658491'),(1124,1,'2016-08-13 18:30:16','2016-08-13 18:30:16','Carpenter',NULL,NULL,0,0,'641370-carpenter.jpg',45,'641370'),(1125,1,'2016-08-13 18:30:16','2016-08-13 18:30:16','Cellist',NULL,NULL,1,0,'641172-cellist.png',45,'641172'),(1126,1,'2016-08-13 18:30:16','2016-08-13 18:30:16','Chef On The Go',NULL,NULL,0,0,'641134-chef-on-the-go.jpg',45,'641134'),(1127,1,'2016-08-13 18:30:16','2016-08-13 18:30:16','Chef Silver',NULL,NULL,0,0,'641196-chef-silver.png',45,'641196'),(1128,1,'2016-08-13 18:30:16','2016-08-13 18:48:19','Chef With Dish & Fork',NULL,NULL,0,0,'643022-chef-with-dish---038--fork.jpg',45,'643022'),(1129,1,'2016-08-13 18:30:16','2016-08-13 18:30:16','Chef With Sign',NULL,NULL,0,0,'641370-chef-with-sign.png',45,'641370'),(1130,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Chef With Whisk Silver',NULL,NULL,0,0,'641172-chef-with-whisk-silver.png',45,'641172'),(1131,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Choir Conductor',NULL,NULL,0,0,'658545-choir-conductor.png',45,'658545'),(1132,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Civil War Soldier',NULL,NULL,0,0,'641448-civil-war-soldier.png',45,'641448'),(1133,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Cocktail Waitress',NULL,NULL,0,0,'641448-cocktail-waitress.png',45,'641448'),(1134,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Contractor',NULL,NULL,0,0,'659597-contractor.jpg',45,'659597'),(1135,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Cool Cat Musician',NULL,NULL,1,0,'640892-cool-cat-musician.png',45,'640892'),(1136,1,'2016-08-13 18:30:17','2016-08-13 18:30:17','Cool Dog Drummer',NULL,NULL,0,0,'641196-cool-dog-drummer.png',45,'641196'),(1137,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Cyclist Silver',NULL,NULL,1,0,'641196-cyclist-silver.png',45,'641196'),(1138,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Cymbal Painted',NULL,NULL,1,0,'641172-cymbal-painted.png',45,'641172'),(1139,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Deep Sea Fisherman',NULL,NULL,1,0,'658545-deep-sea-fisherman.png',45,'658545'),(1140,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Dentist',NULL,NULL,1,0,'643039-dentist.png',45,'643039'),(1141,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Dj',NULL,NULL,0,0,'641134-dj.jpg',45,'641134'),(1142,1,'2016-08-13 18:30:18','2016-08-13 18:30:18','Doctor',NULL,NULL,1,0,'659597-doctor.png',45,'659597'),(1143,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Doctor Silver',NULL,NULL,0,0,'658491-doctor-silver.png',45,'658491'),(1144,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Drinking Dog',NULL,NULL,0,0,'641370-drinking-dog.jpg',45,'641370'),(1145,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Drummer',NULL,NULL,1,0,'641370-drummer.png',45,'641370'),(1146,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Drummer Painted',NULL,NULL,0,0,'658538-drummer-painted.png',45,'658538'),(1147,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Fashion Lady',NULL,NULL,0,0,'643039-fashion-lady.jpg',45,'643039'),(1148,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Female Chef',NULL,NULL,1,0,'658279-female-chef.png',45,'658279'),(1149,1,'2016-08-13 18:30:19','2016-08-13 18:30:19','Female Chef With Sign',NULL,NULL,1,0,'658491-female-chef-with-sign.png',45,'658491'),(1150,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Female Gardener',NULL,NULL,0,0,'643022-female-gardener.png',45,'643022'),(1151,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Female Golfer',NULL,NULL,1,0,'640489-female-golfer.png',45,'640489'),(1152,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Female Guitarist',NULL,NULL,1,0,'641172-female-guitarist.png',45,'641172'),(1153,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Female Nurse',NULL,NULL,1,0,'658538-female-nurse.png',45,'658538'),(1154,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Female Veterinarian',NULL,NULL,0,0,'658545-female-veterinarian.jpg',45,'658545'),(1155,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Fiddler Silver',NULL,NULL,0,0,'641134-fiddler-silver.png',45,'641134'),(1156,1,'2016-08-13 18:30:20','2016-08-13 18:30:20','Fireman With Extinguisher',NULL,NULL,1,0,'643022-fireman-with-extinguisher.png',45,'643022'),(1157,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fisherman',NULL,NULL,0,0,'640489-fisherman.png',45,'640489'),(1158,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fisherman On Boat With Dog',NULL,NULL,0,0,'658323-fisherman-on-boat-with-dog.jpg',45,'658323'),(1159,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fisherman Silver',NULL,NULL,0,0,'658491-fisherman-silver.png',45,'658491'),(1160,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fisherman With Fish',NULL,NULL,1,0,'658491-fisherman-with-fish.png',45,'658491'),(1161,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fishing Moose',NULL,NULL,0,0,'658491-fishing-moose.png',45,'658491'),(1162,1,'2016-08-13 18:30:21','2016-08-13 18:30:21','Fishing On Barrels',NULL,NULL,0,0,'641370-fishing-on-barrels.jpg',45,'641370'),(1163,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Football Helmet',NULL,NULL,0,0,'641196-football-helmet.jpg',45,'641196'),(1164,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Football Player',NULL,NULL,0,0,'659832-football-player.png',45,'659832'),(1165,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Football Player Silver',NULL,NULL,1,0,'659832-football-player-silver.png',45,'659832'),(1166,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Golf Caddy',NULL,NULL,1,0,'641370-golf-caddy.png',45,'641370'),(1167,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Golf Cart',NULL,NULL,0,0,'641172-golf-cart.png',45,'641172'),(1168,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Golf Cart With Golfer',NULL,NULL,0,0,'640892-golf-cart-with-golfer.jpg',45,'640892'),(1169,1,'2016-08-13 18:30:22','2016-08-13 18:30:22','Golfer',NULL,NULL,1,1,'641448-golfer.png',45,'641448'),(1170,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Golfer Glass Wine Stopper',NULL,NULL,0,0,'641134-golfer-glass-wine-stopper.png',45,'641134'),(1171,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Golfer Silver',NULL,NULL,1,0,'641196-golfer-silver.png',45,'641196'),(1172,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Graduate',NULL,NULL,1,0,'658323-graduate.png',45,'658323'),(1173,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Grape Harvester',NULL,NULL,0,0,'643022-grape-harvester.png',45,'643022'),(1174,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Grapevine Bottle Hugger',NULL,NULL,0,0,'658545-grapevine-bottle-hugger.png',45,'658545'),(1175,1,'2016-08-13 18:30:23','2016-08-13 18:30:23','Grill Master',NULL,NULL,1,0,'659832-grill-master.png',45,'659832'),(1176,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','Hairdresser Silver',NULL,NULL,0,0,'659597-hairdresser-silver.png',45,'659597'),(1177,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','Happy Dog Silver',NULL,NULL,1,0,'641172-happy-dog-silver.png',45,'641172'),(1178,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','High Roller',NULL,NULL,0,0,'641370-high-roller.png',45,'641370'),(1179,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','Hiker',NULL,NULL,0,0,'643022-hiker.jpg',45,'643022'),(1180,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','House Painter',NULL,NULL,0,0,'641134-house-painter.jpg',45,'641134'),(1181,1,'2016-08-13 18:30:24','2016-08-13 18:41:31','How Does This Look?',NULL,NULL,0,0,'658538-how-does-this-look-.jpg',45,'658538'),(1182,1,'2016-08-13 18:30:24','2016-08-13 18:30:24','Hunter',NULL,NULL,0,0,'643039-hunter.png',45,'643039'),(1183,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','Hunting Moose',NULL,NULL,0,0,'658491-hunting-moose.png',45,'658491'),(1184,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','Ice Hockey Player',NULL,NULL,0,0,'640489-ice-hockey-player.png',45,'640489'),(1185,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','Iron Chef',NULL,NULL,1,0,'658538-iron-chef.png',45,'658538'),(1186,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','Jazz Drummer Silver',NULL,NULL,0,0,'641448-jazz-drummer-silver.png',45,'641448'),(1187,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','Keyboard Player',NULL,NULL,0,0,'641370-keyboard-player.png',45,'641370'),(1188,1,'2016-08-13 18:30:25','2016-08-13 18:30:25','King',NULL,NULL,1,0,'658484-king.png',45,'658484'),(1189,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','King Painted',NULL,NULL,0,0,'641448-king-painted.png',45,'641448'),(1190,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','Kingsguard',NULL,NULL,1,0,'641370-kingsguard.jpg',45,'641370'),(1191,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','Knight Silver',NULL,NULL,1,0,'640892-knight-silver.png',45,'640892'),(1192,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','Knight With Axe And Sword',NULL,NULL,0,0,'641172-knight-with-axe-and-sword.jpg',45,'641172'),(1193,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','Knight With Battle Axe',NULL,NULL,0,0,'659597-knight-with-battle-axe.jpg',45,'659597'),(1194,1,'2016-08-13 18:30:26','2016-08-13 18:30:26','Knight With Sword And Shield',NULL,NULL,0,0,'658323-knight-with-sword-and-shield.jpg',45,'658323'),(1195,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lady Captain',NULL,NULL,0,0,'643022-lady-captain.png',45,'643022'),(1196,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lead Guitar',NULL,NULL,0,0,'659597-lead-guitar.png',45,'659597'),(1197,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lifeguard',NULL,NULL,1,0,'643022-lifeguard.jpg',45,'643022'),(1198,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lighthouse',NULL,NULL,0,0,'659597-lighthouse.png',45,'659597'),(1199,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lighthouse Painted',NULL,NULL,1,0,'641196-lighthouse-painted.png',45,'641196'),(1200,1,'2016-08-13 18:30:27','2016-08-13 18:30:27','Lighthouse Silver',NULL,NULL,1,0,'658545-lighthouse-silver.png',45,'658545'),(1201,1,'2016-08-13 18:30:27','2016-08-13 18:38:56','Love & Wine',NULL,NULL,0,0,'643039-love--038-wine.jpg',45,'643039'),(1202,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','Male Tennis Player',NULL,NULL,0,0,'658545-male-tennis-player.png',45,'658545'),(1203,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','Master Chef With 6 Bottles',NULL,NULL,0,0,'643039-master-chef-with-6-bottles.jpg',45,'643039'),(1204,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','Moses',NULL,NULL,0,0,'658323-moses.png',45,'658323'),(1205,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','Motorcycle Rider',NULL,NULL,1,1,'658323-motorcycle-rider.png',45,'658323'),(1206,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','Nautical Wheel With Fish Net',NULL,NULL,1,1,'641196-nautical-wheel-with-fish-net.jpg',45,'641196'),(1207,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','New Baseball Player',NULL,NULL,0,0,'658538-new-baseball-player.jpg',45,'658538'),(1208,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','New Cowboy',NULL,NULL,0,0,'658538-new-cowboy.jpg',45,'658538'),(1209,1,'2016-08-13 18:30:28','2016-08-13 18:30:28','New Dentist',NULL,NULL,0,0,'641196-new-dentist.jpg',45,'641196'),(1210,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','New Female Nurse',NULL,NULL,0,0,'659597-new-female-nurse.jpg',45,'659597'),(1211,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','New Female Tennis Player',NULL,NULL,0,0,'659832-new-female-tennis-player.jpg',45,'659832'),(1212,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','New Fireman',NULL,NULL,0,0,'643039-new-fireman.jpg',45,'643039'),(1213,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','New Fisherman',NULL,NULL,0,0,'643022-new-fisherman.jpg',45,'643022'),(1214,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','New Motorcycle Rider',NULL,NULL,0,0,'658545-new-motorcycle-rider.jpg',45,'658545'),(1215,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','Pirate',NULL,NULL,1,1,'641448-pirate.png',45,'641448'),(1216,1,'2016-08-13 18:30:29','2016-08-13 18:30:29','Policeman',NULL,NULL,0,0,'658279-policeman.png',45,'658279'),(1217,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Policeman Silver',NULL,NULL,1,1,'641134-policeman-silver.png',45,'641134'),(1218,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Postman',NULL,NULL,0,0,'643022-postman.png',45,'643022'),(1219,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Preacher',NULL,NULL,0,0,'641196-preacher.png',45,'641196'),(1220,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Red Sailboat Wine Stopper',NULL,NULL,0,0,'640892-red-sailboat-wine-stopper.png',45,'640892'),(1221,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Rock Star',NULL,NULL,1,1,'658484-rock-star.png',45,'658484'),(1222,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Sailboat',NULL,NULL,1,1,'658484-sailboat.jpg',45,'658484'),(1223,1,'2016-08-13 18:30:30','2016-08-13 18:30:30','Santa Glass Wine Stopper',NULL,NULL,0,0,'640489-santa-glass-wine-stopper.png',45,'640489'),(1224,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Shell Wine Stopper',NULL,NULL,0,0,'640892-shell-wine-stopper.png',45,'640892'),(1225,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Shopper',NULL,NULL,1,1,'640892-shopper.png',45,'640892'),(1226,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Short Order Cook',NULL,NULL,0,0,'659832-short-order-cook.jpg',45,'659832'),(1227,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Skier',NULL,NULL,0,0,'641196-skier.jpg',45,'641196'),(1228,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Skiing Moose',NULL,NULL,1,1,'640892-skiing-moose.png',45,'640892'),(1229,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Snowman Glass Wine Stopper',NULL,NULL,0,0,'658538-snowman-glass-wine-stopper.png',45,'658538'),(1230,1,'2016-08-13 18:30:31','2016-08-13 18:30:31','Sommelier',NULL,NULL,1,1,'640892-sommelier.png',45,'640892'),(1231,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','Sport Motorcycle Rider',NULL,NULL,1,1,'641448-sport-motorcycle-rider.jpg',45,'641448'),(1232,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','Surfer',NULL,NULL,1,1,'640892-surfer.png',45,'640892'),(1233,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','Surgeon',NULL,NULL,0,0,'658484-surgeon.jpg',45,'658484'),(1234,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','Tailgater',NULL,NULL,1,1,'658484-tailgater.png',45,'658484'),(1235,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','Teacher',NULL,NULL,1,1,'643022-teacher.jpg',45,'643022'),(1236,1,'2016-08-13 18:30:32','2016-08-13 18:30:32','The Boss',NULL,NULL,0,0,'641134-the-boss.png',45,'641134'),(1237,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Tourist',NULL,NULL,0,0,'641370-tourist.png',45,'641370'),(1238,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Tractor Driver',NULL,NULL,1,1,'641134-tractor-driver.jpg',45,'641134'),(1239,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Traveler',NULL,NULL,0,0,'641196-traveler.png',45,'641196'),(1240,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Truck Driver',NULL,NULL,1,1,'659597-truck-driver.jpg',45,'659597'),(1241,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Vacationing Fisherman',NULL,NULL,0,0,'658484-vacationing-fisherman.jpg',45,'658484'),(1242,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Veterinarian',NULL,NULL,1,1,'658545-veterinarian.png',45,'658545'),(1243,1,'2016-08-13 18:30:33','2016-08-13 18:30:33','Vineyard Worker',NULL,NULL,0,0,'658545-vineyard-worker.jpg',45,'658545'),(1244,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Vintage Car',NULL,NULL,1,1,'641134-vintage-car.jpg',45,'641134'),(1245,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Violinist',NULL,NULL,1,1,'641196-violinist.png',45,'641196'),(1246,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Waiter',NULL,NULL,0,0,'658545-waiter.png',45,'658545'),(1247,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Waiter Painted',NULL,NULL,0,0,'658538-waiter-painted.png',45,'658538'),(1248,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Waiter Silver',NULL,NULL,0,0,'659597-waiter-silver.png',45,'659597'),(1249,1,'2016-08-13 18:30:34','2016-08-13 18:30:34','Waiter Wine Server',NULL,NULL,1,1,'640892-waiter-wine-server.jpg',45,'640892'),(1250,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wedding Couple',NULL,NULL,0,0,'659597-wedding-couple.jpg',45,'659597'),(1251,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wedding Day Kiss',NULL,NULL,1,0,'641196-wedding-day-kiss.jpg',45,'641196'),(1252,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Welcome',NULL,NULL,0,0,'641172-welcome.png',45,'641172'),(1253,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wine Lover',NULL,NULL,0,0,'658538-wine-lover.jpg',45,'658538'),(1254,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wine Server',NULL,NULL,0,0,'659832-wine-server.png',45,'659832'),(1255,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wine Server Painted',NULL,NULL,0,0,'658538-wine-server-painted.png',45,'658538'),(1256,1,'2016-08-13 18:30:35','2016-08-13 18:30:35','Wine Taster',NULL,NULL,0,0,'641370-wine-taster.png',45,'641370'),(1257,1,'2016-08-13 18:30:36','2016-08-13 18:30:36','Young Captain',NULL,NULL,0,0,'658484-young-captain.png',45,'658484');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `name` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image` varchar(510) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_category`
--

LOCK TABLES `product_category` WRITE;
/*!40000 ALTER TABLE `product_category` DISABLE KEYS */;
INSERT INTO `product_category` VALUES (1,1,0,'Wine Caddies',NULL,NULL);
/*!40000 ALTER TABLE `product_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_8D93D649F5B7AF75` (`address_id`),
  CONSTRAINT `FK_8D93D649F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,NULL,'admin','admin','rmf@src.run','rmf@src.run',1,'eet93zwb6t4wscogs0k4s88cwcs8gok','$2y$13$h6cjtma1uIQeAHpO7D8QQ.EmsY3KElMi1uHl8Vw8WJl1NWwwcOp3u','2016-08-13 18:15:49',0,0,NULL,NULL,NULL,'a:1:{i:0;s:10:\"ROLE_ADMIN\";}',0,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-13 19:01:21
