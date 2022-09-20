-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Tempo de geração: 20-Set-2022 às 00:35
-- Versão do servidor: 8.0.29
-- versão do PHP: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: project_tcc
--

-- --------------------------------------------------------

--
-- Estrutura da tabela archives
--

CREATE TABLE archives (
  id_archive int NOT NULL,
  id_post int NOT NULL,
  name_archive varchar(200) NOT NULL,
  url_archive varchar(200) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela archives
--

INSERT INTO archives (id_archive, id_post, name_archive, url_archive, created_at) VALUES
(12, 30, 'cd6591d4ad_time_1663036782_archive_000030.jpg', 'http://localhost:8001/app/archives/cd6591d4ad_time_1663036782_archive_000030.jpg', '2022-09-13 02:39:42'),
(13, 30, '19269d8c49_time_1663036782_archive_000030.jpg', 'http://localhost:8001/app/archives/19269d8c49_time_1663036782_archive_000030.jpg', '2022-09-13 02:39:42'),
(14, 30, 'b7ea5142fa_time_1663036782_archive_000030.jpg', 'http://localhost:8001/app/archives/b7ea5142fa_time_1663036782_archive_000030.jpg', '2022-09-13 02:39:42'),
(15, 30, 'd811ba4cee_time_1663036782_archive_000030.jpg', 'http://localhost:8001/app/archives/d811ba4cee_time_1663036782_archive_000030.jpg', '2022-09-13 02:39:42'),
(16, 31, 'f35ec8d2f4_time_1663199971_archive_000031.jpg', 'http://localhost:8001/app/archives/f35ec8d2f4_time_1663199971_archive_000031.jpg', '2022-09-14 23:59:31'),
(17, 31, '6313fc318f_time_1663199971_archive_000031.jpg', 'http://localhost:8001/app/archives/6313fc318f_time_1663199971_archive_000031.jpg', '2022-09-14 23:59:31'),
(18, 31, '8f9dabc6ba_time_1663199971_archive_000031.jpg', 'http://localhost:8001/app/archives/8f9dabc6ba_time_1663199971_archive_000031.jpg', '2022-09-14 23:59:31'),
(19, 31, 'f870f78e9a_time_1663199971_archive_000031.jpg', 'http://localhost:8001/app/archives/f870f78e9a_time_1663199971_archive_000031.jpg', '2022-09-14 23:59:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela category_posts
--

CREATE TABLE category_posts (
  id_category int NOT NULL,
  name_category varchar(100) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela category_posts
--

INSERT INTO category_posts (id_category, name_category, created_at) VALUES
(1, 'teste', '2022-09-08 02:23:25'),
(2, 'categoria de teste', '2022-09-13 02:12:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela posts
--

CREATE TABLE posts (
  id_post int NOT NULL,
  id_user int NOT NULL,
  id_category int NOT NULL,
  title_post varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  post text NOT NULL,
  accept_post tinyint(1) NOT NULL DEFAULT '0',
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela posts
--

INSERT INTO posts (id_post, id_user, id_category, title_post, post, accept_post, created_at) VALUES
(1, 1, 1, '', 'ESSE POST É UM TESTE DE DESENVOLVIMENTO', 1, '2022-09-08 02:24:21'),
(2, 1, 1, '', 'ESSE É O SEGUNDO POST DE TESTE', 1, '2022-09-08 02:24:21'),
(30, 1, 1, 'POST TESTE', 'ESSE POST FOI INSERIDO VIA INSOMNIA PARA TESTE DE UPLOAD DE ARQUIVOS DE IMAGEM, CONVERTIDOS PARA ', 0, '2022-09-13 02:39:42'),
(31, 1, 1, 'POST TESTE', 'ESSE POST FOI INSERIDO VIA INSOMNIA PARA TESTE DE UPLOAD DE ARQUIVOS DE IMAGEM, CONVERTIDOS PARA ', 0, '2022-09-14 23:59:31');

-- --------------------------------------------------------

--
-- Estrutura da tabela tags
--

CREATE TABLE tags (
  id_tag int NOT NULL,
  id_post int NOT NULL,
  tag varchar(200) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela tags
--

INSERT INTO tags (id_tag, id_post, tag, created_at) VALUES
(1, 1, 'ARTROSE,JOELHO,TORNOZELO', '2022-09-19 23:46:04'),
(2, 30, 'TESTE', '2022-09-19 23:46:04'),
(3, 2, 'TESTE', '2022-09-19 23:51:33'),
(4, 30, 'HELLO', '2022-09-20 00:25:42'),
(5, 2, 'BUSCA', '2022-09-20 00:33:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela user
--

CREATE TABLE `user` (
  id_user int NOT NULL,
  name varchar(255) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  role_permission enum('admin','client') DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela user
--

INSERT INTO user (id_user, name, email, password, role_permission, created_at) VALUES
(1, 'Iago', 'iagooliveira09@outlook.com', NULL, 'admin', '2022-09-08 02:23:06'),
(2, 'teste', 'teste@teste.com', '$2y$10$vn4DZvEFKcq9H0aXGPnxMu/Ui2sC1QYcbD1BL4yPVyTWUmOMfEGhG', 'client', '2022-09-08 03:00:45'),
(8, 'teste', 'teste1@teste.com', '$2y$10$rwS0oeH.nA1vNKDsaJPJLuka./RW4uox2qdpBgVkYUXzhzYdXrwCq', 'client', '2022-09-14 02:40:11');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela archives
--
ALTER TABLE archives
  ADD PRIMARY KEY (id_archive),
  ADD KEY id_post (id_post);

--
-- Índices para tabela category_posts
--
ALTER TABLE category_posts
  ADD PRIMARY KEY (id_category);

--
-- Índices para tabela posts
--
ALTER TABLE posts
  ADD PRIMARY KEY (id_post),
  ADD KEY id_category (id_category),
  ADD KEY id_user (id_user);

--
-- Índices para tabela tags
--
ALTER TABLE tags
  ADD PRIMARY KEY (id_tag),
  ADD KEY id_post (id_post);

--
-- Índices para tabela user
--
ALTER TABLE user
  ADD PRIMARY KEY (id_user),
  ADD UNIQUE KEY email (email);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela archives
--
ALTER TABLE archives
  MODIFY id_archive int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela category_posts
--
ALTER TABLE category_posts
  MODIFY id_category int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela posts
--
ALTER TABLE posts
  MODIFY id_post int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela tags
--
ALTER TABLE tags
  MODIFY id_tag int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela user
--
ALTER TABLE user
  MODIFY id_user int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela archives
--
ALTER TABLE archives
  ADD CONSTRAINT archives_ibfk_1 FOREIGN KEY (id_post) REFERENCES posts (id_post) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela posts
--
ALTER TABLE posts
  ADD CONSTRAINT posts_ibfk_1 FOREIGN KEY (id_category) REFERENCES category_posts (id_category) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT posts_ibfk_2 FOREIGN KEY (id_user) REFERENCES `user` (id_user) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Limitadores para a tabela tags
--
ALTER TABLE tags
  ADD CONSTRAINT tags_ibfk_1 FOREIGN KEY (id_post) REFERENCES posts (id_post);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
