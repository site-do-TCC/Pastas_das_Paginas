-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 14-Nov-2025 às 13:37
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
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `imgperfil` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente_telefone` varchar(20) DEFAULT NULL,
  `cliente_localizacao` varchar(150) DEFAULT NULL,
  `cliente_facebook` varchar(150) DEFAULT NULL,
  `cliente_instagram` varchar(100) DEFAULT NULL,
  `passou_cadastro` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id_usuario`, `nome`, `email`, `senha`, `imgperfil`, `criado_em`, `cliente_telefone`, `cliente_localizacao`, `cliente_facebook`, `cliente_instagram`, `passou_cadastro`) VALUES
(3, 'Teste', 'teste@gmail.com', 'teste123', '../ImgPerfilCliente/perfil_3.jpeg', '2025-11-03 17:54:34', '', 'awdawda', '', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `Nome` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `DescricaoGeral` varchar(500) CHARACTER SET utf8mb4 NOT NULL,
  `Aprender` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `TempoTotal` int(2) NOT NULL,
  `Nivel` varchar(40) CHARACTER SET utf8mb4 NOT NULL,
  `video` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `Nome`, `DescricaoGeral`, `Aprender`, `TempoTotal`, `Nivel`, `video`) VALUES
(1, 'Gestão de Tempo', 'Aprenda a organizar seu tempo e tarefas de forma eficiente, aumentando sua produtividade e reduzindo o estresse. Desenvolva hábitos que ajudam a manter foco e disciplina no dia a dia pessoal e profissional.', 'Planejamento diário e gestão de tempo;\r\nTécnicas de organização física e digital;\r\nEstabelecimento de prioridades;\r\nHábitos produtivos.\r\n', 10, 'Fácil', 'https://www.ev.org.br/cursos/organizacao-pessoal'),
(2, 'Atendimento ao Cliente', 'Este curso prepara você para se destacar no atendimento, ensinando técnicas para interagir de forma cordial, eficiente e profissional com clientes e público em geral. Aprenda a lidar com diferentes situações, manter a empatia e transmitir confiança em cada atendimento.', 'Técnicas de atendimento presencial e online;\r\nEscuta ativa e empatia;\r\nComo lidar com clientes difíceis;\r\nA importância do atendimento para a imagem de um profissional.\r\n', 10, 'Fácil', 'https://www.ev.org.br/cursos/atendimento-ao-publico');

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
(2, 'Mulittle', 'muriloalves.fonseca08@gmail.com', 'EFlSs', '2025-11-12 14:33:07', '../ImgPerfilPrestadoras/perfil_2.png', '../ImgBannersPrestadoras/banner1_id_2.jpg', '../ImgBannersPrestadoras/banner2_id_2.webp', '../ImgBannersPrestadoras/banner3_id_2.png', 'Mulittle', '(11)9722075060', 'muriloalves.fonseca08@gmail.com', 'Sp - Ferraz de Vasconcelos', 'http://FoiBanidoessapembra.com', 'murilimodeiocolica@gmail', 'Trabalho muito trabalho trabalho eu muito trabalho dimais', 'Trabalho muito trabalho trabalho eu muito trabalho dimais', 1),
(22, 'awdawd', 'awd@awd', '123', '2025-11-11 15:58:57', '../ImgPerfilPrestadoras/perfil_22.jpg', '../ImgBannersPrestadoras/banner1_id_22.jpg', '../ImgBannersPrestadoras/banner2_id_22.png', '../ImgBannersPrestadoras/banner3_id_22.jpg', 'Testeedicao', '1211111111111', 'testeedicao@gmail.com', 'lalalalalala', 'http://localhost/Programacao_TCC_Avena/html/EdicaoPerfil.php', '@cachorrofeiodapreula', 'awdawdawd', 'awdawdw', 1);

--
-- Índices para tabelas despejadas
--

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
-- Índices para tabela `prestadora`
--
ALTER TABLE `prestadora`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `prestadora`
--
ALTER TABLE `prestadora`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
