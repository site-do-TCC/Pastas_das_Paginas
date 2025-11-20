-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 03-Nov-2025 às 15:15
-- Versão do servidor: 5.7.36
-- versão do PHP: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_avena`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_prestadora` int(11) NOT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `chat`
--

INSERT INTO `chat` (`id_chat`, `id_cliente`, `id_prestadora`, `criado_em`) VALUES
(1, 1, 2, '2025-10-22 23:35:06'),
(2, 1, 1, '2025-10-31 20:44:17'),
(3, 3, 2, '2025-10-31 20:44:28'),
(4, 1, 3, '2025-10-31 20:44:49'),
(20, 3, 1, '2025-11-02 00:26:14'),
(21, 3, 3, '2025-11-02 00:26:16');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id_usuario`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Teste2', 'teste2@gmail.com', 'CtibT', '2025-09-21 23:39:28'),
(3, 'Teste3', 'teste3@gmail.com', '123', '2025-09-09 21:04:22'),
(4, 'Mulittle', 'muriloalves.fonseca08@gmail.com', '3RIPn', '2025-09-23 14:54:45');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `Nome` varchar(150) NOT NULL,
  `DescricaoGeral` varchar(500) NOT NULL,
  `Aprender` varchar(255) NOT NULL,
  `TempoTotal` int(2) NOT NULL,
  `Nivel` varchar(40) NOT NULL,
  `video` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagem`
--

