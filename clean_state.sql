CREATE TABLE IF NOT EXISTS `list_admin_status` (
  `admin_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_status` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `list_admin_status` (`admin_status_id`, `admin_status`) VALUES
	(1, 'Aktif'),
	(2, 'Tidak Aktif'),
	(3, 'Banned');

CREATE TABLE IF NOT EXISTS `list_division` (
  `division_id` int(11) NOT NULL AUTO_INCREMENT,
  `division_name` varchar(50) NOT NULL,
  PRIMARY KEY (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `list_division` (`division_id`, `division_name`) VALUES
	(1, 'System Administrator');


-- Dumping structure for table template_lte.list_access_control
CREATE TABLE IF NOT EXISTS `list_access_control` (
  `admin_tier_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` int(11) NOT NULL,
  `access_divisionId` int(11) NOT NULL,
  `access_levelName` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_tier_id`),
  KEY `FK__list_division` (`access_divisionId`),
  CONSTRAINT `FK__list_division` FOREIGN KEY (`access_divisionId`) REFERENCES `list_division` (`division_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table template_lte.list_access_control: ~0 rows (approximately)
/*!40000 ALTER TABLE `list_access_control` DISABLE KEYS */;
INSERT INTO `list_access_control` (`admin_tier_id`, `access_level`, `access_divisionId`, `access_levelName`) VALUES
	(1, 100, 1, 'Super Admin');
/*!40000 ALTER TABLE `list_access_control` ENABLE KEYS */;

-- Dumping structure for table template_lte.list_admin
CREATE TABLE IF NOT EXISTS `list_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(125) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_email` varchar(80) NOT NULL,
  `admin_phone` varchar(20) NOT NULL,
  `admin_lastLogin` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `admin_statusId` int(11) NOT NULL,
  `admin_tierId` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `FK_list_admin_list_admin_status` (`admin_statusId`),
  KEY `FK_list_admin_list_access_control` (`admin_tierId`),
  CONSTRAINT `FK_list_admin_list_access_control` FOREIGN KEY (`admin_tierId`) REFERENCES `list_access_control` (`admin_tier_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_list_admin_list_admin_status` FOREIGN KEY (`admin_statusId`) REFERENCES `list_admin_status` (`admin_status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table template_lte.list_admin: ~1 rows (approximately)
/*!40000 ALTER TABLE `list_admin` DISABLE KEYS */;
INSERT INTO `list_admin` (`admin_id`, `admin_name`, `admin_password`, `admin_email`, `admin_phone`, `admin_lastLogin`, `created_date`, `updated_date`, `admin_statusId`, `admin_tierId`) VALUES
	(1, 'Sanjaya', '$2y$10$QEo45sr7r.Cus/cepy9WgOIuCbRwMYkdvcATGoP9xsN2EHXYUA2EK', 'rickysanjaya411@gmail.com', '08', NULL, NULL, NULL, 1, 1);

-- Dumping structure for table template_lte.list_session_token
CREATE TABLE IF NOT EXISTS `list_session_token` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_token` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `active_time` datetime NOT NULL,
  `expire_time` datetime NOT NULL,
  `is_login` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`session_id`),
  KEY `FK__list_admin` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Added date 9 Maret 2021
CREATE TABLE IF NOT EXISTS `list_action` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(100) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `list_action` (`action_id`, `action`) VALUES
	(1, 'Login'),
	(2, 'Logout'),
	(3, 'Add Admin'),
	(4, 'Edit Admin');

CREATE TABLE IF NOT EXISTS `list_activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `platform` text DEFAULT NULL,
  `browser` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `meta_data` mediumtext DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `FK_list_activity_list_action` (`action_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `FK_list_activity_list_action` FOREIGN KEY (`action_id`) REFERENCES `list_action` (`action_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `list_admin`
	ADD COLUMN `admin_photo` TEXT NULL DEFAULT NULL AFTER `admin_tierId`;

-- Added date 6 April 2021
CREATE TABLE IF NOT EXISTS `list_bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `bank_code` varchar(20) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `holder_name` varchar(50) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Added date 11 April 2021
CREATE TABLE IF NOT EXISTS `list_customer_status` (
  `customer_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_status` varchar(50) NOT NULL,
  PRIMARY KEY (`customer_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `list_customer_status` (`customer_status_id`, `customer_status`) VALUES
	(1, 'Active'),
	(2, 'Discontinued'),
	(3, 'Banned');

CREATE TABLE IF NOT EXISTS `list_customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_password` varchar(100) NOT NULL,
  `customer_address` text NOT NULL,
  `province_id` int(11) NOT NULL,
  `province_name` varchar(150) NOT NULL,
  `district_id` int(11) NOT NULL,
  `district_name` varchar(150) NOT NULL,
  `customer_photo` text DEFAULT NULL,
  `customer_status_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`),
  KEY `FK_list_customer_list_customer_status` (`customer_status_id`),
  CONSTRAINT `FK_list_customer_list_customer_status` FOREIGN KEY (`customer_status_id`) REFERENCES `list_customer_status` (`customer_status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;