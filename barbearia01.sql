-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/06/2024 às 22:08
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `barbearia01`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `funcionario` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `data_lancamento` date NOT NULL,
  `usuario` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nome` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`) VALUES
(1, 'Administrador'),
(2, 'recepcionista'),
(3, 'contador'),
(4, 'BARBEIRO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_produtos`
--

CREATE TABLE `categoria_produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria_produtos`
--

INSERT INTO `categoria_produtos` (`id`, `nome`) VALUES
(5, 'CREMES'),
(6, 'SHAMPOO'),
(7, 'TINTA'),
(8, 'CERVEJA'),
(9, 'REFRIGERANTE');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_servicos`
--

CREATE TABLE `categoria_servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria_servicos`
--

INSERT INTO `categoria_servicos` (`id`, `nome`) VALUES
(5, 'CORTE DE CABELO'),
(6, 'SOBRANCELHA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `data_cadastro` date NOT NULL,
  `data_nascimento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `telefone`, `endereco`, `data_cadastro`, `data_nascimento`) VALUES
(1, 'cliente 1', '(96) 99167-6889', 'fonte nova', '2024-05-18', '2024-05-03'),
(4, 'cliente 2', '(96) 99154-5657', 'Rua o', '2024-05-21', '2024-05-09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone_fixo` varchar(20) DEFAULT NULL,
  `telefone_whatsapp` varchar(20) NOT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `logo_relatorio` varchar(100) DEFAULT NULL,
  `tipo_relatorio` varchar(10) DEFAULT NULL,
  `tipo_comissao` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`id`, `nome`, `email`, `telefone_fixo`, `telefone_whatsapp`, `endereco`, `logo`, `icone`, `logo_relatorio`, `tipo_relatorio`, `tipo_comissao`) VALUES
(1, 'Barbearia Lima', 'nagatabrisa.05@gmail.com', '', '(96) 99182-7077', 'proiyverbio 987', 'logo.png', 'favicon.ico', 'logo_relatorio.jpg', 'PDF', 'R$');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dias`
--

CREATE TABLE `dias` (
  `id` int(11) NOT NULL,
  `dia` varchar(25) NOT NULL,
  `funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `dias`
--

