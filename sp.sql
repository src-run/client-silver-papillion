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
INSERT INTO `content_block` VALUES (1,'footer','Copyright','<p>&copy; 2016 Silver Papillon. Designed and maintained by <a href=\"https://src.run\">Source Consulting, LLC</a>.</p>','[address]=11 W Main St,[city]=Mystic,[state]=Connecticut,[phone]=+1 (860) 415-8737'),(3,'about.phone','Our <em>Phone</em>','<p>+1 (860)&nbsp;415-8737</p>',NULL),(4,'page.home.banner','Shop Now','<p>Browse our large collection of Wine Caddies and other items now!</p>','[route]=app_category_list'),(5,'page.feed.social','Social','<p>Looking for more news and information?! Join us on Facebook and be sure to like our page!</p>','[name]=Visit our Facebook Page,[link]=https://www.facebook.com/SilverPapillonOfMystic'),(6,'about.address','Our <em>Address</em>','<p>11 W Main St<br />\r\nMystic<br />\r\nConnecticut 06355</p>',NULL),(7,'about.description','About <em>Us</em>','<p>Located in the heart of historic downtown Mystic near the drawbridge. We carry a wide array of jewelry in a variety of prices and styles. We offer settings in larimar, mystic topaz, moonstone, opal and so many other gems. Our popular wine caddies offer something for everyone. Other selections are scarfs, Lyme Logo shirts, handbags, pictures, clocks, glasses&hellip; and so much more! There are many nautical items to choose from as well.</p>',NULL),(8,'about.contact','Contact <em>Us</em>','<p>Have questions, comments, or want to simply send a general inquary? Complete the below form and we will get back you within 48 hours.</p>',NULL);
/*!40000 ALTER TABLE `content_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_order`
--

DROP TABLE IF EXISTS `customer_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
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
-- Table structure for table `hours`
--

DROP TABLE IF EXISTS `hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dow` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weight` int(11) NOT NULL,
  `closed` tinyint(1) DEFAULT NULL,
  `timeOpen` time DEFAULT NULL,
  `timeClose` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hours`
--

LOCK TABLES `hours` WRITE;
/*!40000 ALTER TABLE `hours` DISABLE KEYS */;
INSERT INTO `hours` VALUES (2,'Monday',0,11,'20:00:00','00:00:01'),(3,'Tueaday',2,0,'11:00:00','20:00:00'),(4,'Wednesday',3,0,'11:00:00','20:00:00'),(5,'Thursday',4,0,'11:00:00','20:00:00'),(6,'Friday',5,0,'11:00:00','20:00:00'),(7,'Saturday',6,0,'11:00:00','20:00:00'),(8,'Sunday',7,0,'11:00:00','20:00:00');
/*!40000 ALTER TABLE `hours` ENABLE KEYS */;
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
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `price` double NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,1,'2016-08-14 05:21:43','2016-08-14 05:21:43','19 Holes','658309',1,0,45,NULL,'658309-19-holes.png'),(2,1,'2016-08-14 05:21:43','2016-08-14 05:21:43','5-bottle Wine Basket','642612',0,0,45,NULL,'642612-5-bottle-wine-basket.jpg'),(3,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Admin Assistant','659542',0,0,45,NULL,'659542-admin-assistant.png'),(4,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Airplane Pilot','659832',0,0,45,NULL,'659832-airplane-pilot.png'),(5,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Airplane With Pilot','642476',1,0,69,NULL,'642476-airplane-with-pilot.jpg'),(6,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Angel','641158',1,0,45,NULL,'641158-angel.png'),(7,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Angel With Halo','655810',1,0,45,NULL,'655810-angel-with-halo.png'),(8,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Architect','642346',0,0,45,NULL,'642346-architect.jpg'),(9,1,'2016-08-14 05:21:44','2016-08-14 05:21:44','Artist With Easel','642414',0,0,45,NULL,'642414-artist-with-easel.jpg'),(10,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Baker','642704',0,0,45,NULL,'642704-baker.jpg'),(11,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Barber','641370',0,0,45,NULL,'641370-barber.png'),(12,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Bartender','641172',1,0,47,NULL,'641172-bartender.png'),(13,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Bartender With Glass Tower','642759',0,0,45,NULL,'642759-bartender-with-glass-tower.jpg'),(14,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Bartender With Stools','643022',1,0,49,NULL,'643022-bartender-with-stools.png'),(15,1,'2016-08-14 05:21:45','2016-08-14 05:21:45','Bbq Cook Silver','640816',0,0,45,NULL,'640816-bbq-cook-silver.png'),(16,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Big Rig Tractor','642636',0,0,69,NULL,'642636-big-rig-tractor.jpg'),(17,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Biker','641851',1,0,45,NULL,'641851-biker.png'),(18,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Birthday Girl With Balloon','642285',0,0,45,NULL,'642285-birthday-girl-with-balloon.jpg'),(19,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Boxer','642551',0,0,45,NULL,'642551-boxer.jpg'),(20,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Captain','659351',1,1,45,NULL,'659351-captain.png'),(21,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Captain Painted','642070',1,1,45,NULL,'642070-captain-painted.png'),(22,1,'2016-08-14 05:21:46','2016-08-14 05:21:46','Captain Silver','655780',1,0,45,NULL,'655780-captain-silver.png'),(23,1,'2016-08-14 05:21:47','2016-08-14 05:21:47','Carpenter','642308',0,0,45,NULL,'642308-carpenter.jpg'),(24,1,'2016-08-14 05:21:47','2016-08-14 05:21:47','Cellist','659443',1,0,45,NULL,'659443-cellist.png'),(25,1,'2016-08-14 05:21:47','2016-08-14 05:21:47','Chef On The Go','642698',0,0,45,NULL,'642698-chef-on-the-go.jpg'),(26,1,'2016-08-14 05:21:47','2016-08-14 05:21:47','Chef Silver','655711',0,0,45,NULL,'655711-chef-silver.png'),(27,1,'2016-08-14 05:21:47','2016-08-14 05:21:47','Chef With Dish --038- Fork','642209',1,0,45,NULL,'642209-chef-with-dish---038--fork.jpg'),(28,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Chef With Sign','658484',1,0,45,NULL,'658484-chef-with-sign.png'),(29,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Chef With Whisk Silver','658279',0,0,45,NULL,'658279-chef-with-whisk-silver.png'),(30,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Choir Conductor','659818',0,0,45,NULL,'659818-choir-conductor.png'),(31,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Civil War Soldier','659993',0,0,45,NULL,'659993-civil-war-soldier.png'),(32,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Cocktail Waitress','659597',0,0,45,NULL,'659597-cocktail-waitress.png'),(33,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Contractor','642322',0,0,45,NULL,'642322-contractor.jpg'),(34,1,'2016-08-14 05:21:48','2016-08-14 05:21:48','Cool Cat Musician','659467',1,0,49,NULL,'659467-cool-cat-musician.png'),(35,1,'2016-08-14 05:21:49','2016-08-14 05:21:49','Cool Dog Drummer','641349',1,0,49,NULL,'641349-cool-dog-drummer.png'),(36,1,'2016-08-14 05:21:49','2016-08-14 05:21:49','Cyclist Silver','655766',0,0,45,NULL,'655766-cyclist-silver.png'),(37,1,'2016-08-14 05:21:49','2016-08-14 05:21:49','Cymbal Painted','641493',0,0,45,NULL,'641493-cymbal-painted.png'),(38,1,'2016-08-14 05:21:49','2016-08-14 05:21:49','Deep Sea Fisherman','658507',1,0,45,NULL,'658507-deep-sea-fisherman.png'),(39,1,'2016-08-14 05:21:49','2016-08-14 05:21:49','Dentist','659344',1,0,45,NULL,'659344-dentist.png'),(40,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Dj','642568',0,0,45,NULL,'642568-dj.jpg'),(41,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Doctor','658316',1,0,45,NULL,'658316-doctor.png'),(42,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Doctor Silver','640465',0,0,45,NULL,'640465-doctor-silver.png'),(43,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Drinking Dog','642513',0,0,45,NULL,'642513-drinking-dog.jpg'),(44,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Drummer','659450',1,0,49,NULL,'659450-drummer.png'),(45,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Drummer Painted','641486',1,0,45,NULL,'641486-drummer-painted.png'),(46,1,'2016-08-14 05:21:50','2016-08-14 05:21:50','Fashion Lady','642315',0,0,45,NULL,'642315-fashion-lady.jpg'),(47,1,'2016-08-14 05:21:51','2016-08-14 05:21:51','Female Chef','658538',1,0,45,NULL,'658538-female-chef.png'),(48,1,'2016-08-14 05:21:51','2016-08-14 05:21:51','Female Chef With Sign','641448',1,0,45,NULL,'641448-female-chef-with-sign.png'),(49,1,'2016-08-14 05:21:51','2016-08-14 05:21:51','Female Gardener','659566',1,0,45,NULL,'659566-female-gardener.png'),(50,1,'2016-08-14 05:21:51','2016-08-14 05:21:51','Female Golfer','658439',1,0,45,NULL,'658439-female-golfer.png'),(51,1,'2016-08-14 05:21:51','2016-08-14 05:21:51','Female Guitarist','641189',0,0,45,NULL,'641189-female-guitarist.png'),(52,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Female Nurse','640489',1,0,45,NULL,'640489-female-nurse.png'),(53,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Female Veterinarian','642278',0,0,45,NULL,'642278-female-veterinarian.jpg'),(54,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Fiddler Silver','655865',0,0,45,NULL,'655865-fiddler-silver.png'),(55,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Fireman With Extinguisher','641332',1,0,45,NULL,'641332-fireman-with-extinguisher.png'),(56,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Fisherman','641837',0,0,45,NULL,'641837-fisherman.png'),(57,1,'2016-08-14 05:21:52','2016-08-14 05:21:52','Fisherman On Boat With Dog','642452',0,0,45,NULL,'642452-fisherman-on-boat-with-dog.jpg'),(58,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Fisherman Silver','640861',0,0,45,NULL,'640861-fisherman-silver.png'),(59,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Fisherman With Fish','658330',1,0,45,NULL,'658330-fisherman-with-fish.png'),(60,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Fishing Moose','641622',0,0,45,NULL,'641622-fishing-moose.png'),(61,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Fishing On Barrels','642674',0,0,45,NULL,'642674-fishing-on-barrels.jpg'),(62,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Football Helmet','642520',0,0,45,NULL,'642520-football-helmet.jpg'),(63,1,'2016-08-14 05:21:53','2016-08-14 05:21:53','Football Player','641165',0,0,45,NULL,'641165-football-player.png'),(64,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Football Player Silver','655742',1,0,45,NULL,'655742-football-player-silver.png'),(65,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Golf Caddy','658385',1,0,45,NULL,'658385-golf-caddy.png'),(66,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Golf Cart','641462',0,0,45,NULL,'641462-golf-cart.png'),(67,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Golf Cart With Golfer','642421',0,0,45,NULL,'642421-golf-cart-with-golfer.jpg'),(68,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Golfer','641141',1,1,45,NULL,'641141-golfer.png'),(69,1,'2016-08-14 05:21:54','2016-08-14 05:21:54','Golfer Glass Wine Stopper','611083',0,0,45,NULL,'611083-golfer-glass-wine-stopper.png'),(70,1,'2016-08-14 05:21:55','2016-08-14 05:21:55','Golfer Silver','640038',1,0,45,NULL,'640038-golfer-silver.png'),(71,1,'2016-08-14 05:21:55','2016-08-14 05:21:55','Graduate','641363',1,0,45,NULL,'641363-graduate.png'),(72,1,'2016-08-14 05:21:55','2016-08-14 05:21:55','Grape Harvester','659528',0,0,45,NULL,'659528-grape-harvester.png'),(73,1,'2016-08-14 05:21:55','2016-08-14 05:21:55','Grapevine Bottle Hugger','659559',0,0,45,NULL,'659559-grapevine-bottle-hugger.png'),(74,1,'2016-08-14 05:21:55','2016-08-14 05:21:55','Grill Master','641134',1,0,45,NULL,'641134-grill-master.png'),(75,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','Hairdresser Silver','640892',0,0,45,NULL,'640892-hairdresser-silver.png'),(76,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','Happy Dog Silver','658286',1,0,45,NULL,'658286-happy-dog-silver.png'),(77,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','High Roller','659511',0,0,45,NULL,'659511-high-roller.png'),(78,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','Hiker','642254',0,0,45,NULL,'642254-hiker.jpg'),(79,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','House Painter','642339',0,0,45,NULL,'642339-house-painter.jpg'),(80,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','How Does This Look-','642506',1,0,45,NULL,'642506-how-does-this-look-.jpg'),(81,1,'2016-08-14 05:21:56','2016-08-14 05:21:56','Hunter','658354',0,0,45,NULL,'658354-hunter.png'),(82,1,'2016-08-14 05:21:57','2016-08-14 05:21:57','Hunting Moose','641615',0,0,45,NULL,'641615-hunting-moose.png'),(83,1,'2016-08-14 05:21:57','2016-08-14 05:21:57','Ice Hockey Player','641387',1,0,45,NULL,'641387-ice-hockey-player.png'),(84,1,'2016-08-14 05:21:57','2016-08-14 05:21:57','Iron Chef','658545',1,0,45,NULL,'658545-iron-chef.png'),(85,1,'2016-08-14 05:21:57','2016-08-14 05:21:57','Jazz Drummer Silver','640687',0,0,45,NULL,'640687-jazz-drummer-silver.png'),(86,1,'2016-08-14 05:21:57','2016-08-14 05:21:57','Keyboard Player','659429',0,0,45,NULL,'659429-keyboard-player.png'),(87,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','King','641318',1,0,69,NULL,'641318-king.png'),(88,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','King Painted','641530',0,0,45,NULL,'641530-king-painted.png'),(89,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','Kingsguard','642230',1,0,65,NULL,'642230-kingsguard.jpg'),(90,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','Knight Silver','640076',1,0,65,NULL,'640076-knight-silver.png'),(91,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','Knight With Axe And Sword','642735',0,0,45,NULL,'642735-knight-with-axe-and-sword.jpg'),(92,1,'2016-08-14 05:21:58','2016-08-14 05:21:58','Knight With Battle Axe','642773',0,0,45,NULL,'642773-knight-with-battle-axe.jpg'),(93,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Knight With Sword And Shield','642742',0,0,45,NULL,'642742-knight-with-sword-and-shield.jpg'),(94,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Lady Captain','659481',0,0,45,NULL,'659481-lady-captain.png'),(95,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Lead Guitar','641127',0,0,45,NULL,'641127-lead-guitar.png'),(96,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Lifeguard','642582',0,0,45,NULL,'642582-lifeguard.jpg'),(97,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Lighthouse','659375',1,0,45,NULL,'659375-lighthouse.png'),(98,1,'2016-08-14 05:21:59','2016-08-14 05:21:59','Lighthouse Painted','642087',1,0,45,NULL,'642087-lighthouse-painted.png'),(99,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Lighthouse Silver','655858',0,0,45,NULL,'655858-lighthouse-silver.png'),(100,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Love--038-wine','642544',1,0,45,NULL,'642544-love--038-wine.jpg'),(101,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Male Tennis Player','641882',0,0,45,NULL,'641882-male-tennis-player.png'),(102,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Master Chef With 6 Bottles','642193',0,0,45,NULL,'642193-master-chef-with-6-bottles.jpg'),(103,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Moses','659382',0,0,45,NULL,'659382-moses.png'),(104,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Motorcycle Rider','642018',1,1,69,NULL,'642018-motorcycle-rider.png'),(105,1,'2016-08-14 05:22:00','2016-08-14 05:22:00','Nautical Wheel With Fish Net','642490',1,1,45,NULL,'642490-nautical-wheel-with-fish-net.jpg'),(106,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Baseball Player','642599',0,0,45,NULL,'642599-new-baseball-player.jpg'),(107,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Cowboy','642643',0,0,45,NULL,'642643-new-cowboy.jpg'),(108,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Dentist','642650',0,0,45,NULL,'642650-new-dentist.jpg'),(109,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Female Nurse','642728',0,0,45,NULL,'642728-new-female-nurse.jpg'),(110,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Female Tennis Player','642575',0,0,45,NULL,'642575-new-female-tennis-player.jpg'),(111,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Fireman','642605',0,0,45,NULL,'642605-new-fireman.jpg'),(112,1,'2016-08-14 05:22:01','2016-08-14 05:22:01','New Fisherman','642711',0,0,45,NULL,'642711-new-fisherman.jpg'),(113,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','New Motorcycle Rider','642483',0,0,69,NULL,'642483-new-motorcycle-rider.jpg'),(114,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Pirate','659412',1,1,45,NULL,'659412-pirate.png'),(115,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Policeman','641196',0,0,45,NULL,'641196-policeman.png'),(116,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Policeman Silver','640496',1,1,45,NULL,'640496-policeman-silver.png'),(117,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Postman','642025',0,0,45,NULL,'642025-postman.png'),(118,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Preacher','659054',1,0,40,NULL,'659054-preacher.png'),(119,1,'2016-08-14 05:22:02','2016-08-14 05:22:02','Red Sailboat Wine Stopper','611564',0,0,45,NULL,'611564-red-sailboat-wine-stopper.png'),(120,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Rock Star','658347',1,1,45,NULL,'658347-rock-star.png'),(121,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Sailboat','642469',1,1,69,NULL,'642469-sailboat.jpg'),(122,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Santa Glass Wine Stopper','611069',0,0,45,NULL,'611069-santa-glass-wine-stopper.png'),(123,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Shell Wine Stopper','611533',0,0,45,NULL,'611533-shell-wine-stopper.png'),(124,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Shopper','659498',1,1,45,NULL,'659498-shopper.png'),(125,1,'2016-08-14 05:22:03','2016-08-14 05:22:03','Short Order Cook','642681',0,0,45,NULL,'642681-short-order-cook.jpg'),(126,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Skier','642445',0,0,45,NULL,'642445-skier.jpg'),(127,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Skiing Moose','659290',1,1,45,NULL,'659290-skiing-moose.png'),(128,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Snowman Glass Wine Stopper','611090',0,0,45,NULL,'611090-snowman-glass-wine-stopper.png'),(129,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Sommelier','658491',1,1,45,NULL,'658491-sommelier.png'),(130,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Sport Motorcycle Rider','642179',0,1,45,NULL,'642179-sport-motorcycle-rider.jpg'),(131,1,'2016-08-14 05:22:04','2016-08-14 05:22:04','Surfer','641868',1,1,45,NULL,'641868-surfer.png'),(132,1,'2016-08-14 05:22:05','2016-08-14 05:22:05','Surgeon','642261',0,0,45,NULL,'642261-surgeon.jpg'),(133,1,'2016-08-14 05:22:05','2016-08-14 05:22:05','Tailgater','641899',1,1,45,NULL,'641899-tailgater.png'),(134,1,'2016-08-14 05:22:05','2016-08-14 05:22:05','Teacher','642247',1,1,40,NULL,'642247-teacher.jpg'),(135,1,'2016-08-14 05:22:05','2016-08-14 05:22:05','The Boss','641844',0,0,45,NULL,'641844-the-boss.png'),(136,1,'2016-08-14 05:22:05','2016-08-14 05:22:05','Tourist','641325',0,0,45,NULL,'641325-tourist.png'),(137,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Tractor Driver','642155',1,1,79,NULL,'642155-tractor-driver.jpg'),(138,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Traveler','641110',0,0,45,NULL,'641110-traveler.png'),(139,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Truck Driver','642186',1,1,89,NULL,'642186-truck-driver.jpg'),(140,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Vacationing Fisherman','642223',0,0,45,NULL,'642223-vacationing-fisherman.jpg'),(141,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Veterinarian','641394',1,1,45,NULL,'641394-veterinarian.png'),(142,1,'2016-08-14 05:22:06','2016-08-14 05:22:06','Vineyard Worker','642629',0,0,45,NULL,'642629-vineyard-worker.jpg'),(143,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Vintage Car','642438',1,1,69,NULL,'642438-vintage-car.jpg'),(144,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Violinist','659436',1,1,45,NULL,'659436-violinist.png'),(145,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Waiter','658323',0,0,45,NULL,'658323-waiter.png'),(146,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Waiter Painted','642100',0,0,45,NULL,'642100-waiter-painted.png'),(147,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Waiter Silver','640809',0,0,45,NULL,'640809-waiter-silver.png'),(148,1,'2016-08-14 05:22:07','2016-08-14 05:22:07','Waiter Wine Server','642216',1,1,45,NULL,'642216-waiter-wine-server.jpg'),(149,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Wedding Couple','642537',1,0,69,NULL,'642537-wedding-couple.jpg'),(150,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Wedding Day Kiss','642766',0,0,45,NULL,'642766-wedding-day-kiss.jpg'),(151,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Welcome','659580',0,0,45,NULL,'659580-welcome.png'),(152,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Wine Lover','642162',1,0,45,NULL,'642162-wine-lover.jpg'),(153,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Wine Server','641455',0,0,45,NULL,'641455-wine-server.png'),(154,1,'2016-08-14 05:22:08','2016-08-14 05:22:08','Wine Server Painted','642094',0,0,45,NULL,'642094-wine-server-painted.png'),(155,1,'2016-08-14 05:22:09','2016-08-14 05:22:09','Wine Taster','643039',0,0,45,NULL,'643039-wine-taster.png'),(156,1,'2016-08-14 05:22:09','2016-08-14 05:22:09','Young Captain','641424',0,0,45,NULL,'641424-young-captain.png');
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
  `name` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_category`
--

LOCK TABLES `product_category` WRITE;
/*!40000 ALTER TABLE `product_category` DISABLE KEYS */;
INSERT INTO `product_category` VALUES (1,1,'Wine Caddies');
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
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
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
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','admin','rmf@src.run','rmf@src.run',1,'eet93zwb6t4wscogs0k4s88cwcs8gok','$2y$13$h6cjtma1uIQeAHpO7D8QQ.EmsY3KElMi1uHl8Vw8WJl1NWwwcOp3u','2016-08-14 03:11:20',0,0,NULL,NULL,NULL,'a:1:{i:0;s:10:\"ROLE_ADMIN\";}',0,NULL);
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

-- Dump completed on 2016-08-14  5:23:26
