-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2023 às 02:30
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `codFavorito` int(11) NOT NULL,
  `codPostagem` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`codFavorito`, `codPostagem`, `codUsuario`) VALUES
(32, 69, 4),
(33, 70, 4),
(34, 70, 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ingressos`
--

CREATE TABLE `ingressos` (
  `codIngresso` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL,
  `codReserva` int(11) NOT NULL,
  `Tipo` varchar(20) NOT NULL,
  `ValorUnt` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ingressos`
--

INSERT INTO `ingressos` (`codIngresso`, `codUsuario`, `codReserva`, `Tipo`, `ValorUnt`) VALUES
(284, 4, 138, 'PADRÃO', 8.99),
(285, 4, 139, 'PADRÃO', 30),
(286, 4, 140, 'PADRÃO', 50),
(287, 4, 141, 'PADRÃO', 30),
(288, 4, 142, 'PADRÃO', 30),
(289, 4, 142, 'PADRÃO', 30),
(290, 4, 142, 'PADRÃO', 30),
(291, 4, 142, 'PADRÃO', 30);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `codPostagem` int(11) NOT NULL,
  `Titulo` varchar(120) NOT NULL,
  `Conteudo` varchar(5000) NOT NULL,
  `TotalIngressos` int(11) NOT NULL DEFAULT 0,
  `qtdIngressos` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Horario` time NOT NULL,
  `Local` varchar(40) NOT NULL,
  `Imagem` mediumtext NOT NULL DEFAULT '\'imagens/imagem.png',
  `Banner` mediumtext NOT NULL,
  `Valor` double NOT NULL,
  `Classificacao` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`codPostagem`, `Titulo`, `Conteudo`, `TotalIngressos`, `qtdIngressos`, `Data`, `Horario`, `Local`, `Imagem`, `Banner`, `Valor`, `Classificacao`) VALUES
(51, 'Manifestações do Inconsciente - Espetáculo de Dança Contemporânea', 'A escola municipal de cultura artística (EMCA), da cidade de Garça/SP, apresenta pela primeira vez, o espetáculo de dança contemporânea. <br><br>\r\n\r\n A dança contemporânea se trata uma forma de expressão artística que se desenvolveu a partir do final do século XX como uma evolução da dança moderna. Ela é caracterizada por uma abordagem mais livre e experimental em comparação com estilos de dança mais tradicionais.<br><br>\r\n \r\n18 de novembro, no teatro municipal, ás 19:00, contamos com sua presença.<br><br>', 400, 400, '2023-11-18', '19:00:00', 'Teatro Municipal de Garça', 'imagens/EMCA.jpeg', '', 10, 'L'),
(57, 'Cineract: Barbie', 'O Cineract está de volta após a pandemia de 2020, trazendo uma variedade de filmes que acabarem de sair das telas, confira a sessão de domingo (14:00):<br><br>\r\n\r\nDepois de ser expulsa da Barbieland por ser uma boneca de aparência menos do que perfeita, Barbie parte para o mundo humano em busca da verdadeira felicidade.<br><br>\r\n\r\nComo as edições anteriores, o Cineract será no Teatro Municipal de nossa cidade, ao lado da Biblioteca Municipal.<br><br>\r\n\r\n<b>Leve 1kg que alimento não perecível e garanta o seu ingresso na Imobiliária Marques.</b>', 394, 394, '2023-09-24', '14:00:00', 'Teatro Municipal', 'imagens/barbie.png', 'uploads/barbie_v.png', 0, '+12'),
(59, 'Cineract: A Pequena Sereia: Live Action', 'O Cineract está de volta após a pandemia de 2020, trazendo uma variedade de filmes que acabarem de sair das telas, confira a sessão de sábado (15:00): <br><br>\r\n\r\nUma jovem sereia faz um acordo com uma bruxa do mar para trocar sua bela voz por pernas humanas para que possa descobrir o mundo acima da água e impressionar um príncipe. <br><br>\r\n\r\nComo as edições anteriores, o Cineract será no Teatro Municipal de nossa cidade, ao lado da Biblioteca Municipal.<br><br>\r\n\r\n<b>Leve 1kg que alimento não perecível e garanta o seu ingresso na Imobiliária Marques.</b><br><br>', 392, 392, '2023-09-23', '17:00:00', 'Teatro Municipal', 'imagens/ariel.png', 'uploads/pequena_sereia_v.jpg', 0, 'L'),
(63, 'Baile do Havaí - Summer Mix (VIP)', 'A festa \"Baile do Havaí\" da Mix Produções está de volta, irá acontecer no dia 18/11/2023 (sábado), com seu início às 20:30, localizada no Garça Tênis Clube (área externa ao redor das piscinas).<br><br>\r\n\r\nO evento irá conter 3 atrações incríveis: DJ Bia Zanoni, Jean Castro e Will Rise<br><br>\r\n\r\nPost da Área VIP: R$50,00<br><br>\r\n\r\nÉ proibido a entrada de menores de 18 anos.<br><br>\r\n\r\nNão fique de fora dessa!', 499, 499, '2023-11-18', '20:30:00', 'Garça Tênis Clube', 'imagens/havaifestavip.png', 'a', 50, '+18'),
(64, 'Baile do Havaí - Summer Mix (PISTA)', 'A festa \"Baile do Havaí\" da Mix Produções está de volta, irá acontecer no dia 18/11/2023 (sábado), com seu início às 20:30, localizada no Garça Tênis Clube (área externa ao redor das piscinas).<br><br>\r\n\r\nO evento irá conter 3 atrações incríveis: DJ Bia Zanoni, Jean Castro e Will Rise <br><br>\r\n\r\nPost da PISTA: R$30,00<br><br>\r\n\r\nÉ proibido a entrada de menores de 18 anos.<br><br>\r\n\r\nNão fique de fora dessa!<br><br>', 494, 494, '2023-11-18', '20:30:00', 'Garça Tênis Clube', 'imagens/havaifestapista.png', 'a', 30, '+18'),
(66, 'Oktober Mix - 2ª Edição (VIP)', 'A festa \"Oktober Mix\" da Mix Produções está de volta, irá acontecer no dia 21/10/2023 (sábado), com seu início às 20:00, localizada na AABB Garça.<br><br>\r\n\r\nO evento irá conter 3 atrações incríveis: Torvi, Danilo Caparroz e DJ Vitão.<br><br>\r\n\r\nPost do VIP: R$50,00<br><br>\r\n\r\nÉ proibido a entrada de menores de 18 anos.<br><br>\r\n\r\nNão fique de fora dessa!<br><br>', 298, 297, '2023-10-18', '20:00:00', 'AABB Garça', 'imagens/vipoktober.jpeg', 'a', 50, '+18'),
(67, 'Oktober Mix - 2ª Edição (PISTA)', 'A festa \"Oktober Mix\" da Mix Produções está de volta, irá acontecer no dia 21/10/2023 (sábado), com seu início às 20:00, localizada na AABB Garça.<br><br>\r\n\r\nO evento irá conter 3 atrações incríveis: Torvi, Danilo Caparroz e DJ Vitão.<br><br>\r\n\r\nPost do VIP: R$50,00<br><br>\r\n\r\nÉ proibido a entrada de menores de 18 anos.<br><br>\r\n\r\nNão fique de fora dessa!<br><br>', 500, 497, '2023-10-18', '20:00:00', 'AABB Garça', 'imagens/pistaoktober.jpeg', 'a', 30, '+18'),
(68, 'ODE: Espetáculo de Ballet Clássico Juvenil', 'Escola Municipal de Cultura Artística \"Amélio Naná Zancopé\" apresenta o Espetáculo de Ballet Clássico Juvenil e Contemporâneo:<br><br>\r\n\r\nODE: \"Um espetáculo que homenageia em forma de dança, algumas das coreografias mais lindas que já passaram por nosso palco\".<br><br>\r\n\r\nDia 18 de novembro de 2023 às 19h no Teatro Municipal<br><br>\r\n\r\nIngresso R$10,00 (à venda na secretaria da EMCA a partir do dia 13/11).', 400, 400, '2023-11-18', '19:00:00', 'Teatro Municipal', 'imagens/ballet.jpeg', 'imagens/ballet', 10, 'L'),
(69, 'Cerejeiras Festival 2024', 'O Cerejeiras Festival é um evento de grande importância turística e econômica do município de Garça. São 4 dias de festa, contendo atrações todos os dias, barracas com as mais deliciosas e diversas comidas, parque de diversões e muita arte.<br><br>\r\n\r\nContamos com a sua presença no ano de 2024, atrações e dias ainda não divulgados.<br><br>\r\n\r\n', 10000, 10000, '2024-06-18', '12:00:00', 'Lago Municipal de Garça', 'imagens/cerejeiras.jpeg', 'imagens/cerejeiras.jpeg', 0, 'L'),
(70, 'TecnoCafé 2024', 'A Feira Regional de Tecnologia Cafeeira acontece em Garça nos dias 05 a 08 de Outubro de 2023 em sua segunda edição, trazemos novidades tecnológicas para o agronegócio.', 10000, 10000, '2024-10-03', '12:00:00', 'Lago Municipal de Garça', 'imagens/festacafe.jpeg', 'imagens/festacafe.jpeg', 0, 'L'),
(76, 'MotoRock Festival - 2024', 'Um novo evento surge em Garça! Em abril teremos a 5ª edição do MOTO ROCK FESTIVAL, a segunda a ser realizada na cidade.<br><br>\r\n\r\nMais uma vez parceria entre Prefeitura Municipal, através da Seture - secretaria de turismo e eventos - e Consebs Lago - conselho de segurança e turismo - irá funcionar. O Consebs é quem realizará este evento beneficente com total apoio da Prefeitura. Toda a renda será revertiva à casa de apoio de Garça aos pacientes com câncer em tratamento na cidade de Jaú.<br><br>\r\n\r\nO evento consta com o desfile de motocicletas de Garça e região, parque de diversões e diversas atrações que ainda serão divulgadas.\r\n\r\nA entrada será franca. Aguarde novas informações sobre esta grande para o mês de abril de 2024 em Garça.', 10000, 10000, '2023-04-12', '18:00:00', 'Lago Municipal Prof J. K. Wiliams', 'imagens/motorock.jpeg', 'imagens/motorock.jpeg', 0, 'L'),
(77, 'I EMPREENDETEC', 'Venha participar do I Empreendetec, evento que mostra o resultado dos trabalhos interdisciplinares desenvolvidos pelos alunos em 2023, no projeto \"Empreendedorismo e Inovação\".<br><br>\r\n\r\nO evento acontecerá na ETECMAM, no dia 26 de agosto de 2023, a partir das 9hrs até às 11hrs.<br><br>\r\n\r\nContamos com a sua presença.', 0, 0, '2023-08-26', '09:00:00', 'ETEC Monsenhor Antônio Magliano', 'imagens/1empreendetec.jpeg', 'imagens/1empreendetec.jpeg', 0, 'L'),
(78, 'II EMPREENDETEC', 'O II Empreendetec acontecerá no dia 21 de outubro de 2023, o evento que tem o objetivo de alunos venderem seu produto para diversas pessoas, assim aprendendo mais sobre o que é empreendedorismo e sua importância, venha apreciar nossa escola.<br><br>\r\n\r\nDas 10:00 até 12:00, na praça em frente a Igreja Matriz (ao lado da sorveteria Friskare e lojas Pernambucanas). Contamos com sua presença.', 0, 0, '2023-10-21', '10:00:00', 'Praça em frente a Matriz', 'imagens/2empreendetec.jpeg', 'imagens/2empreendetec.jpeg', 0, 'L'),
(82, 'Espetáculo de música ', 'Esse final de semana o EMCA terá o Espetáculo de Música \"Formandos\" Dia 25 de novembro às 19h no Teatro Municipal.<br><br>\r\nIngressos no valor de R$ 10,00, já à venda na secretaria da EMCA e na plataforma Divulgarça!<br><br>\r\n<b>Alunos de Música não pagam (só retirar a senha na secretaria da EMCA)</b>', 400, 400, '2023-11-25', '19:00:00', 'Teatro Municipal', 'imagens/jazz.jpeg', 'imagens/jazz.jpeg', 10, 'L');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `codReserva` int(11) NOT NULL,
  `codPostagem` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL,
  `Aprovado` varchar(30) NOT NULL DEFAULT 'AGUARDANDO PAGAMENTO',
  `Valor` double NOT NULL,
  `DataReserva` date NOT NULL,
  `HoraReserva` time NOT NULL,
  `Formato` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`codReserva`, `codPostagem`, `codUsuario`, `Aprovado`, `Valor`, `DataReserva`, `HoraReserva`, `Formato`) VALUES
(138, 59, 4, 'AGUARDANDO PAGAMENTO', 8.99, '2023-11-23', '12:05:20', ''),
(139, 67, 4, 'PAGAMENTO APROVADO', 30, '2023-11-23', '12:14:22', ''),
(140, 66, 4, 'PAGAMENTO RECUSADO', 50, '2023-11-23', '12:20:59', ''),
(141, 67, 4, 'AGUARDANDO PAGAMENTO', 30, '2023-11-23', '13:24:00', ''),
(142, 64, 4, 'PAGAMENTO APROVADO', 120, '2023-11-24', '01:07:12', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `codUsuario` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Senha` varchar(20) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Telefone` varchar(15) NOT NULL,
  `Admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`codUsuario`, `Nome`, `Senha`, `Email`, `Telefone`, `Admin`) VALUES
