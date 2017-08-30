-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 19-Ago-2016 às 15:34
-- Versão do servidor: 5.5.50-0+deb8u1
-- PHP Version: 5.6.24-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Syslog`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_log`
--

CREATE TABLE IF NOT EXISTS `usuario_log` (
`idusuariolog` int(11) NOT NULL,
  `usuariolog` varchar(20) NOT NULL,
  `ipusuariolog` varchar(20) NOT NULL,
  `obs` text
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario_log`
--

INSERT INTO `usuario_log` (`idusuariolog`, `usuariolog`, `ipusuariolog`, `obs`) VALUES
(2, 'JoÃ£o', '192.168.6.253', 'Atendente'),
(4, 'Cleber', '192.168.6.253', 'Gerente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usuario_log`
--
ALTER TABLE `usuario_log`
 ADD PRIMARY KEY (`idusuariolog`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usuario_log`
--
ALTER TABLE `usuario_log`
MODIFY `idusuariolog` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
