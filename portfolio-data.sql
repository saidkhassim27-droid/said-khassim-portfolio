/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: said_khassim_portfolio
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES
(1,'Said Khassim','ICT Professional & Digital Services','ICT level 6 Diploma student interested in Web Development, Software Development, Database Management and Digital Solutions.','uploads/55e3d5d05891825344175a9b5e668102.png','saidkhassim27@gmail.com','','Kenya');
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES
(1,'Software Development','Programming','Basic programming, Problem Solving and Developing Computer-Based Solutions'),
(2,'Web Development','Web Technologies','HTML, CSS, JavaScript and PHP for Creating and Developing Websites.'),
(3,'Database Management','Database Systems','MySQL and Microsoft Access, Database Creation, Tables, Queries and Relationships'),
(4,'Computer Hardware','Hardware Support','Computer Assembly, Hardware Maintenance, Troubleshooting and Software Installation.'),
(5,'Computer Networking','Networking','Basic Network Configuration, Network Cabling, Network Devices and Troubleshooting.'),
(6,'Professional Skills','Personal and Workplace Skills','Communication, Teamwork, Leadership, Organization and Problem Solving.');
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `education`
--

LOCK TABLES `education` WRITE;
/*!40000 ALTER TABLE `education` DISABLE KEYS */;
INSERT INTO `education` VALUES
(1,'Diploma in ICT.','Lungalunga Technical and Vocational College.','2025-09-02',NULL,'Currently Pursuing','Studying ICT level 6 under the CDACC Curriculum.');
/*!40000 ALTER TABLE `education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `experience`
--

LOCK TABLES `experience` WRITE;
/*!40000 ALTER TABLE `experience` DISABLE KEYS */;
INSERT INTO `experience` VALUES
(1,'College Student Council President','Lungalunga Technical and Vocational College','2026-02-24',NULL,'Student Leadership, Communication, Teamwork, Coordination of Student Activities and Representation of Students.');
/*!40000 ALTER TABLE `experience` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,'Software Development.','Development of Computer-Based Solutions, Programming and Software Applications to Address Practical Problems.'),
(2,'Web Development.','Designing and Developing Responsive Websites using HTML, CSS, JavaScript and PHP.'),
(3,'Database Management.','Creating, Organizing and Managing Databases Using MySQL and Microsoft Access, Including Tables, Queries and Relationships.'),
(4,'Networking.','Basic Computer Network Setup, Network Cabling, Configuration of Network Devices and Troubleshooting.'),
(5,'IT Support.','Computer Installation, Software Setup, Maintenance, Troubleshooting and Technical Support.'),
(6,'Digital Design.','Creating Simple Digital Graphics, Posters, Presentations and Professional Visual Materials.'),
(7,'Technology Writing.','Preparing ICT Documents, Technical Reports, User Guides and Clear Technology-Related Content.'),
(8,'Digital Marketing.','Supporting Online Promotion Through Digital Content, Social Media Communication and Basic Marketing Strategies.');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES
(1,'Library Management System','Developing an Academic Library Management System to Organize Books, Members and Borrowing Records.','PHP, MySQL, HTML, CSS',NULL,'In Progress'),
(2,'Healthcare Management System.','Planning a System For Managing Patient Information and Healthcare Records.','PHP, MySQL, System Design',NULL,'Planning'),
(3,'Hostel Management System.','Designing a Hostel Management System and its Database Structure.','Database Design, System Architecture',NULL,'Planning'),
(4,'Computer Hardware Practical.','Academic Practical Work Involving Computer Components, Hardware Maintenance and Troubleshooting.','Computer Hardware, Troubleshooting',NULL,'Academic Project');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES
(1,'College Student Council President Certificate.','Lungalunga Technical and Vocational College.','2026-06-18','');
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES
(1,'site_title','SAID KHASSIM - ICT PROFESSIONAL'),
(2,'site_description','ICT Professional & Digital Services | Web Development | Database Management | IT Support | Computer Networking'),
(3,'contact_email',''),
(4,'contact_phone',''),
(5,'site_location',''),
(6,'whatsapp',''),
(7,'linkedin',''),
(8,'github','');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-25  8:51:17
