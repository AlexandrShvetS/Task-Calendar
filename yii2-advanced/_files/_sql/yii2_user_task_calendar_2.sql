-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 13 2020 г., 21:33
-- Версия сервера: 5.5.53
-- Версия PHP: 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii2_user_task_calendar_2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1605205033),
('m130524_201442_init', 1605205040),
('m190124_110200_add_verification_token_column_to_user_table', 1605205041);

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `date_end` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `task`
--

INSERT INTO `task` (`id`, `name`, `info`, `date_end`, `user_id`) VALUES
(1, 'Покупки', 'Хлеб\r\nСок\r\nСигареты\r\nЭнергетик', '2020-11-19', 1),
(2, 'Запчасти', 'Колодки\r\nФильтр салона\r\nМасло ДВС', '2020-11-15', 7),
(4, 'Продукты', 'Банан\r\nАпельсин', '2020-11-14', 8),
(5, 'Коту', 'Корм Роял Канин Уринар', '2020-11-18', 1),
(6, 'Предохранители', '5А\r\n25А\r\n40А', '2020-11-15', 7),
(7, 'Давление в системе', '2 Бара', '2020-11-23', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'admin', '9e7vDMF5By8ujmh5TPSwUDRgJfO5xAND', '$2y$13$BbXDebFYVoYg3FaQeov2zeIHFMpGoKa/FxUBmmJf4SyPY4oduiAV2', NULL, 'k33Lrji-DH4TKihFPmPQ', 'alexandr.shvets96@gmail.com', 10, 1605212179, 1605212179, 'ba9jrswlqnukEoaaD8luIt30rYw6TU-B_1605212179'),
(7, 'Dmitriy', 'gJinYeDkuNMy4z0D-56RvOAINdNO3qkY', '$2y$13$E6fx/MyKZUHdQgDgH.4seeYo.0/jTxllnPbbXuvw10N7nfArS6jk.', NULL, NULL, 'dmitriy@gmail.com', 10, 1605240553, 1605240553, 'SkVk1Choj4pR46qF-mOPtPpJHN80hDz__1605240553'),
(8, 'Egor', 'lmDzi1LHoRDwYgstUInyWzSKbZo3pLzP', '$2y$13$/UAwbPdGQqZ9atBQmRDXJu0vUtRpKahJxuO246kyjkBeBSxoy20X2', NULL, '', 'egor@gmail.com', 10, 1605240990, 1605246816, 'CI35wzXZy1ArszSDM1qF3uHgtpKyV8hh_1605240990'),
(9, 'Oleg', '9nFAZj6G1dJlYdLX71AidVhFixuAy4U1', '$2y$13$h3aLoyQlsGzFSlcrcii8uOouVeq8ChHzNY8fnyvaBvVF425aIFu1W', NULL, NULL, 'olegr@gmail.com', 10, 0, 0, '_HD4dWW2IV4-AROKmhRC7uTnjh2uSJZ0_1605291427');

-- --------------------------------------------------------

--
-- Структура таблицы `yii_config`
--

CREATE TABLE `yii_config` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `yii_config`
--

INSERT INTO `yii_config` (`id`, `name`, `value`) VALUES
(1, 'active_token', '1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Индексы таблицы `yii_config`
--
ALTER TABLE `yii_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `yii_config`
--
ALTER TABLE `yii_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
