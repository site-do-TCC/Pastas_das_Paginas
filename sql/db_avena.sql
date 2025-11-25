-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
<<<<<<< HEAD
-- Tempo de geração: 24-Nov-2025 às 21:37
=======
-- Tempo de geração: 23-Nov-2025 às 20:11
>>>>>>> master
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
-- Estrutura da tabela `agenda`
<<<<<<< HEAD
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_usuario` enum('cliente','prestadora') NOT NULL,
  `data_evento` date NOT NULL,
  `anotacao` text NOT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `id_usuario`, `tipo_usuario`, `data_evento`, `anotacao`, `criado_em`) VALUES
(2, 3, 'cliente', '2025-11-20', 'AnotaÃ§Ã£o pra amanhÃ£', '2025-11-19 20:12:52'),
(3, 3, 'cliente', '2025-12-12', 'awd', '2025-11-19 20:13:06'),
(4, 4, 'cliente', '2025-11-24', 'Marcar o cabelo com a Geisa. MARCAR ATÃ‰ DIA 24', '2025-11-22 16:34:37'),
(5, 23, 'prestadora', '2025-11-27', 'Cabelo com o Lucas', '2025-11-22 16:35:59'),
(6, 4, 'cliente', '2025-11-25', 'Cabelo com a geisa', '2025-11-22 16:37:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `avaliador_id` int(11) NOT NULL,
  `avaliador_tipo` enum('cliente','prestadora') NOT NULL,
  `avaliado_id` int(11) NOT NULL,
  `avaliado_tipo` enum('cliente','prestadora') NOT NULL,
  `nota` int(11) NOT NULL,
  `comentario` text,
  `data_avaliacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `avaliador_id`, `avaliador_tipo`, `avaliado_id`, `avaliado_tipo`, `nota`, `comentario`, `data_avaliacao`) VALUES
(1, 3, 'cliente', 22, 'prestadora', 5, 'a', '2025-11-21 20:20:44'),
(2, 1, 'prestadora', 3, 'cliente', 5, 'a', '2025-11-21 20:31:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat`
=======
>>>>>>> master
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_usuario` enum('cliente','prestadora') NOT NULL,
  `data_evento` date NOT NULL,
  `anotacao` text NOT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `id_usuario`, `tipo_usuario`, `data_evento`, `anotacao`, `criado_em`) VALUES
(2, 3, 'cliente', '2025-11-20', 'AnotaÃ§Ã£o pra amanhÃ£', '2025-11-19 20:12:52'),
(3, 3, 'cliente', '2025-12-12', 'awd', '2025-11-19 20:13:06'),
(4, 4, 'cliente', '2025-11-24', 'Marcar o cabelo com a Geisa. MARCAR ATÃ‰ DIA 24', '2025-11-22 16:34:37'),
(5, 23, 'prestadora', '2025-11-27', 'Cabelo com o Lucas', '2025-11-22 16:35:59'),
(6, 4, 'cliente', '2025-11-25', 'Cabelo com a geisa', '2025-11-22 16:37:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `avaliador_id` int(11) NOT NULL,
  `avaliador_tipo` enum('cliente','prestadora') NOT NULL,
  `avaliado_id` int(11) NOT NULL,
  `avaliado_tipo` enum('cliente','prestadora') NOT NULL,
  `nota` int(11) NOT NULL,
  `comentario` text,
  `data_avaliacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `avaliador_id`, `avaliador_tipo`, `avaliado_id`, `avaliado_tipo`, `nota`, `comentario`, `data_avaliacao`) VALUES
