-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: mysqlsvr41.world4you.com
-- Erstellungszeit: 17. Dez 2017 um 22:05
-- Server-Version: 5.5.57
-- PHP-Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `7368952db4`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Measuring_Attributes`
--

CREATE TABLE `Measuring_Attributes` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Id ',
  `patterns` int(11) NOT NULL,
  `dont_know` int(11) NOT NULL,
  `priming` int(11) NOT NULL,
  `conflict` int(11) NOT NULL,
  `anchoring` int(11) NOT NULL,
  `straight` int(11) NOT NULL,
  `speeding` int(11) NOT NULL,
  `incomplete` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Quality_Weights`
--

CREATE TABLE `Quality_Weights` (
  `id` bigint(40) NOT NULL,
  `pattern` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `dont` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `priming` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `conflict` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `anchoring` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `straight` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `speeding` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `complete` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `survey_id` bigint(40) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Responses`
--

CREATE TABLE `Responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quality_score` int(3) DEFAULT NULL,
  `evaluated` tinyint(1) NOT NULL DEFAULT '1',
  `survey_id` bigint(40) NOT NULL COMMENT 'Response connected to survey',
  `respond_speeding` bigint(40) NOT NULL COMMENT 'Calculates the speeding time per respond '
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Response_Data`
--

CREATE TABLE `Response_Data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_text` varchar(100) DEFAULT NULL,
  `answer` varchar(60) NOT NULL,
  `response_time` int(11) NOT NULL,
  `response_id` bigint(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Response_Measuring`
--

CREATE TABLE `Response_Measuring` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `response_id` bigint(20) NOT NULL,
  `measuring_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Surveys`
--

CREATE TABLE `Surveys` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID for the online survey',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Connects the user to a survey',
  `name` varchar(30) NOT NULL COMMENT 'Name of the survey',
  `topic` varchar(30) NOT NULL COMMENT 'Name of the topic of the survey',
  `quality_score` int(11) UNSIGNED DEFAULT NULL COMMENT 'Quality Overall quality score of the survey',
  `measuring_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Foreign key for the attributes',
  `survey_link` varchar(40) DEFAULT NULL COMMENT 'Link to the existing survey'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID from user',
  `pwd` varchar(1000) NOT NULL COMMENT 'pwd from user',
  `mail` varchar(40) NOT NULL COMMENT 'mail from user is unique',
  `activated` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if the user is activated he has access to the survey analyses '
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='User table for the survey analyses ';

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Measuring_Attributes`
--
ALTER TABLE `Measuring_Attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Quality_Weights`
--
ALTER TABLE `Quality_Weights`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Responses`
--
ALTER TABLE `Responses`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Response_Data`
--
ALTER TABLE `Response_Data`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Response_Measuring`
--
ALTER TABLE `Response_Measuring`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Surveys`
--
ALTER TABLE `Surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Measuring_Attributes`
--
ALTER TABLE `Measuring_Attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id ', AUTO_INCREMENT=1299;

--
-- AUTO_INCREMENT für Tabelle `Quality_Weights`
--
ALTER TABLE `Quality_Weights`
  MODIFY `id` bigint(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT für Tabelle `Responses`
--
ALTER TABLE `Responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1313;

--
-- AUTO_INCREMENT für Tabelle `Response_Data`
--
ALTER TABLE `Response_Data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27037;

--
-- AUTO_INCREMENT für Tabelle `Response_Measuring`
--
ALTER TABLE `Response_Measuring`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1215;

--
-- AUTO_INCREMENT für Tabelle `Surveys`
--
ALTER TABLE `Surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID for the online survey', AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID from user', AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
