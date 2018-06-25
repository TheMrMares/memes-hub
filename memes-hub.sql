-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 25 Cze 2018, 04:17
-- Wersja serwera: 10.1.32-MariaDB
-- Wersja PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `memes-hub`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meme_id` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8_polish_ci,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `meme_id`, `comment`, `created`) VALUES
(4, 4, 16, 'lool', '2018-06-25 03:58:00'),
(5, 4, 16, 'O kurcze pieczone :D', '2018-06-25 04:16:40');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `memes`
--

CREATE TABLE `memes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(24) COLLATE utf8_polish_ci DEFAULT NULL,
  `description` text COLLATE utf8_polish_ci,
  `path` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `memes`
--

INSERT INTO `memes` (`id`, `user_id`, `title`, `description`, `path`, `created`) VALUES
(16, 4, 'Hejciaa', 'tutajjj noo', './uploads/memes/4.user/16.Hejciaa/3f0710084deef715ad637a6f45691c3b - Copy.jpeg', '2018-06-25 03:11:25');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(24) COLLATE utf8_polish_ci DEFAULT NULL,
  `password` varchar(72) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(24) COLLATE utf8_polish_ci DEFAULT NULL,
  `activation_code` varchar(72) COLLATE utf8_polish_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `activation_code`, `activated`, `created`) VALUES
(4, 'user', '$2y$14$qiSy51kl4n.rjN7DFtxuce5K2hf5uDmVATqfzAcIIwWpIfHXJ6R1e', '123@gmail.com', NULL, 1, '2018-06-22 19:35:29'),
(7, 'jakislogin', '$2y$14$yHM3o1SrwRz9etHRg3mwius9XbsFJ3bw3UY4mYvPlsNkKD22WDsli', 'jakislogin@gmail.com', NULL, 1, '2018-06-22 19:35:29'),
(13, 'Bartusss', '$2y$14$jvX22xY0SnJMwp3JixrwEuqwARcQEEmTVV6NHg8KXFi1WA3wCZFOm', 'xd@gmail.com', NULL, 1, '2018-06-23 04:33:45'),
(14, 'JAceeekk', '$2y$14$NinZoytBTQNdcz7q8zMmN.267kWX1iUZFk2vY16AMRMcsKCma5MiG', 'j@gmail.com', NULL, 1, '2018-06-23 04:50:16');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meme_id` (`meme_id`);

--
-- Indeksy dla tabeli `memes`
--
ALTER TABLE `memes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `memes`
--
ALTER TABLE `memes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`meme_id`) REFERENCES `memes` (`id`);

--
-- Ograniczenia dla tabeli `memes`
--
ALTER TABLE `memes`
  ADD CONSTRAINT `memes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
