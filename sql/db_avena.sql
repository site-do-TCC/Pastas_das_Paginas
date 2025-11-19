-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 19-Nov-2025 às 17:12
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
(15, 3, 2, '2025-11-19 13:11:05', 'pendente'),
(18, 3, 1, '2025-11-19 13:27:24', 'pendente'),
(19, 3, 2, '2025-11-19 13:29:10', 'pendente'),
(20, 3, 2, '2025-11-19 13:30:46', 'pendente');

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
-- Índices para tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_solicitacao` (`id_solicitacao`);

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
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `prestadora`
--
ALTER TABLE `prestadora`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `prestadora` (`id_usuario`),
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