(4, 'Administrador', '1', 'admin@a', '12345678', 1),
(5, 'pedroso', '123', 'marcelo@a', '1234', 0),
(6, 'Marcelo', '27123113', 'pedrosom202@gmail.com', '14996798897', 0),
(8, 'teste', '1', 'rafael@a', '1', 0),
(9, 'Rafael', '1', 'bla@m', '123456789', 0),
(10, 'Maria Clara Ribeiro', '53832400', 'mariaclara53832400rp@gmail.com', '14981584742', 0),
(11, '.com', '1', 'lkdej@email', '1', 0),
(12, 'Maria Fernanda', 'mariafernanda987', 'mahfer3f@gmail.com', '14981024727', 0),
(13, '1', '1', 'a@a', '1', 0),
(14, 'Pedro Qualy', '1', 'oi@oi', '1', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`codFavorito`);

--
-- Índices de tabela `ingressos`
--
ALTER TABLE `ingressos`
  ADD PRIMARY KEY (`codIngresso`),
  ADD KEY `usuario2` (`codUsuario`),
  ADD KEY `reserva` (`codReserva`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`codPostagem`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`codReserva`),
  ADD KEY `usuario` (`codUsuario`),
  ADD KEY `postagem` (`codPostagem`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `codFavorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `ingressos`
--
ALTER TABLE `ingressos`
  MODIFY `codIngresso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `codPostagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `codReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `codUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `ingressos`
--
ALTER TABLE `ingressos`
  ADD CONSTRAINT `reserva` FOREIGN KEY (`codReserva`) REFERENCES `reservas` (`codReserva`),
  ADD CONSTRAINT `usuario2` FOREIGN KEY (`codUsuario`) REFERENCES `usuario` (`codUsuario`);

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `evento` FOREIGN KEY (`codPostagem`) REFERENCES `posts` (`codPostagem`),
  ADD CONSTRAINT `usuario` FOREIGN KEY (`codUsuario`) REFERENCES `usuario` (`codUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
