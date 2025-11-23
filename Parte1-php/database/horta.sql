-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 07:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `horta`
--

-- --------------------------------------------------------

--
-- Table structure for table `alimentos`
--

CREATE TABLE `alimentos` (
  `nome_alimento` varchar(50) DEFAULT NULL,
  `nome_cientifico` varchar(100) DEFAULT NULL,
  `tipo_alimento` varchar(20) DEFAULT NULL,
  `secao_circular` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alimentos`
--

INSERT INTO `alimentos` (`nome_alimento`, `nome_cientifico`, `tipo_alimento`, `secao_circular`) VALUES
('Alface', 'Lactuca sativa', 'Verdura', 'A'),
('Cenoura', 'Daucus carota', 'Legume', 'B'),
('Tomate', 'Solanum lycopersicum', 'Fruto', 'C'),
('Cebolinha', 'Allium fistulosum', 'Tempero', 'A'),
('Salsinha', 'Petroselinum crispum', 'Tempero', 'A'),
('Espinafre', 'Spinacia oleracea', 'Verdura', 'D'),
('Beterraba', 'Beta vulgaris', 'Legume', 'B'),
('Manjericão', 'Ocimum basilicum', 'Tempero', 'C'),
('Rúcula', 'Eruca sativa', 'Verdura', 'D'),
('Pimentão', 'Capsicum annuum', 'Fruto', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `nivel` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data for table `login`
--

INSERT INTO `login` (`usuario`, `senha`, `nivel`) VALUES
('allycorreto', '$2y$10$5zYW02nQOvMXRq6awtHtueGdBY5rXOX1a84eFzo1Zs83sE6gxA8QW', 2),
('allyadm', '$2y$10$uk5055/tzSladv1CWiKouedveoR.LH88DcKIkdbY2TZ/QiKKrHpPq', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_arquivo` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `secao_horta` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;