CREATE TABLE `mensagem` (
  `id_mensagem` int(11) NOT NULL,
  `id_chat` int(11) NOT NULL,
  `id_de` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `enviado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_para` int(11) NOT NULL,
  `lido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `mensagem`
--

INSERT INTO `mensagem` (`id_mensagem`, `id_chat`, `id_de`, `conteudo`, `enviado_em`, `id_para`) VALUES
(2, 1, 1, 'a', '2025-10-22 23:35:10', 0),
(4, 1, 1, 'aaaaaa', '2025-10-23 05:29:27', 0),
(5, 1, 1, 'rebound', '2025-10-31 17:23:29', 2),
(6, 1, 1, 'a', '2025-10-31 18:40:29', 2),
(7, 1, 1, 'aaa', '2025-10-31 18:40:42', 2),
(8, 1, 1, 'aaa', '2025-10-31 18:40:43', 2),
(9, 1, 1, 'aaa', '2025-10-31 18:40:46', 2),
(10, 1, 1, 'aaaa', '2025-10-31 18:40:49', 2),
(11, 1, 1, 'zaaa', '2025-10-31 20:10:20', 2),
(12, 1, 1, 'aaaa', '2025-10-31 20:10:25', 2),
(13, 1, 1, 'a', '2025-10-31 20:10:26', 2),
(14, 1, 1, 'a', '2025-10-31 20:10:26', 2),
(15, 1, 1, 'a', '2025-10-31 20:10:26', 2),
(16, 1, 1, 'a', '2025-10-31 20:10:26', 2),
(17, 1, 1, 'zz\\z\\z\\\\', '2025-10-31 20:11:59', 2),
(18, 1, 1, '\\z\\z\\z\\z', '2025-10-31 20:12:04', 2),
(19, 1, 1, 'a', '2025-10-31 20:28:37', 2),
(20, 1, 1, 'a', '2025-10-31 20:28:38', 2),
(21, 1, 1, 'aaa', '2025-10-31 20:33:09', 2),
(22, 1, 1, 'aaaa', '2025-10-31 20:33:20', 2),
(23, 1, 1, 'meu deus', '2025-10-31 20:44:41', 2),
(24, 2, 1, 'aaaa', '2025-10-31 20:44:54', 1),
(25, 2, 1, 'aaaa', '2025-10-31 20:44:56', 1),
(26, 2, 1, 'oi', '2025-10-31 20:52:11', 1),
(27, 1, 1, 'oi', '2025-11-01 20:59:42', 2),
(28, 1, 1, 'oi', '2025-11-01 21:07:14', 2),
(29, 1, 1, 'aaaaaaloo', '2025-11-01 21:07:31', 2),
(30, 1, 1, 'a', '2025-11-01 21:07:31', 2),
(31, 1, 1, 'a', '2025-11-01 21:07:32', 2),
(32, 1, 1, 'a', '2025-11-01 21:07:32', 2),
(33, 1, 1, 'a', '2025-11-01 21:07:32', 2),
(34, 1, 1, 'a', '2025-11-01 21:07:32', 2),
(35, 1, 1, 'a', '2025-11-01 21:07:32', 2),
(36, 1, 1, 'a', '2025-11-01 21:07:33', 2),
(37, 1, 1, 'a', '2025-11-01 21:07:33', 2),
(38, 4, 1, 'aaaaa', '2025-11-01 21:13:38', 3),
(39, 1, 1, 'aa', '2025-11-01 21:13:41', 2),
(40, 2, 1, 'aaa', '2025-11-01 21:13:43', 1),
(41, 4, 1, 'Oi mandinha', '2025-11-01 21:30:00', 3),
(42, 4, 1, 'oiiiiiii', '2025-11-01 21:30:23', 3),
(43, 4, 1, 'aaaaa', '2025-11-01 21:40:19', 3),
(44, 4, 1, 'aaaaa', '2025-11-01 21:40:23', 3),
(45, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(46, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(47, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(48, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(49, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(50, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3),
(51, 4, 1, 'oix', '2025-11-01 21:40:38', 3),
(52, 4, 1, 'cuxin', '2025-11-01 21:41:21', 3),
(53, 4, 1, 'a', '2025-11-01 21:41:38', 3),
(54, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 21:42:49', 3),
(55, 4, 1, 'oi', '2025-11-01 21:51:25', 3),
(56, 4, 1, 'aaaaaaaaaaaaaaaaa', '2025-11-01 21:51:48', 3),
(57, 1, 1, 'aaa', '2025-11-01 21:51:51', 2),
(58, 2, 1, 'a', '2025-11-01 21:51:59', 1),
(59, 4, 1, 'aaaaaaa', '2025-11-01 22:49:24', 3),
(60, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:57:07', 3),
(61, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:59:34', 3),
(62, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:59:36', 3),
(63, 4, 1, 'a', '2025-11-02 00:13:34', 3),
(64, 1, 1, 'a', '2025-11-02 00:13:40', 2),
(65, 4, 1, 'ô amandinha', '2025-11-02 00:14:17', 3),
(66, 4, 1, 'caralho', '2025-11-02 00:14:19', 3),
(67, 4, 1, 'acelera isso aê', '2025-11-02 00:14:24', 3),
(68, 2, 1, 'oie', '2025-11-02 00:15:27', 1),
(69, 2, 1, 'queridoooooooooooo', '2025-11-02 00:17:03', 1),
(70, 2, 1, 'nossa q oido', '2025-11-02 00:17:10', 1),
(71, 4, 1, 'puta', '2025-11-02 00:21:45', 3),
(72, 4, 1, 'aaaaaa', '2025-11-02 00:21:59', 3),
(73, 2, 1, 'oi', '2025-11-02 00:24:39', 1),
(74, 1, 1, 'ei', '2025-11-02 00:24:56', 2),
(75, 1, 1, 'ooieeee', '2025-11-02 00:25:16', 2),
(76, 2, 1, 'amoreco', '2025-11-02 00:25:25', 1),
(77, 21, 3, 'ei', '2025-11-02 00:26:49', 3),
(78, 21, 3, 'bonitinha', '2025-11-02 00:26:54', 3),
(79, 21, 3, 'que foi', '2025-11-02 00:28:07', 3),
(80, 20, 1, 'ooiiii', '2025-11-02 08:46:54', 3),
(81, 20, 1, 'ta apagando pq', '2025-11-02 08:47:11', 3),
(82, 20, 1, 'aaaaa', '2025-11-02 08:47:20', 3),
(83, 20, 1, 'aa', '2025-11-02 08:47:20', 3),
(84, 20, 1, 'aa', '2025-11-02 08:47:21', 3),
(85, 20, 1, 'a', '2025-11-02 08:47:21', 3),
(86, 20, 1, 'oioi', '2025-11-02 09:06:35', 3),
(87, 20, 1, 'aaaaaaaaaaaaa', '2025-11-02 09:06:59', 3),
(88, 20, 1, 'a', '2025-11-02 09:07:02', 3),
(89, 20, 1, 'a', '2025-11-02 09:07:03', 3),
(90, 20, 1, 'a', '2025-11-02 09:07:03', 3),
(91, 20, 1, 'a', '2025-11-02 09:07:04', 3),
(92, 20, 1, 'aaa', '2025-11-02 09:07:23', 3),
(93, 20, 1, 'aa', '2025-11-02 09:07:25', 3),
(94, 20, 1, 'a', '2025-11-02 09:10:17', 3),
(95, 20, 1, 'a', '2025-11-02 09:10:20', 3),
(96, 20, 1, 'a', '2025-11-02 09:10:21', 3),
(97, 20, 1, 'a', '2025-11-02 09:10:28', 3),
(98, 20, 1, 'a', '2025-11-02 09:10:31', 3),
(99, 20, 1, 'a', '2025-11-02 09:10:33', 3),
(100, 20, 1, 'a', '2025-11-02 09:10:34', 3),
(101, 20, 1, 'aa', '2025-11-02 09:10:36', 3),
(102, 1, 1, 'aaaaa', '2025-11-02 09:14:33', 2),
(103, 1, 1, 'aaaaa', '2025-11-02 09:15:19', 2),
(104, 20, 1, 'aaaaaa', '2025-11-02 09:15:23', 3),
(105, 20, 1, 'aaaaa', '2025-11-02 09:15:25', 3),
(106, 20, 1, 'aaaaaaaa', '2025-11-02 09:15:28', 3),
(107, 20, 1, 'a', '2025-11-02 09:53:25', 3),
(108, 20, 1, 'a', '2025-11-02 09:53:33', 3),
(109, 20, 1, 'a', '2025-11-02 09:53:36', 3),
(110, 20, 1, 'a', '2025-11-02 09:57:29', 3),
(111, 20, 1, 'aaaaaaaaaaaaaaaaaa', '2025-11-02 09:57:35', 3),
(112, 20, 1, 'aaaaaaaa', '2025-11-02 10:15:44', 3),
(113, 1, 1, 'cu', '2025-11-02 10:15:49', 2),
(114, 1, 1, 'oi', '2025-11-02 10:15:55', 2),
(115, 20, 1, 'aaaa', '2025-11-02 10:16:03', 3),
(116, 1, 1, 'aaaa', '2025-11-02 10:16:08', 2),
(117, 2, 1, 'amigo', '2025-11-02 20:26:10', 1),
(118, 20, 1, 'amanda', '2025-11-02 20:26:21', 3),
(119, 2, 1, 'oi', '2025-11-02 23:10:27', 1),
(120, 20, 1, 'aaa', '2025-11-02 23:25:23', 3),
(121, 2, 1, 'alo', '2025-11-03 10:39:26', 1),
(122, 2, 1, 'ooii', '2025-11-03 10:47:07', 1),
(123, 2, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-03 10:47:42', 1),
(124, 2, 1, 'aaaaa', '2025-11-03 10:49:02', 1),
(125, 1, 1, 'a', '2025-11-03 10:49:22', 2),
(126, 1, 1, 'iu', '2025-11-03 10:55:29', 2),
(127, 1, 1, 'alo', '2025-11-03 10:55:51', 2),
(128, 4, 1, 'e', '2025-11-03 11:00:25', 3),
(129, 4, 1, 'oi', '2025-11-03 11:07:09', 3),
(130, 4, 1, 'a', '2025-11-03 11:07:14', 3),
(131, 4, 1, 'a', '2025-11-03 11:07:20', 3),
(132, 4, 1, 'a', '2025-11-03 11:07:20', 3),
(133, 2, 1, 'a', '2025-11-03 11:07:27', 1),
(134, 4, 1, 'aaaa', '2025-11-03 11:09:14', 3),
(135, 1, 1, 'a', '2025-11-03 11:09:19', 2),
(136, 4, 1, 'aaa', '2025-11-03 11:09:22', 3),
(137, 4, 1, 'a', '2025-11-03 11:09:27', 3),
(138, 4, 1, 'a', '2025-11-03 11:10:17', 3),
(139, 4, 1, 'a', '2025-11-03 11:10:21', 3),
(140, 4, 1, 'a', '2025-11-03 11:10:48', 3),
(141, 4, 1, 'a', '2025-11-03 11:10:52', 3),
(142, 4, 1, 'aa', '2025-11-03 11:10:57', 3),
(143, 4, 1, 'a', '2025-11-03 11:11:07', 3),
(144, 4, 1, 'a', '2025-11-03 11:11:19', 3),
(145, 4, 1, 'a', '2025-11-03 11:12:48', 3),
(146, 4, 1, 'a', '2025-11-03 11:12:59', 3),
(147, 4, 1, 'a', '2025-11-03 11:13:12', 3),
(148, 4, 1, 'aaa', '2025-11-03 11:13:19', 3),
(149, 4, 1, 'aa', '2025-11-03 11:13:19', 3),
(150, 4, 1, 'aa', '2025-11-03 11:13:20', 3),
(151, 4, 1, 'a', '2025-11-03 11:13:20', 3),
(152, 4, 1, 'aaa', '2025-11-03 11:13:36', 3),
(153, 2, 1, 'aaa', '2025-11-03 11:13:46', 1),
(154, 4, 1, 'a', '2025-11-03 11:14:27', 3),
(155, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-03 11:39:06', 3),
(156, 4, 1, 'aaaa', '2025-11-03 11:53:34', 3),
(157, 4, 1, 'aaaa', '2025-11-03 11:54:28', 3),
(158, 4, 1, 'aaaa', '2025-11-03 11:55:17', 3),
(159, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:27', 3),
(160, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3),
(161, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3),
(162, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3),
(163, 4, 1, 'aaaa', '2025-11-03 12:04:26', 3),
(164, 4, 1, 'aaaa', '2025-11-03 12:04:43', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `prestadora`
--

CREATE TABLE `prestadora` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `imgperfil` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `banner1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `banner2` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `banner3` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `empresa_nome` varchar(100) DEFAULT NULL,
  `empresa_telefone` varchar(20) DEFAULT NULL,
  `empresa_email` varchar(100) DEFAULT NULL,
  `empresa_localizacao` varchar(150) DEFAULT NULL,
  `empresa_facebook` varchar(150) DEFAULT NULL,
  `empresa_instagram` varchar(150) DEFAULT NULL,
  `empresa_biografia` text,
  `empresa_servicos` text,
  `passou_cadastro` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `prestadora`
--

INSERT INTO `prestadora` (`id_usuario`, `nome`, `email`, `senha`, `criado_em`, `imgperfil`, `banner1`, `banner2`, `banner3`, `empresa_nome`, `empresa_telefone`, `empresa_email`, `empresa_localizacao`, `empresa_facebook`, `empresa_instagram`, `empresa_biografia`, `empresa_servicos`, `passou_cadastro`) VALUES
(1, 'Teste', 'teste@gmail.com', '123', '2025-10-14 22:01:16', '../ImgPerfilPrestadoras/perfil_1.webp', '../ImgBannersPrestadoras/banner1_id_1.jpg', '../ImgBannersPrestadoras/banner2_id_1.jpg', '../ImgBannersPrestadoras/banner3_id_1.jpg', 'Teste', '12345', 'teste@gmail.com', 'Sp', 'http://localhost/Programacao_TCC_Avena/html/EdicaoPerfil.php', 'teste@gmail.com', 'testetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetesteteste', 'etestetesteaaa aaaaa\naaaaaaaaaaaaaaaaaaaa aaaaaaaaaa', 1),
(2, 'Mulittle', 'muriloalves.fonseca08@gmail.com', 'EFlSs', '2025-10-14 20:41:03', '../ImgPerfilPrestadoras/perfil_2.png', '../ImgBannersPrestadoras/banner1_id_2.jpg', '../ImgBannersPrestadoras/banner2_id_2.webp', '../ImgBannersPrestadoras/banner3_id_2.png', 'Mulittle', '(11)9722075060', 'muriloalves.fonseca08@gmail.com', 'Sp - Ferraz de Vasconcelos', 'http://FoiBanidoessapembra.com', 'murilimodeiocolica@gmail', 'Odeio Colica Odeio Colica Odeio ColicaOdeio Colica Odeio Colica Odeio Colica', 'Odeio Colica Odeio Colica Odeio Colica Odeio Colica Odeio Colica', 1),
(3, 'Amanda', 'amanda3capa@gmail.com', '123', '2025-10-14 22:57:37', '../ImgPerfilPrestadoras/perfil_3.webp', '../ImgBannersPrestadoras/banner1_id_3.png', '../ImgBannersPrestadoras/banner2_id_3.png', '../ImgBannersPrestadoras/banner3_id_3.png', 'Amanda', '(11)74882532', 'amanda3capa@gmail.com', 'Kalahari', 'http://FoiBanidoessapembra.com', 'amandinha@arroba', 'sou a amandinha, gostu de fri fairi', 'sou a amandinha, gostu de fri fairi, jogo fri fai9ri, dou 3 capas na tropinha do free fairei', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_prestadora` (`id_prestadora`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`);

--
-- Índices para tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `id_chat` (`id_chat`),
  ADD KEY `id_para` (`id_para`),
  ADD KEY `idx_unread` (`id_chat`,`id_para`,`lido`);

--
-- Índices para tabela `prestadora`
--
ALTER TABLE `prestadora`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagem`
--
ALTER TABLE `mensagem`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de tabela `prestadora`
--
ALTER TABLE `prestadora`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_usuario`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_prestadora`) REFERENCES `prestadora` (`id_usuario`);

--
-- Limitadores para a tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD CONSTRAINT `mensagem_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id_chat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
