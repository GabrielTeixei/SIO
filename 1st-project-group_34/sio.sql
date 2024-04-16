-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03-Nov-2023 às 19:56
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sio`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `carts`
--

INSERT INTO `carts` (`id`, `product_id`, `quantidade`, `cart_id`) VALUES
(60, 1, 1, 23),
(70, 1, 1, 24),
(98, 1, 5, 30);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart_user`
--

CREATE TABLE `cart_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cart_user`
--

INSERT INTO `cart_user` (`id`, `user_id`, `total`) VALUES
(22, 2, 244.43),
(23, 1, 0.00),
(25, 8, -179.96),
(26, 12, 0.00),
(27, 13, 0.00),
(28, 15, 0.00),
(29, 17, 0.00),
(30, 0, 0.00),
(31, 9, 0.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `descr` text NOT NULL,
  `stock` int(11) NOT NULL,
  `img` varchar(200) NOT NULL,
  `category` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `descr`, `stock`, `img`, `category`) VALUES
(1, 'Caneca', 2.99, 'Uma caneca para todos os informáticos possam desfrutar do seu sagrado café.', 75, '/detishop/images/mug.png', 'casa'),
(2, 'hoodie', 27.90, 'O vestuário mais confortável para as longas noites de coding.', 13, '/detishop/images/ola.php', 'roupa'),
(3, 'camisola', 14.99, 'A t-shirt perfeita para mostrares ao mundo o teu orgulho de ser programador.', 157, '/detishop/images/tshirt.png', 'roupa'),
(4, 'Mala para portatil', 20.00, 'Porque a tua ferramenta de trabalho também precisa de um miminho. ', 45, '/detishop/images/laptop_bag.png', 'tecnologia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` decimal(5,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `date`, `total`, `quantity`) VALUES
(21, 2, '2023-10-23 11:40:56', 102.50, 0),
(22, 2, '2023-10-23 11:43:10', 39.98, 0),
(23, 2, '2023-10-23 15:37:56', 117.43, 12),
(24, 2, '2023-10-25 15:03:25', -9.60, -14),
(25, 2, '2023-10-25 15:03:47', -418.50, -16),
(26, 2, '2023-10-26 00:00:43', 223.20, 8),
(27, 8, '2023-10-27 17:15:23', 197.80, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `purchase_product`
--

CREATE TABLE `purchase_product` (
  `id_pp` int(11) NOT NULL,
  `id_purchase` int(11) NOT NULL,
  `id_prduct` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `purchase_product`
--

INSERT INTO `purchase_product` (`id_pp`, `id_purchase`, `id_prduct`, `quantidade`) VALUES
(1, 22, 1, 4),
(2, 22, 3, 2),
(3, 23, 1, 5),
(4, 23, 3, 7),
(5, 24, 1, -15),
(6, 24, 2, 1),
(7, 25, 2, -16),
(8, 26, 2, 8),
(9, 27, 2, 7),
(10, 27, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `reviews`
--

INSERT INTO `reviews` (`id`, `id_produto`, `id_user`, `comment`, `rating`) VALUES
(32, 4, 2, 'ddd', 3),
(48, 2, 2, 'ola', 3),
(55, 3, 2, 'a', 1),
(60, 1, 2, 'recomendo', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `pass` varchar(256) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `nome`, `email`, `pass`, `admin`) VALUES
(8, 'admin', 'admin@detiua.pt', 'admin', 1),
(9, 'teste', 'suport@cx.com', '1234', 0),
(13, 'joao', 'oo@ff.pt', 'ouououo', 0),
(15, 'antonia', 'antaa@alo.py', 'passw0rd', 0),
(16, 'ana maria', 'anam@ff.pt', 'benfica', 0),
(17, 'marcelo', 'rm@v.t', 'b8', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cart_user`
--
ALTER TABLE `cart_user`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `purchase_product`
--
ALTER TABLE `purchase_product`
  ADD PRIMARY KEY (`id_pp`);

--
-- Índices para tabela `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `index_mail` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de tabela `cart_user`
--
ALTER TABLE `cart_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `purchase_product`
--
ALTER TABLE `purchase_product`
  MODIFY `id_pp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
