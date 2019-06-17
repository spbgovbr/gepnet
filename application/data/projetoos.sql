-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 30/01/2012 às 09h44min
-- Versão do Servidor: 5.5.14
-- Versão do PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `projetoos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo_os`
--

CREATE TABLE IF NOT EXISTS `grupo_os` (
  `GRUPO_ID` int(11) NOT NULL,
  `GRUPO_NOME` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`GRUPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `os`
--

CREATE TABLE IF NOT EXISTS `os` (
  `OS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `OS_DESCRICAO` text COLLATE utf8_unicode_ci NOT NULL,
  `OS_OBS` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `OS_IMAGEM` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `OS_SOLUCAO` text COLLATE utf8_unicode_ci,
  `OS_DATA_INICIO` date NOT NULL,
  `OS_DATA_FIM` date DEFAULT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `GRUPO_ID` int(11) NOT NULL,
  PRIMARY KEY (`OS_ID`),
  KEY `USUARIO_ID` (`USUARIO_ID`),
  KEY `GRUPO_ID` (`GRUPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_usuario`
--

CREATE TABLE IF NOT EXISTS `perfil_usuario` (
  `PERFIL_ID` int(11) NOT NULL,
  `PERFIL_NOME` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`PERFIL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `USUARIO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO_MATRICULA` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_NOME` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_DATA_NASC` date NOT NULL,
  `USUARIO_SETOR` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_TELEFONE` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_EMAIL` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_LOGIN` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_SENHA` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_PERFIL` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO-OBS` text COLLATE utf8_unicode_ci NOT NULL,
  `USUARIO_DATA_INICIO` date NOT NULL,
  `USUARIO_DATA_FIM` date DEFAULT NULL,
  `PERFIL_ID` int(11) NOT NULL,
  PRIMARY KEY (`USUARIO_ID`),
  KEY `PERFIL_ID` (`PERFIL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `os`
--
ALTER TABLE `os`
  ADD CONSTRAINT `GRUPO_ID` FOREIGN KEY (`GRUPO_ID`) REFERENCES `grupo_os` (`GRUPO_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `USUARIO_ID` FOREIGN KEY (`USUARIO_ID`) REFERENCES `usuario` (`USUARIO_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `PERFIL_ID` FOREIGN KEY (`PERFIL_ID`) REFERENCES `perfil_usuario` (`PERFIL_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
