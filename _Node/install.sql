-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 30 2010 г., 22:36
-- Версия сервера: 5.1.41
-- Версия PHP: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `_Access`
--

CREATE TABLE IF NOT EXISTS `_Access` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Access`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_Application`
--

CREATE TABLE IF NOT EXISTS `_Application` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Application`
--

INSERT INTO `_Application` (`I`, `K`, `V`) VALUES
('Default', 'Plugin:System:Shared', 'Show'),
('Gate', 'Plugin:System:Own', 'Step1'),
('Gate', 'Plugin:System:Own', 'Step2'),
('Gate', 'Plugin:System:Own', 'Step3'),
('Default', 'Plugin:System:Shared', 'Create'),
('Default', 'Plugin:System:Shared', 'Update'),
('_User', 'Plugin:System:Own', 'Cabinet'),
('Gate', 'Plugin:System:Own', 'Exit'),
('Default', 'Plugin:System:Shared', 'List'),
('Default', 'Plugin:System:Shared', 'Catalog'),
('_User', 'Plugin:System:Own', 'Create'),
('Default', 'Plugin:System:Shared', 'Delete'),
('Default', 'Plugin:System:Shared', 'Search'),
('Default', 'Tune:PageSize', '50'),
('Page', 'Status', 'Installed'),
('Node', 'Plugin:System:Own', 'Status'),
('_Language', 'Plugin:System:Own', 'Convert'),
('_Language', 'Plugin:System:Own', 'Submit');

-- --------------------------------------------------------

--
-- Структура таблицы `_IP2Geo`
--

CREATE TABLE IF NOT EXISTS `_IP2Geo` (
  `I` bigint(20) NOT NULL,
  `Town` text COLLATE utf8_unicode_ci NOT NULL,
  `Region` text COLLATE utf8_unicode_ci NOT NULL,
  `Country` text COLLATE utf8_unicode_ci NOT NULL,
  `Latitude` float NOT NULL,
  `Longitude` float NOT NULL,
  `Timezone` tinyint(4) NOT NULL,
  `DSTOffset` tinyint(4) NOT NULL,
  `GMTOffset` tinyint(4) NOT NULL,
  KEY `IP` (`I`),
  KEY `Timezone` (`Timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_IP2Geo`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_Language`
--

CREATE TABLE IF NOT EXISTS `_Language` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Language`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_Signal`
--

CREATE TABLE IF NOT EXISTS `_Signal` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Signal`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_Slot`
--

CREATE TABLE IF NOT EXISTS `_Slot` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Slot`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_Ticket`
--

CREATE TABLE IF NOT EXISTS `_Ticket` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_Ticket`
--


-- --------------------------------------------------------

--
-- Структура таблицы `_User`
--

CREATE TABLE IF NOT EXISTS `_User` (
  `I` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `V` longtext COLLATE utf8_unicode_ci NOT NULL,
  KEY `I` (`I`),
  KEY `K` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `_User`
--

