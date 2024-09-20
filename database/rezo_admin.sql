-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2017 at 11:20 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rezo_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails_history`
--

CREATE TABLE IF NOT EXISTS `emails_history` (
`id` int(10) unsigned NOT NULL,
  `from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
`id` int(10) unsigned NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_12_100000_create_settings_table', 1),
(4, '2016_09_27_053133_seed_gateway_settings', 1),
(5, '2016_09_30_172219_seed_validations_to_settings_table', 1),
(6, '2016_12_12_092055_seed_mail_settings', 1),
(7, '2016_12_15_041604_seed_mail_validations', 1),
(8, '2016_12_15_054358_rename_mailgun_domain_and_mailgun_key_fields', 1),
(9, '2017_10_20_183958_create_emails_history_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(10) unsigned NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci,
  `validation_rules` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `view_name`, `title`, `description`, `config`, `validation_rules`, `created_at`, `updated_at`) VALUES
(1, 'general', 'settings.general', 'General Settings', NULL, '{"site_name":"","default_email":"","site_url":"","additional_emails":[],"in_advance_rental_days":1,"reservation_fee":10,"discount":0}', '{"config.site_name":"required","config.site_url":"required","config.default_email":"required|email","config.reservation_fee":"required|numeric|min:0","config.discount":"required|numeric|min:0","config.in_advance_rental_days":"required|numeric|min:0","config.season_start":"required|date_format:m\\/d\\/Y","config.season_end":"required|date_format:m\\/d\\/Y"}', NULL, NULL),
(2, 'limitation', 'settings.limitation', 'Limitation Settings', NULL, '{"age":"14","height":"5'' 5''''","weight":"150 lbs","shoe_size":7.5}', '{"config.age":"required|numeric","config.height":"required","config.weight":"required","config.shoe_size":"required"}', NULL, NULL),
(3, 'mail', 'settings.mail', 'Mail Settings', NULL, '{"host":"","port":"","username":"","password":"","from":{"name":"","address":""},"driver":"smtp","domain":"","secret":""}', '{"config.host":"required_if:config.driver,smtp","config.port":"required_if:config.driver,smtp|numeric","config.username":"required_if:config.driver,smtp","config.password":"required_if:config.driver,smtp","config.from.name":"required","config.from.address":"required","config.driver":"required","config.domain":"required_if:config.driver,mailgun","config.secret":"required_if:config.driver,mailgun"}', NULL, NULL),
(4, 'gateway', 'settings.gateway', 'Gateway Settings', NULL, '{"is_live":0,"login_id":"","transaction_key":""}', '{"config.is_live":"required|in:0,1","config.login_id":"required","config.transaction_key":"required"}', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `type`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Marc Harrell', 'marc@rezosystems.com', '$2y$10$dDf51rt1.3jWkDJcK8x.K.mXb.2LD1VsIYP9aDrBYpEyJ5OieBbpK', 'admin', NULL, NULL, NULL),
(2, 'Alvis Bogisich', 'ndeckow@hotmail.com', '$2y$10$J2oZinz7kGX1NqsjvlK4Guisw20AUkOj7AZja9lmMWlSID03VrzR2', 'admin', NULL, NULL, NULL),
(3, 'Jazlyn Kshlerin', 'zack.hammes@gmail.com', '$2y$10$ZN1qerM2X.//U5uZn.ysxu5auWUUT4vM9yyTGIso3.jf8e3LtjB72', 'admin', NULL, NULL, NULL),
(4, 'Madonna Vandervort', 'efrain.davis@hotmail.com', '$2y$10$CWTi/q2lpN2YIYk5Tk20sudY.5ibpEpXcjlmgvJZLbmM3uJpvSV.K', 'admin', NULL, NULL, NULL),
(5, 'Precious Waelchi', 'rose.raynor@yahoo.com', '$2y$10$axzNsbyPeL62tvJfjAUD.Orfm6Rc4znN.qsCA9C3Y5prQwp9JapIu', 'admin', NULL, NULL, NULL),
(6, 'Ariel Mueller', 'cbartoletti@legros.info', '$2y$10$CZhNNRbP4yjH.iadeVBzA.nowN.3GJzhMC2W1nxAdYVnSz/RO.XK.', 'admin', NULL, NULL, NULL),
(7, 'Geovanny Morar', 'schiller.shayna@yahoo.com', '$2y$10$qCGhm2odQ/r570547obDpOy6xbpp5r37pDUtoFq9PeAQEPvjhR0je', 'user', NULL, NULL, NULL),
(8, 'Moshe Kreiger', 'johns.maci@haag.info', '$2y$10$N.qayR3LZwMz/fF8/qorheKQ3TIOoxhSa7I4cZV9sfSWIBbFXTiqa', 'user', NULL, NULL, NULL),
(9, 'Vickie Zieme IV', 'kautzer.kitty@gmail.com', '$2y$10$0noLKXD7KAA2HpFbrzhL1OBcH2mpNmw1gp6lyf0WeAssgTZL8cZZG', 'user', NULL, NULL, NULL),
(10, 'Jamison Doyle III', 'rwisoky@gmail.com', '$2y$10$Syk3.SuRe8xKMqZVY.DzR.2Io9/nDOWB38PfwJiBtfiljy0B5P1Ku', 'user', NULL, NULL, NULL),
(11, 'Cornell Mante', 'sierra.heidenreich@bradtke.net', '$2y$10$A.0JUTninnidTge9ID7T1OLDwMYHpqHpOuzz8Y.bXpb90o8AbegQy', 'user', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emails_history`
--
ALTER TABLE `emails_history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
 ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emails_history`
--
ALTER TABLE `emails_history`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