INSERT INTO `dias` (`id`, `dia`, `funcionario`) VALUES
(1, 'Segunda-Feira', 4),
(2, 'Quarta-Feira', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `usuario` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `data_cadastro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `telefone`, `endereco`, `data_cadastro`) VALUES
(1, 'MeHaaaff', '(96) 99167-8698', 'trem', '2024-05-25'),
(2, 'Barçamull', '(96) 99188-6098', 'trem 444', '2024-05-30'),
(3, 'RealMadruga', '(96) 99155-6070', 'Urucu 333', '2024-05-30'),
(4, 'BayernMunchen', '(96) 99112-1314', 'Hospitalidade 555', '2024-05-30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `horario` time NOT NULL,
  `funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios`
--

INSERT INTO `horarios` (`id`, `horario`, `funcionario`) VALUES
(5, '17:00:00', 4),
(6, '21:00:00', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagar`
--

CREATE TABLE `pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `tipo` varchar(35) DEFAULT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data_lancamento` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date NOT NULL,
  `usuario_lancou` int(11) NOT NULL,
  `usuario_baixa` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `pessoa` int(11) NOT NULL,
  `pago` varchar(5) NOT NULL,
  `produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pagar`
--

INSERT INTO `pagar` (`id`, `descricao`, `tipo`, `valor`, `data_lancamento`, `data_vencimento`, `data_pagamento`, `usuario_lancou`, `usuario_baixa`, `foto`, `pessoa`, `pago`, `produto`, `quantidade`) VALUES
(9, 'PAGAMENTO X', 'Conta', 260.00, '2024-05-30', '2024-05-30', '0000-00-00', 1, 0, 'sem-foto.jpg', 0, 'Não', 0, 0),
(10, 'PAGAMENTO X', 'Conta', 260.00, '2024-06-01', '2024-06-04', '2024-06-03', 1, 1, '01-06-2024-19-09-35-Pdf01.png', 0, 'Sim', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` int(11) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `categoria` int(11) NOT NULL,
  `valor_compra` decimal(8,2) NOT NULL,
  `valor_venda` decimal(8,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `nivel_estoque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `receber`
--

CREATE TABLE `receber` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `tipo` varchar(35) DEFAULT NULL,
  `valor` decimal(8,2) NOT NULL,
  `data_lancamento` date NOT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date NOT NULL,
  `usuario_lancou` int(11) NOT NULL,
  `usuario_baixa` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `pessoa` int(11) NOT NULL,
  `pago` varchar(5) NOT NULL,
  `produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `receber`
--

INSERT INTO `receber` (`id`, `descricao`, `tipo`, `valor`, `data_lancamento`, `data_vencimento`, `data_pagamento`, `usuario_lancou`, `usuario_baixa`, `foto`, `pessoa`, `pago`, `produto`, `quantidade`) VALUES
(4, 'PAGAMENTO X', 'Conta', 260.00, '2024-05-30', '2024-05-30', '0000-00-00', 1, 0, 'sem-foto.jpg', 0, 'Não', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `saidas`
--

CREATE TABLE `saidas` (
  `id` int(11) NOT NULL,
  `produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `usuario` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `categoria` int(11) NOT NULL,
  `valor` decimal(8,2) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `ativo` varchar(5) NOT NULL,
  `comissao` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios01`
--

CREATE TABLE `usuarios01` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `senha` varchar(25) NOT NULL,
  `senha_crip` varchar(100) NOT NULL,
  `nivel` varchar(35) NOT NULL,
  `data` date NOT NULL,
  `ativo` varchar(5) NOT NULL,
  `endereco` varchar(20) DEFAULT NULL,
  `telefone` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `atendimento` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios01`
--

INSERT INTO `usuarios01` (`id`, `nome`, `email`, `cpf`, `senha`, `senha_crip`, `nivel`, `data`, `ativo`, `endereco`, `telefone`, `foto`, `atendimento`) VALUES
(1, 'NEDVED', 'nagatabrisa.05@gmail.com', '213.213.434-32', '123', '202cb962ac59075b964b07152d234b70', 'Administrador', '2024-05-01', 'Sim', 'Rua H', '(96) 99188-9567', '07-05-2024-20-51-48-yt.jpg', 'Sim'),
(3, 'MESSI ', 'messisupremo@gmail.com', '123.098.555-67', '123', '202cb962ac59075b964b07152d234b70', 'contador', '2024-06-01', 'Sim', 'Almeida ferreira 123', '(96) 99133-9900', '01-06-2024-16-17-31-fotoperfil.jpg', ''),
(4, 'NEYMAR', 'neymarlokoloko@gmail.com', '428.500.340-77', '123', '202cb962ac59075b964b07152d234b70', 'recepcionista', '2024-06-01', 'Sim', 'alba silva 123', '(96) 99155-6246', '01-06-2024-16-20-59-fotoperfil01.jpg', ''),
(5, 'BARBEIRA alice', 'aliceglubalbal@gmail.com', '276.894.553-44', '123', '202cb962ac59075b964b07152d234b70', 'BARBEIRO', '2024-06-01', 'Sim', 'RUA franca 246', '(96) 99133-4886', '01-06-2024-16-38-01-barbeira01.jpg', ''),
(6, 'BARBEIRO japim', 'japimcod@gmail.com', '444.555.666-77', '123', '202cb962ac59075b964b07152d234b70', 'BARBEIRO', '2024-06-04', 'Sim', 'RUA X', '(96) 99157-5659', 'sem-foto.jpg', 'Sim');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categoria_produtos`
--
ALTER TABLE `categoria_produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categoria_servicos`
--
ALTER TABLE `categoria_servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dias`
--
ALTER TABLE `dias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pagar`
--
ALTER TABLE `pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `receber`
--
ALTER TABLE `receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `saidas`
--
ALTER TABLE `saidas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios01`
--
ALTER TABLE `usuarios01`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `categoria_produtos`
--
ALTER TABLE `categoria_produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `categoria_servicos`
--
ALTER TABLE `categoria_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `dias`
--
ALTER TABLE `dias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pagar`
--
ALTER TABLE `pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `receber`
--
ALTER TABLE `receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `saidas`
--
ALTER TABLE `saidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios01`
--
ALTER TABLE `usuarios01`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
