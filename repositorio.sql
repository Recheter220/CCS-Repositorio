-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 08-Mar-2018 às 14:31
-- Versão do servidor: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repositorio`
--
CREATE DATABASE IF NOT EXISTS `repositorio` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `repositorio`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_admin`
--

DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin` (
  `adm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `adm_nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_admin`
--

INSERT INTO `tb_admin` (`adm_id`, `user_id`, `adm_nome`) VALUES
(1, 1, 'Administrador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_aluno`
--

DROP TABLE IF EXISTS `tb_aluno`;
CREATE TABLE `tb_aluno` (
  `aluno_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `aluno_nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_aluno`
--

INSERT INTO `tb_aluno` (`aluno_id`, `user_id`, `aluno_nome`) VALUES
(1, 3, 'Aluno');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_artigo`
--

DROP TABLE IF EXISTS `tb_artigo`;
CREATE TABLE `tb_artigo` (
  `aluno_id` int(11) NOT NULL,
  `art_id` int(11) NOT NULL,
  `art_titulo` varchar(100) NOT NULL,
  `art_resumo` text NOT NULL,
  `art_caminho` text NOT NULL,
  `art_status` tinyint(1) NOT NULL,
  `art_userHomologacao` int(11) DEFAULT NULL,
  `art_dataHoraHomologacao` timestamp NULL DEFAULT NULL,
  `art_comentarioHomologacao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_artigo`
--

INSERT INTO `tb_artigo` (`aluno_id`, `art_id`, `art_titulo`, `art_resumo`, `art_caminho`, `art_status`, `art_userHomologacao`, `art_dataHoraHomologacao`, `art_comentarioHomologacao`) VALUES
(1, 4, 'titlee', 'abstracte', 'uploads/artigos/3_5aa06561e8f90.pdf', -1, 2, '2018-03-08 02:10:07', 'blablabla');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_professor`
--

DROP TABLE IF EXISTS `tb_professor`;
CREATE TABLE `tb_professor` (
  `prof_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prof_nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_professor`
--

INSERT INTO `tb_professor` (`prof_id`, `user_id`, `prof_nome`) VALUES
(1, 2, 'Professor');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE `tb_usuario` (
  `user_id` int(11) NOT NULL,
  `user_cpf` varchar(18) NOT NULL,
  `user_senha` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_tipo` enum('Administrador','Professor','Aluno') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`user_id`, `user_cpf`, `user_senha`, `user_email`, `user_tipo`) VALUES
(1, '1', '$2y$10$omMoG7VdA5BwlXevaLTPaeH9DWuqqBpUlKgbHk2dO3iU4jrM3/Irq', 'admin@example.com', 'Administrador'),
(2, '2', '$2y$10$1xgTJhSnHBRdpZJj565.nekBPYN8kDzCwmvv4oh1XB894BX6cEzCG', 'prof@example.com', 'Professor'),
(3, '3', '$2y$10$omMoG7VdA5BwlXevaLTPaeH9DWuqqBpUlKgbHk2dO3iU4jrM3/Irq', 'aluno@example.com', 'Aluno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`adm_id`),
  ADD KEY `fk_adm_user` (`user_id`);

--
-- Indexes for table `tb_aluno`
--
ALTER TABLE `tb_aluno`
  ADD PRIMARY KEY (`aluno_id`),
  ADD KEY `fk_aluno_user` (`user_id`);

--
-- Indexes for table `tb_artigo`
--
ALTER TABLE `tb_artigo`
  ADD PRIMARY KEY (`art_id`),
  ADD KEY `fk_artigo_aluno_id` (`aluno_id`);

--
-- Indexes for table `tb_professor`
--
ALTER TABLE `tb_professor`
  ADD PRIMARY KEY (`prof_id`),
  ADD KEY `fk_prof_user` (`user_id`);

--
-- Indexes for table `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_cpf` (`user_cpf`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_aluno`
--
ALTER TABLE `tb_aluno`
  MODIFY `aluno_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_artigo`
--
ALTER TABLE `tb_artigo`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_professor`
--
ALTER TABLE `tb_professor`
  MODIFY `prof_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD CONSTRAINT `fk_adm_user` FOREIGN KEY (`user_id`) REFERENCES `tb_usuario` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_aluno`
--
ALTER TABLE `tb_aluno`
  ADD CONSTRAINT `fk_aluno_user` FOREIGN KEY (`user_id`) REFERENCES `tb_usuario` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_artigo`
--
ALTER TABLE `tb_artigo`
  ADD CONSTRAINT `fk_artigo_aluno_id` FOREIGN KEY (`aluno_id`) REFERENCES `tb_aluno` (`aluno_id`);

--
-- Limitadores para a tabela `tb_professor`
--
ALTER TABLE `tb_professor`
  ADD CONSTRAINT `fk_prof_user` FOREIGN KEY (`user_id`) REFERENCES `tb_usuario` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
