SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `Codeine`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Page`
--

DROP TABLE IF EXISTS `Page`;
CREATE TABLE IF NOT EXISTS `Page` (
  `ID` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `K` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `V` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `ID` (`ID`),
  KEY `Key` (`K`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Page`
--

INSERT INTO `Page` (`ID`, `K`, `V`) VALUES
('About', 'Title', 'Codeine работает.'),
('About', 'Text', 'И демонстрирует нам объект из Хранилища.');