(1, 3, 'cliente', 22, 'prestadora', 5, 'a', '2025-11-21 20:20:44'),
(2, 1, 'prestadora', 3, 'cliente', 5, 'a', '2025-11-21 20:31:47');

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
(3, 'Teste', 'teste@gmail.com', 'teste123', '../ImgPerfilCliente/perfil_3.jpeg', '2025-11-03 17:54:34', '', 'awdawda', '', '', 1),
(4, 'Lucas Vitor', 'lucasvitor@gmail.com', 'lucas', '../ImgPerfilCliente/perfil_4.jpg', '2025-11-22 19:33:12', '(11)997342852', 'Rua Xingo Katanaedo', 'http://faceboock/lucasvitor', '@lucasvitor', 1);

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
(1, 'Gestão de Tempo', 'Aprenda a organizar seu tempo e tarefas de forma eficiente, aumentando sua produtividade e reduzindo o estresse. Desenvolva hábitos que ajudam a manter foco e disciplina no dia a dia pessoal e profissional.', 'Planejamento diário e gestão de tempo;\nTécnicas de organização física e digital;\nEstabelecimento de prioridades;\nHábitos produtivos.\n', 10, 'Fácil', 'https://www.ev.org.br/cursos/organizacao-pessoal'),
(2, 'Atendimento ao Cliente', 'Este curso prepara você para se destacar no atendimento, ensinando técnicas para interagir de forma cordial, eficiente e profissional com clientes e público em geral. Aprenda a lidar com diferentes situações, manter a empatia e transmitir confiança em cada atendimento.', 'Técnicas de atendimento presencial e online;\r\nEscuta ativa e empatia;\r\nComo lidar com clientes difíceis;\r\nA importância do atendimento para a imagem de um profissional.\r\n', 10, 'Fácil', 'https://www.ev.org.br/cursos/atendimento-ao-publico'),
(3, 'Comunicação Escrita', 'Aprenda a escrever de forma clara, objetiva e adequada para o ambiente profissional. Este curso capacita você a transmitir ideias com eficiência, evitando mal-entendidos e melhorando sua imagem no trabalho ou nos estudos.', 'Redação clara e objetiva; Estrutura de textos profissionais; Comunicação escrita para diferentes públicos; Normas e regras da escrita.', 10, 'Fácil', 'https://www.ev.org.br/cursos/comunicacao-escrita'),
(4, 'Educação Financeira', 'Desenvolva habilidades essenciais para controlar suas finanças pessoais. Aprenda a planejar gastos, poupar de forma inteligente e tomar decisões financeiras conscientes para alcançar seus objetivos.', 'Planejamento financeiro pessoal; Controle de gastos e orçamento; Noções de poupança e investimentos; Tomada de decisões financeiras conscientes.', 10, 'Fácil', 'https://www.ev.org.br/cursos/educacao-financeira'),
(5, 'Manicure e Pedicure', 'Aprenda técnicas profissionais de manicure e pedicure, com foco em estética, higiene e satisfação do cliente.', 'Técnicas de manicure e pedicure; Higienização e cuidados com unhas; Tipos de esmaltação e decoração; Noções de saúde e segurança.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/manicure-e-pedicure/'),
(6, 'Maquiagem Profissional', 'Aprenda a criar maquiagens sofisticadas e adequadas para diferentes ocasiões. Desenvolva habilidades práticas em técnicas, cores e produtos, garantindo resultados profissionais e clientes satisfeitos.', 'Técnicas de maquiagem para diferentes ocasições; Uso correto de pincéis e produtos; Harmonização de cores e tipos de pele; Cuidados e higiene profissional.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/maquiagem-profissional/'),
(7, 'Trancista', 'Capacite-se para atuar como trancista profissional, dominando técnicas de tranças e penteados modernos, além de oferecer um atendimento de qualidade e cuidar da saúde capilar dos clientes.', 'Técnicas de tranças, penteados e manutenção; Produtos e cuidados com cabelos; Atendimento ao cliente; Higiene e postura profissional.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/trancista/'),
(8, 'Inclusividade', 'Aprenda a promover diversidade e inclusão no atendimento ao público. Desenvolva atitudes que valorizam a pluralidade e combatem preconceitos, tornando-se um profissional mais consciente e preparado.', 'Conceitos de diversidade e inclusão; Atendimento inclusivo; Combate a preconceitos; Estratégias de inclusão no trabalho.', 10, 'Fácil', 'https://www.ev.org.br/cursos/inclusividade'),
(9, 'Empreendedorismo e Inovação', 'Descubra como transformar ideias em negócios de sucesso. Este curso ensina conceitos de empreendedorismo, inovação e planejamento estratégico, preparando você para identificar oportunidades e criar soluções criativas.', 'Conceitos de empreendedorismo e inovação; Identificação de oportunidades de negócio; Planejamento estratégico; Criatividade e resolução de problemas.', 10, 'Fácil', 'https://www.ev.org.br/cursos/empreendedorismo-e-inovacao'),
(10, 'Boas Práticas de Manipulação de Alimentos', 'Aprenda a manusear alimentos de forma segura, garantindo higiene e prevenção de contaminações. Ideal para quem atua na área de alimentação e deseja oferecer serviços com qualidade e segurança.', 'Higiene pessoal e do ambiente; Armazenamento correto de alimentos; Prevenção de contaminação; Normas de segurança alimentar.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/boas-praticas-de-manipulacao-de-alimentos/'),
(11, 'Congelamento de Alimentos', 'Aprenda técnicas corretas de congelamento e conservação de alimentos, mantendo qualidade, sabor e valor nutricional.', 'Técnicas corretas de congelamento; Conservação adequada de alimentos; Segurança alimentar; Planejamento e armazenamento eficiente.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/congelamento-de-alimentos/'),
(12, 'Marketing', 'Este curso apresenta conceitos e estratégias de marketing, ensinando como promover produtos e serviços, planejar campanhas e se comunicar de forma eficaz com diferentes públicos.', 'Conceitos de marketing digital e tradicional; Estratégias de vendas e comunicação; Planejamento de campanhas; Relacionamento com clientes.', 20, 'Fácil', 'https://www.educaweb.com.br/cursos/marketing/'),
(13, 'Resiliência', 'Desenvolva a capacidade de superar desafios, mantendo equilíbrio emocional e foco nos objetivos. Este curso ensina técnicas para lidar com situações adversas e fortalecer sua postura pessoal e profissional.', 'Conceito e importância da resiliência; Técnicas para lidar com adversidades; Desenvolvimento pessoal e profissional; Controle emocional.', 10, 'Fácil', 'https://www.ev.org.br/cursos/resiliencia'),
(14, 'Postura e Imagem Profissional', 'Aprenda a transmitir uma imagem profissional positiva, aprimorando postura, etiqueta, comunicação e aparência, essenciais para destacar-se.', 'Etiqueta e comportamento profissional; Comunicação não verbal; Apresentação pessoal; Construção de imagem profissional positiva.', 10, 'Fácil', 'https://www.ev.org.br/cursos/postura-e-imagem-profissional'),
(15, 'Análise de Balanços', 'Aprenda a interpretar demonstrações financeiras e indicadores contábeis para tomar decisões estratégicas baseadas em dados concretos.', 'Interpretação de demonstrações financeiras; Indicadores de saúde financeira; Planejamento estratégico a partir de balanços; Tomada de decisões baseada em dados contábeis.', 10, 'Fácil', 'https://www.ev.org.br/cursos/analise-de-balancos');

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

