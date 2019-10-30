-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 30 2019 г., 22:28
-- Версия сервера: 5.7.23
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cip`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `text`) VALUES
(1, 1, 'текст ответа 11'),
(2, 1, 'текст ответа 12'),
(3, 1, 'текст ответа 13'),
(4, 2, 'текст ответа 21'),
(5, 2, 'текст ответа 22'),
(6, 2, 'текст ответа 23'),
(9, 4, 'ответ11'),
(10, 4, 'ответ12'),
(11, 5, 'ответ21'),
(12, 5, 'ответ22'),
(13, 6, 'ответ 11'),
(14, 6, 'ответ 12'),
(15, 7, 'ответ 21'),
(16, 7, 'ответ 22'),
(17, 7, 'ответ 23'),
(18, 7, 'ответ 24'),
(19, 8, 'ответ 31'),
(20, 8, 'ответ 32'),
(21, 8, 'ответ 33'),
(22, 9, 'ответ 41'),
(23, 9, 'ответ 42'),
(24, 9, 'ответ 43'),
(25, 10, 'Коронованый герцог графства 1'),
(26, 10, 'Коронованый герцог графства 2'),
(27, 11, 'Консультация с широким активом 1'),
(28, 11, 'Консультация с широким активом 2'),
(29, 11, 'Консультация с широким активом 3'),
(30, 11, 'Консультация с широким активом 4'),
(31, 11, 'Консультация с широким активом 5'),
(32, 11, 'Консультация с широким активом 6'),
(33, 12, 'Сейчас всё чаще звучит 1'),
(34, 12, 'Сейчас всё чаще звучит 2'),
(35, 12, 'Сейчас всё чаще звучит 3');

-- --------------------------------------------------------

--
-- Структура таблицы `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190525220301', '2019-05-25 22:03:21'),
('20190526194841', '2019-05-26 19:49:42'),
('20190526213138', '2019-05-26 21:31:49'),
('20190526213739', '2019-05-26 21:37:48'),
('20190526214437', '2019-05-26 21:44:43'),
('20190529191838', '2019-05-29 19:18:55');

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `question`
--

INSERT INTO `question` (`id`, `survey_id`, `text`, `type`, `required`) VALUES
(1, 1, 'текст вопроса 1', 'radio', 0),
(2, 1, 'текст вопроса 2', 'checkbox', 1),
(4, 3, 'вопроссс1', 'checkbox', 0),
(5, 3, 'вопроссс2', 'radio', 1),
(6, 4, 'текст 1 вопроса', 'checkbox', 1),
(7, 4, 'текст 2 вопроса', 'radio', 0),
(8, 4, 'текст 3 вопроса', 'checkbox', 0),
(9, 4, 'текст 4 вопроса', 'radio', 1),
(10, 5, 'Коронованый герцог графства стал нашим флагом в борьбе с ложью', 'checkbox', 0),
(11, 5, 'Консультация с широким активом оказалась чрезвычайно полезной', 'checkbox', 1),
(12, 5, 'Сейчас всё чаще звучит ласковый перезвон вертикали власти', 'radio', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `survey`
--

CREATE TABLE `survey` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `survey`
--

INSERT INTO `survey` (`id`, `title`, `status`) VALUES
(1, 'Первый Опрос1', 'closed'),
(3, 'Опросище', 'draft'),
(4, 'Главный опрос', 'closed'),
(5, 'Коронованый герцог', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `survey_id`) VALUES
(1, 4),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(7, 4),
(38, 4),
(8, 5),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(14, 5),
(15, 5),
(16, 5),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(28, 5),
(35, 5),
(36, 5),
(37, 5),
(39, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `user_answer`
--

CREATE TABLE `user_answer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user_answer`
--

INSERT INTO `user_answer` (`id`, `user_id`, `answer_id`) VALUES
(1, 1, 14),
(2, 1, 18),
(3, 1, 20),
(4, 1, 21),
(5, 1, 24),
(6, 2, 13),
(7, 2, 14),
(8, 2, 15),
(9, 2, 19),
(10, 2, 20),
(11, 2, 22),
(12, 3, 13),
(13, 3, 14),
(14, 3, 16),
(15, 3, 19),
(16, 3, 20),
(17, 3, 23),
(18, 4, 13),
(19, 5, 14),
(20, 5, 18),
(21, 5, 21),
(22, 5, 23),
(23, 6, 13),
(24, 6, 14),
(25, 6, 17),
(26, 6, 20),
(27, 6, 21),
(28, 6, 23),
(29, 7, 14),
(30, 7, 17),
(31, 7, 20),
(32, 7, 21),
(33, 7, 23),
(34, 8, 26),
(35, 8, 29),
(36, 8, 30),
(37, 8, 32),
(38, 8, 34),
(39, 9, 26),
(40, 9, 29),
(41, 9, 30),
(42, 9, 32),
(43, 9, 34),
(44, 10, 26),
(45, 10, 31),
(46, 10, 35),
(47, 11, 26),
(48, 11, 32),
(49, 11, 34),
(50, 12, 25),
(51, 12, 31),
(52, 12, 32),
(53, 12, 34),
(54, 13, 26),
(55, 13, 28),
(56, 13, 29),
(57, 13, 30),
(58, 13, 32),
(59, 13, 34),
(60, 14, 26),
(61, 14, 31),
(62, 14, 32),
(63, 14, 35),
(64, 15, 31),
(65, 15, 32),
(66, 15, 35),
(67, 16, 26),
(68, 16, 31),
(69, 16, 32),
(70, 16, 34),
(71, 17, 26),
(72, 17, 27),
(73, 17, 33),
(74, 18, 26),
(75, 18, 27),
(76, 18, 33),
(77, 19, 26),
(78, 19, 31),
(79, 19, 32),
(80, 19, 34),
(81, 20, 26),
(82, 20, 31),
(83, 20, 32),
(84, 20, 35),
(85, 21, 26),
(86, 21, 32),
(87, 21, 34),
(88, 22, 26),
(89, 22, 31),
(90, 22, 32),
(91, 22, 35),
(92, 23, 26),
(93, 23, 31),
(94, 23, 32),
(95, 23, 35),
(96, 24, 26),
(97, 24, 31),
(98, 24, 35),
(99, 25, 26),
(100, 25, 32),
(101, 25, 34),
(104, 28, 26),
(105, 28, 28),
(106, 28, 34),
(115, 35, 25),
(116, 35, 26),
(117, 35, 29),
(118, 35, 35),
(119, 36, 25),
(120, 36, 28),
(121, 36, 29),
(122, 36, 31),
(123, 36, 35),
(124, 37, 26),
(125, 37, 28),
(126, 37, 33),
(127, 38, 14),
(128, 38, 18),
(129, 38, 21),
(130, 38, 23),
(131, 39, 30),
(132, 39, 31),
(133, 39, 32);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DADD4A251E27F6BF` (`question_id`);

--
-- Индексы таблицы `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6F7494EB3FE509D` (`survey_id`);

--
-- Индексы таблицы `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D649B3FE509D` (`survey_id`);

--
-- Индексы таблицы `user_answer`
--
ALTER TABLE `user_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BF8F5118A76ED395` (`user_id`),
  ADD KEY `IDX_BF8F5118AA334807` (`answer_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `user_answer`
--
ALTER TABLE `user_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A251E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Ограничения внешнего ключа таблицы `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_B6F7494EB3FE509D` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649B3FE509D` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_answer`
--
ALTER TABLE `user_answer`
  ADD CONSTRAINT `FK_BF8F5118A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BF8F5118AA334807` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
