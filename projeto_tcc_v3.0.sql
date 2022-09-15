-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Tempo de geração: 14-Set-2022 às 18:09
-- Versão do servidor: 8.0.30
-- versão do PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto_tcc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `archives`
--

CREATE TABLE `archives` (
  `id_archive` int NOT NULL,
  `id_post` int NOT NULL,
  `name_archive` varchar(200) NOT NULL,
  `url_archive` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_posts`
--

CREATE TABLE `category_posts` (
  `id_category` int NOT NULL,
  `name_category` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `category_posts`
--

INSERT INTO `category_posts` (`id_category`, `name_category`, `created_at`) VALUES
(1, 'Artroses Tornozelo', '2022-09-14 12:39:28'),
(2, 'Artroses pós-traúmaticas', '2022-09-14 12:39:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE `posts` (
  `id_post` int NOT NULL,
  `id_user` int NOT NULL,
  `id_category` int NOT NULL,
  `title_post` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post` text NOT NULL,
  `accept_post` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `id_category`, `title_post`, `post`, `accept_post`, `created_at`) VALUES
(1, 1, 2, 'Sintomas', 'É uma doença que causa dores no local', 0, '2022-09-14 12:41:09'),
(2, 1, 1, 'Causas', 'Existem diversas causas que podem acometer a artrose, uma delas é o degaste natural da cartilagem', 0, '2022-09-14 12:41:09');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE `tags` (
  `id_tag` int NOT NULL,
  `id_post` int NOT NULL,
  `tag` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_permission` enum('admin','client') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id_user`, `name`, `email`, `password`, `role_permission`, `created_at`) VALUES
(1, 'iago', 'iago@outlook.com', '$2y$10$u4ddQNPAT3BGTjoD.5ailuu69Ap9MqEbOTE53.8yrwuIBftSn0LEy', 'client', '2022-09-14 12:17:43');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id_archive`),
  ADD KEY `id_post` (`id_post`);

--
-- Índices para tabela `category_posts`
--
ALTER TABLE `category_posts`
  ADD PRIMARY KEY (`id_category`);

--
-- Índices para tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices para tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `archives`
--
ALTER TABLE `archives`
  MODIFY `id_archive` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `category_posts`
--
ALTER TABLE `category_posts`
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id_tag` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `archives`
--
ALTER TABLE `archives`
  ADD CONSTRAINT `archives_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category_posts` (`id_category`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