INSERT INTO `mensagem` (`id_mensagem`, `id_chat`, `id_de`, `conteudo`, `enviado_em`, `id_para`, `lido`) VALUES
(2, 1, 1, 'a', '2025-10-22 23:35:10', 0, 0),
(4, 1, 1, 'aaaaaa', '2025-10-23 05:29:27', 0, 0),
(5, 1, 1, 'rebound', '2025-10-31 17:23:29', 2, 0),
(6, 1, 1, 'a', '2025-10-31 18:40:29', 2, 0),
(7, 1, 1, 'aaa', '2025-10-31 18:40:42', 2, 0),
(8, 1, 1, 'aaa', '2025-10-31 18:40:43', 2, 0),
(9, 1, 1, 'aaa', '2025-10-31 18:40:46', 2, 0),
(10, 1, 1, 'aaaa', '2025-10-31 18:40:49', 2, 0),
(11, 1, 1, 'zaaa', '2025-10-31 20:10:20', 2, 0),
(12, 1, 1, 'aaaa', '2025-10-31 20:10:25', 2, 0),
(13, 1, 1, 'a', '2025-10-31 20:10:26', 2, 0),
(14, 1, 1, 'a', '2025-10-31 20:10:26', 2, 0),
(15, 1, 1, 'a', '2025-10-31 20:10:26', 2, 0),
(16, 1, 1, 'a', '2025-10-31 20:10:26', 2, 0),
(17, 1, 1, 'zz\\z\\z\\\\', '2025-10-31 20:11:59', 2, 0),
(18, 1, 1, '\\z\\z\\z\\z', '2025-10-31 20:12:04', 2, 0),
(19, 1, 1, 'a', '2025-10-31 20:28:37', 2, 0),
(20, 1, 1, 'a', '2025-10-31 20:28:38', 2, 0),
(21, 1, 1, 'aaa', '2025-10-31 20:33:09', 2, 0),
(22, 1, 1, 'aaaa', '2025-10-31 20:33:20', 2, 0),
(23, 1, 1, 'meu deus', '2025-10-31 20:44:41', 2, 0),
(24, 2, 1, 'aaaa', '2025-10-31 20:44:54', 1, 0),
(25, 2, 1, 'aaaa', '2025-10-31 20:44:56', 1, 0),
(26, 2, 1, 'oi', '2025-10-31 20:52:11', 1, 0),
(27, 1, 1, 'oi', '2025-11-01 20:59:42', 2, 0),
(28, 1, 1, 'oi', '2025-11-01 21:07:14', 2, 0),
(29, 1, 1, 'aaaaaaloo', '2025-11-01 21:07:31', 2, 0),
(30, 1, 1, 'a', '2025-11-01 21:07:31', 2, 0),
(31, 1, 1, 'a', '2025-11-01 21:07:32', 2, 0),
(32, 1, 1, 'a', '2025-11-01 21:07:32', 2, 0),
(33, 1, 1, 'a', '2025-11-01 21:07:32', 2, 0),
(34, 1, 1, 'a', '2025-11-01 21:07:32', 2, 0),
(35, 1, 1, 'a', '2025-11-01 21:07:32', 2, 0),
(36, 1, 1, 'a', '2025-11-01 21:07:33', 2, 0),
(37, 1, 1, 'a', '2025-11-01 21:07:33', 2, 0),
(38, 4, 1, 'aaaaa', '2025-11-01 21:13:38', 3, 0),
(39, 1, 1, 'aa', '2025-11-01 21:13:41', 2, 0),
(40, 2, 1, 'aaa', '2025-11-01 21:13:43', 1, 0),
(41, 4, 1, 'Oi mandinha', '2025-11-01 21:30:00', 3, 0),
(42, 4, 1, 'oiiiiiii', '2025-11-01 21:30:23', 3, 0),
(43, 4, 1, 'aaaaa', '2025-11-01 21:40:19', 3, 0),
(44, 4, 1, 'aaaaa', '2025-11-01 21:40:23', 3, 0),
(45, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(46, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(47, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(48, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(49, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(50, 4, 1, 'aaaaa', '2025-11-01 21:40:32', 3, 0),
(51, 4, 1, 'oix', '2025-11-01 21:40:38', 3, 0),
(52, 4, 1, 'cuxin', '2025-11-01 21:41:21', 3, 0),
(53, 4, 1, 'a', '2025-11-01 21:41:38', 3, 0),
(54, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 21:42:49', 3, 0),
(55, 4, 1, 'oi', '2025-11-01 21:51:25', 3, 0),
(56, 4, 1, 'aaaaaaaaaaaaaaaaa', '2025-11-01 21:51:48', 3, 0),
(57, 1, 1, 'aaa', '2025-11-01 21:51:51', 2, 0),
(58, 2, 1, 'a', '2025-11-01 21:51:59', 1, 0),
(59, 4, 1, 'aaaaaaa', '2025-11-01 22:49:24', 3, 0),
(60, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:57:07', 3, 0),
(61, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:59:34', 3, 0),
(62, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-01 22:59:36', 3, 0),
(63, 4, 1, 'a', '2025-11-02 00:13:34', 3, 0),
(64, 1, 1, 'a', '2025-11-02 00:13:40', 2, 0),
(65, 4, 1, 'ô amandinha', '2025-11-02 00:14:17', 3, 0),
(66, 4, 1, 'caralho', '2025-11-02 00:14:19', 3, 0),
(67, 4, 1, 'acelera isso aê', '2025-11-02 00:14:24', 3, 0),
(68, 2, 1, 'oie', '2025-11-02 00:15:27', 1, 0),
(69, 2, 1, 'queridoooooooooooo', '2025-11-02 00:17:03', 1, 0),
(70, 2, 1, 'nossa q oido', '2025-11-02 00:17:10', 1, 0),
(71, 4, 1, 'puta', '2025-11-02 00:21:45', 3, 0),
(72, 4, 1, 'aaaaaa', '2025-11-02 00:21:59', 3, 0),
(73, 2, 1, 'oi', '2025-11-02 00:24:39', 1, 0),
(74, 1, 1, 'ei', '2025-11-02 00:24:56', 2, 0),
(75, 1, 1, 'ooieeee', '2025-11-02 00:25:16', 2, 0),
(76, 2, 1, 'amoreco', '2025-11-02 00:25:25', 1, 0),
(77, 21, 3, 'ei', '2025-11-02 00:26:49', 3, 0),
(78, 21, 3, 'bonitinha', '2025-11-02 00:26:54', 3, 0),
(79, 21, 3, 'que foi', '2025-11-02 00:28:07', 3, 0),
(80, 20, 1, 'ooiiii', '2025-11-02 08:46:54', 3, 0),
(81, 20, 1, 'ta apagando pq', '2025-11-02 08:47:11', 3, 0),
(82, 20, 1, 'aaaaa', '2025-11-02 08:47:20', 3, 0),
(83, 20, 1, 'aa', '2025-11-02 08:47:20', 3, 0),
(84, 20, 1, 'aa', '2025-11-02 08:47:21', 3, 0),
(85, 20, 1, 'a', '2025-11-02 08:47:21', 3, 0),
(86, 20, 1, 'oioi', '2025-11-02 09:06:35', 3, 0),
(87, 20, 1, 'aaaaaaaaaaaaa', '2025-11-02 09:06:59', 3, 0),
(88, 20, 1, 'a', '2025-11-02 09:07:02', 3, 0),
(89, 20, 1, 'a', '2025-11-02 09:07:03', 3, 0),
(90, 20, 1, 'a', '2025-11-02 09:07:03', 3, 0),
(91, 20, 1, 'a', '2025-11-02 09:07:04', 3, 0),
(92, 20, 1, 'aaa', '2025-11-02 09:07:23', 3, 0),
(93, 20, 1, 'aa', '2025-11-02 09:07:25', 3, 0),
(94, 20, 1, 'a', '2025-11-02 09:10:17', 3, 0),
(95, 20, 1, 'a', '2025-11-02 09:10:20', 3, 0),
(96, 20, 1, 'a', '2025-11-02 09:10:21', 3, 0),
(97, 20, 1, 'a', '2025-11-02 09:10:28', 3, 0),
(98, 20, 1, 'a', '2025-11-02 09:10:31', 3, 0),
(99, 20, 1, 'a', '2025-11-02 09:10:33', 3, 0),
(100, 20, 1, 'a', '2025-11-02 09:10:34', 3, 0),
(101, 20, 1, 'aa', '2025-11-02 09:10:36', 3, 0),
(102, 1, 1, 'aaaaa', '2025-11-02 09:14:33', 2, 0),
(103, 1, 1, 'aaaaa', '2025-11-02 09:15:19', 2, 0),
(104, 20, 1, 'aaaaaa', '2025-11-02 09:15:23', 3, 0),
(105, 20, 1, 'aaaaa', '2025-11-02 09:15:25', 3, 0),
(106, 20, 1, 'aaaaaaaa', '2025-11-02 09:15:28', 3, 0),
(107, 20, 1, 'a', '2025-11-02 09:53:25', 3, 0),
(108, 20, 1, 'a', '2025-11-02 09:53:33', 3, 0),
(109, 20, 1, 'a', '2025-11-02 09:53:36', 3, 0),
(110, 20, 1, 'a', '2025-11-02 09:57:29', 3, 0),
(111, 20, 1, 'aaaaaaaaaaaaaaaaaa', '2025-11-02 09:57:35', 3, 0),
(112, 20, 1, 'aaaaaaaa', '2025-11-02 10:15:44', 3, 0),
(113, 1, 1, 'cu', '2025-11-02 10:15:49', 2, 0),
(114, 1, 1, 'oi', '2025-11-02 10:15:55', 2, 0),
(115, 20, 1, 'aaaa', '2025-11-02 10:16:03', 3, 0),
(116, 1, 1, 'aaaa', '2025-11-02 10:16:08', 2, 0),
(117, 2, 1, 'amigo', '2025-11-02 20:26:10', 1, 0),
(118, 20, 1, 'amanda', '2025-11-02 20:26:21', 3, 0),
(119, 2, 1, 'oi', '2025-11-02 23:10:27', 1, 0),
(120, 20, 1, 'aaa', '2025-11-02 23:25:23', 3, 0),
(121, 2, 1, 'alo', '2025-11-03 10:39:26', 1, 0),
(122, 2, 1, 'ooii', '2025-11-03 10:47:07', 1, 0),
(123, 2, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-03 10:47:42', 1, 0),
(124, 2, 1, 'aaaaa', '2025-11-03 10:49:02', 1, 0),
(125, 1, 1, 'a', '2025-11-03 10:49:22', 2, 0),
(126, 1, 1, 'iu', '2025-11-03 10:55:29', 2, 0),
(127, 1, 1, 'alo', '2025-11-03 10:55:51', 2, 0),
(128, 4, 1, 'e', '2025-11-03 11:00:25', 3, 0),
(129, 4, 1, 'oi', '2025-11-03 11:07:09', 3, 0),
(130, 4, 1, 'a', '2025-11-03 11:07:14', 3, 0),
(131, 4, 1, 'a', '2025-11-03 11:07:20', 3, 0),
(132, 4, 1, 'a', '2025-11-03 11:07:20', 3, 0),
(133, 2, 1, 'a', '2025-11-03 11:07:27', 1, 0),
(134, 4, 1, 'aaaa', '2025-11-03 11:09:14', 3, 0),
(135, 1, 1, 'a', '2025-11-03 11:09:19', 2, 0),
(136, 4, 1, 'aaa', '2025-11-03 11:09:22', 3, 0),
(137, 4, 1, 'a', '2025-11-03 11:09:27', 3, 0),
(138, 4, 1, 'a', '2025-11-03 11:10:17', 3, 0),
(139, 4, 1, 'a', '2025-11-03 11:10:21', 3, 0),
(140, 4, 1, 'a', '2025-11-03 11:10:48', 3, 0),
(141, 4, 1, 'a', '2025-11-03 11:10:52', 3, 0),
(142, 4, 1, 'aa', '2025-11-03 11:10:57', 3, 0),
(143, 4, 1, 'a', '2025-11-03 11:11:07', 3, 0),
(144, 4, 1, 'a', '2025-11-03 11:11:19', 3, 0),
(145, 4, 1, 'a', '2025-11-03 11:12:48', 3, 0),
(146, 4, 1, 'a', '2025-11-03 11:12:59', 3, 0),
(147, 4, 1, 'a', '2025-11-03 11:13:12', 3, 0),
(148, 4, 1, 'aaa', '2025-11-03 11:13:19', 3, 0),
(149, 4, 1, 'aa', '2025-11-03 11:13:19', 3, 0),
(150, 4, 1, 'aa', '2025-11-03 11:13:20', 3, 0),
(151, 4, 1, 'a', '2025-11-03 11:13:20', 3, 0),
(152, 4, 1, 'aaa', '2025-11-03 11:13:36', 3, 0),
(153, 2, 1, 'aaa', '2025-11-03 11:13:46', 1, 0),
(154, 4, 1, 'a', '2025-11-03 11:14:27', 3, 0),
(155, 4, 1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-11-03 11:39:06', 3, 0),
(156, 4, 1, 'aaaa', '2025-11-03 11:53:34', 3, 0),
(157, 4, 1, 'aaaa', '2025-11-03 11:54:28', 3, 0),
(158, 4, 1, 'aaaa', '2025-11-03 11:55:17', 3, 0),
(159, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:27', 3, 0),
(160, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3, 0),
(161, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3, 0),
(162, 4, 1, 'aloooooooooooo', '2025-11-03 12:01:34', 3, 0),
(163, 4, 1, 'aaaa', '2025-11-03 12:04:26', 3, 0),
(164, 4, 1, 'aaaa', '2025-11-03 12:04:43', 3, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_solicitacao` int(11) NOT NULL,
  `mensagem` text,
  `visualizado` tinyint(1) DEFAULT '0',
  `data` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `id_usuario`, `id_solicitacao`, `mensagem`, `visualizado`, `data`) VALUES
(10, 3, 20, 'Seu pedido foi recusado pela prestadora.', 1, '2025-11-19 18:06:41'),
(11, 3, 19, 'Seu pedido foi aceito pela prestadora.', 1, '2025-11-19 18:06:49'),
(12, 3, 15, 'Seu pedido foi aceito pela prestadora.', 1, '2025-11-19 18:35:34'),
(13, 3, 18, 'Sua solicitaÃ§Ã£o foi recusado pela prestadora.', 1, '2025-11-20 15:27:05'),
(14, 3, 21, 'Seu pedido foi recusado pela prestadora.', 1, '2025-11-20 15:31:03'),
(15, 4, 22, 'Seu pedido foi aceito pela prestadora.', 1, '2025-11-22 16:35:45');

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
(1, 'Flavia Gomes', 'flavia@gmail.com', 'flavia', '2025-11-23 18:26:29', '../ImgPerfilPrestadoras/perfil_1.jpg', '../ImgBannersPrestadoras/banner1_id_1.jpg', '../ImgBannersPrestadoras/banner2_id_1.jpg', '../ImgBannersPrestadoras/banner3_id_1.jpg', 'Teste', '12345', 'teste@gmail.com', 'Rua Dirceu', 'http://localhost/Programacao_TCC_Avena/html/EdicaoPerfil.php', 'teste@gmail.com', 'testetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetestetesteteste', 'etestetesteaaa aaaaa\naaaaaaaaaaaaaaaaaaaa aaaaaaaaaa', 1),
(2, 'Mulittle', 'muriloalves.fonseca08@gmail.com', 'EFlSs', '2025-11-12 14:33:07', '../ImgPerfilPrestadoras/perfil_2.png', '../ImgBannersPrestadoras/banner1_id_2.jpg', '../ImgBannersPrestadoras/banner2_id_2.webp', '../ImgBannersPrestadoras/banner3_id_2.png', 'Mulittle', '(11)9722075060', 'muriloalves.fonseca08@gmail.com', 'Sp - Ferraz de Vasconcelos', 'http://FoiBanidoessapembra.com', 'murilimodeiocolica@gmail', 'Trabalho muito trabalho trabalho eu muito trabalho dimais', 'Trabalho muito trabalho trabalho eu muito trabalho dimais', 1),
(22, 'awdawd', 'awd@awd', '123', '2025-11-11 15:58:57', '../ImgPerfilPrestadoras/perfil_22.jpg', '../ImgBannersPrestadoras/banner1_id_22.jpg', '../ImgBannersPrestadoras/banner2_id_22.png', '../ImgBannersPrestadoras/banner3_id_22.jpg', 'Testeedicao', '1211111111111', 'testeedicao@gmail.com', 'lalalalalala', 'http://localhost/Programacao_TCC_Avena/html/EdicaoPerfil.php', '@cachorrofeiodapreula', 'awdawdawd', 'awdawdw', 1),
(23, 'Geisa', 'geisaAM@gmail.com', 'geisa', '2025-11-22 19:29:45', '../ImgPerfilPrestadoras/perfil_23.jpg', '../ImgBannersPrestadoras/banner1_id_23.jpg', '../ImgBannersPrestadoras/banner2_id_23.jpg', '../ImgBannersPrestadoras/banner3_id_23.jpg', 'Geisa Alves Macedo', '(11) 972207560', 'geisaAM@gmail.com', 'Rua Ruthemberg Alvarez de Alcantara', 'http://localhost/Programacao_TCC_Avena/html/AdicaoServicoPrestadora.php', '@geisaestetica', 'FaÃ§o serviÃ§os de estÃ©tica no geral. Lorem ipsum daiso Kar tine donsapir lomano taricader thead for malu', 'Unha: 10R$, Cabelo: 20$, PÃ©s: 30R$', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `id` int(11) NOT NULL,
  `id_contratante` int(11) NOT NULL,
  `id_prestadora` int(11) NOT NULL,
  `data_solicitacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pendente','aceito','recusado','concluido') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `solicitacoes`
--

INSERT INTO `solicitacoes` (`id`, `id_contratante`, `id_prestadora`, `data_solicitacao`, `status`) VALUES
(15, 3, 2, '2025-11-19 13:11:05', 'aceito'),
(18, 3, 1, '2025-11-19 13:27:24', 'recusado'),
(19, 3, 2, '2025-11-19 13:29:10', 'aceito'),
(20, 3, 2, '2025-11-19 13:30:46', 'recusado'),
(21, 3, 2, '2025-11-20 15:29:17', 'recusado'),
(22, 4, 23, '2025-11-22 16:35:01', 'aceito');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agenda`
<<<<<<< HEAD
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `chat`
=======
>>>>>>> master
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`);

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
<<<<<<< HEAD
-- Índices para tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `id_chat` (`id_chat`),
  ADD KEY `id_para` (`id_para`),
  ADD KEY `idx_unread` (`id_chat`,`id_para`,`lido`);

--
=======
>>>>>>> master
-- Índices para tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_solicitacao` (`id_solicitacao`),
  ADD KEY `notificacoes_ibfk_1` (`id_usuario`);

--
-- Índices para tabela `prestadora`
--
ALTER TABLE `prestadora`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_contratante` (`id_contratante`),
  ADD KEY `id_prestadora` (`id_prestadora`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agenda`
<<<<<<< HEAD
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `chat`
=======
>>>>>>> master
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
<<<<<<< HEAD
-- AUTO_INCREMENT de tabela `mensagem`
--
ALTER TABLE `mensagem`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
=======
>>>>>>> master
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `prestadora`
--
ALTER TABLE `prestadora`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restrições para despejos de tabelas
--

--
<<<<<<< HEAD
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

--
=======
>>>>>>> master
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `cliente` (`id_usuario`),
  ADD CONSTRAINT `notificacoes_ibfk_2` FOREIGN KEY (`id_solicitacao`) REFERENCES `solicitacoes` (`id`);

--
-- Limitadores para a tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `solicitacoes_ibfk_1` FOREIGN KEY (`id_contratante`) REFERENCES `cliente` (`id_usuario`),
  ADD CONSTRAINT `solicitacoes_ibfk_2` FOREIGN KEY (`id_prestadora`) REFERENCES `prestadora` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
