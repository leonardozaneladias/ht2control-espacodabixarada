/*
 Navicat Premium Data Transfer

 Source Server         : vagrant
 Source Server Type    : MySQL
 Source Server Version : 50712
 Source Host           : localhost
 Source Database       : espacodabixarada

 Target Server Type    : MySQL
 Target Server Version : 50712
 File Encoding         : utf-8

 Date: 11/28/2016 18:32:11 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `app_agenda`
-- ----------------------------
DROP TABLE IF EXISTS `app_agenda`;
CREATE TABLE `app_agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `hr_inicio` time DEFAULT NULL,
  `hr_pausa` time DEFAULT NULL,
  `hr_termino` time DEFAULT NULL,
  `domingo` tinyint(4) DEFAULT NULL,
  `segunda` tinyint(4) DEFAULT NULL,
  `terca` tinyint(4) DEFAULT NULL,
  `quarta` tinyint(4) DEFAULT NULL,
  `quinta` tinyint(4) DEFAULT NULL,
  `sexta` tinyint(4) DEFAULT NULL,
  `sabado` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `app_agenda`
-- ----------------------------
BEGIN;
INSERT INTO `app_agenda` VALUES ('1', '1', '09:00:00', '12:00:00', '18:00:00', '0', '1', '1', '1', '1', '1', '1', '1', '2016-10-01 23:52:10', '2016-10-01 23:53:00');
COMMIT;

-- ----------------------------
--  Table structure for `app_agendamentos`
-- ----------------------------
DROP TABLE IF EXISTS `app_agendamentos`;
CREATE TABLE `app_agendamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_id` int(11) DEFAULT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `tempo_servico` int(11) DEFAULT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `app_agendamentos`
-- ----------------------------
BEGIN;
INSERT INTO `app_agendamentos` VALUES ('1', '1', '1', '2016-10-02 13:00:00', '30', '1', '1', '2016-10-02 00:48:47', '2016-10-02 00:49:27'), ('2', '1', '1', '2016-10-02 14:00:00', '30', '1', '1', '2016-10-02 00:48:47', '2016-10-02 00:49:29');
COMMIT;

-- ----------------------------
--  Table structure for `app_animal`
-- ----------------------------
DROP TABLE IF EXISTS `app_animal`;
CREATE TABLE `app_animal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `animal_img` varchar(255) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `raca_id` int(11) DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `descricao` text,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `peso` float(10,2) DEFAULT NULL,
  `tipo_pelo` varchar(255) DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `app_animal`
-- ----------------------------
BEGIN;
INSERT INTO `app_animal` VALUES ('2', 'Animal de Teste 1.1', '', '2', '92', '1986-02-22', 'Este animal é alergico a novalgina', '1', '2016-11-28 18:40:59', '2016-11-28 18:56:20', '9.45', 'Longo', 'FEMEA');
COMMIT;

-- ----------------------------
--  Table structure for `app_categorias`
-- ----------------------------
DROP TABLE IF EXISTS `app_categorias`;
CREATE TABLE `app_categorias` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_nome` varchar(50) DEFAULT NULL,
  `categoria_img` varchar(255) DEFAULT NULL,
  `categoria_descricao` text,
  `tipoevento_cod` int(11) NOT NULL,
  `categoria_status` tinyint(1) DEFAULT NULL,
  `categoria_alias` int(11) DEFAULT '0',
  `date_create` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`categoria_id`),
  KEY `fk_app_categorias_app_tipoevento_1` (`tipoevento_cod`),
  CONSTRAINT `fk_app_categorias_app_tipoevento_1` FOREIGN KEY (`tipoevento_cod`) REFERENCES `app_tipoevento` (`tipoevento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `app_categorias`
-- ----------------------------
BEGIN;
INSERT INTO `app_categorias` VALUES ('92', 'Cocker Spaniel', 'uploads/images/2016/11/20161128182352.png', 'O cocker spaniel inglês é uma raça de cão de porte médio. Apesar do nome, seu surgimento deu-se na Espanha, local onde auxiliava caçadores em florestas ou pântanos, servindo como cão de aponte para caça de aves selvagens', '18', '1', '0', '2016-11-28 18:23:54', null);
COMMIT;

-- ----------------------------
--  Table structure for `app_cliente`
-- ----------------------------
DROP TABLE IF EXISTS `app_cliente`;
CREATE TABLE `app_cliente` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nome` varchar(50) DEFAULT NULL,
  `cliente_img` varchar(255) DEFAULT NULL,
  `cliente_telefone` varchar(255) DEFAULT NULL,
  `cliente_descricao` text,
  `cliente_email` varchar(255) DEFAULT NULL,
  `cliente_cpf` varchar(11) DEFAULT NULL,
  `cliente_cep` varchar(8) DEFAULT NULL,
  `cliente_rua` varchar(255) DEFAULT NULL,
  `cliente_numero` varchar(255) DEFAULT '0',
  `cliente_complemento` varchar(255) DEFAULT NULL,
  `cliente_bairro` varchar(255) DEFAULT NULL,
  `cliente_cidade` varchar(255) DEFAULT NULL,
  `cliente_uf` varchar(255) DEFAULT NULL,
  `cliente_status` tinyint(1) DEFAULT NULL,
  `date_create` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `app_cliente`
-- ----------------------------
BEGIN;
INSERT INTO `app_cliente` VALUES ('1', 'Teste5', '../../../uploads/images/2016/10/20161001185900.png', '11 98490970', 'teste obs', 'leo@gmail.com', '33044918804', '03908000', 'Rua Pedro Rabelo', '99', 'Casa', 'V Nova York', 'São Paulo', 'SP', '1', '2016-10-01 17:52:56', null), ('2', 'Leonardo P Zanela Dias', 'uploads/images/2016/10/20161001221359.png', '11984909936', 'Teste', 'leonardozaneladias@gmail.com', '33044918804', '03908000', 'Rua Pedro Rabelo', 'Jd Novo Carrão', '', 'Jardim Novo Carrão', 'São Paulo', 'SP', '1', '2016-10-01 22:15:23', null);
COMMIT;

-- ----------------------------
--  Table structure for `app_perfil`
-- ----------------------------
DROP TABLE IF EXISTS `app_perfil`;
CREATE TABLE `app_perfil` (
  `perfil_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_nome` varchar(15) DEFAULT NULL,
  `perfil_status` tinyint(1) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`perfil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `app_produtos`
-- ----------------------------
DROP TABLE IF EXISTS `app_produtos`;
CREATE TABLE `app_produtos` (
  `produto_id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_nome` varchar(50) DEFAULT NULL,
  `categoria_cod` int(11) DEFAULT NULL,
  `produto_valor` decimal(11,2) DEFAULT NULL,
  `produto_valor_minimo` decimal(11,2) DEFAULT NULL,
  `produto_posicao` int(11) DEFAULT NULL,
  `produto_mult_formando` tinyint(4) DEFAULT NULL,
  `produto_mult_convites` tinyint(4) DEFAULT NULL,
  `produto_extra_mult_mesa` tinyint(4) DEFAULT NULL,
  `produto_extra_mult_convite` tinyint(4) DEFAULT NULL,
  `produto_alt_cortesia` int(11) DEFAULT NULL,
  `produto_descricao` text,
  `produto_obs` text,
  `produto_status` tinyint(4) DEFAULT '0',
  `produto_alias` int(11) DEFAULT '0',
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`produto_id`),
  KEY `categoria_cod` (`categoria_cod`),
  CONSTRAINT `produto_categoria` FOREIGN KEY (`categoria_cod`) REFERENCES `app_categorias` (`categoria_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `app_racas`
-- ----------------------------
DROP TABLE IF EXISTS `app_racas`;
CREATE TABLE `app_racas` (
  `raca_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `descricao` text,
  `peso` int(11) DEFAULT NULL,
  `altura` float(10,2) DEFAULT NULL,
  `pelo` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`raca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `app_servicos`
-- ----------------------------
DROP TABLE IF EXISTS `app_servicos`;
CREATE TABLE `app_servicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `categoria_servico_id` int(11) DEFAULT NULL,
  `descricao` text,
  `valor` decimal(10,0) DEFAULT NULL,
  `tempo` smallint(6) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `app_servicos`
-- ----------------------------
BEGIN;
INSERT INTO `app_servicos` VALUES ('1', 'Banho', '2', 'Banho de Sabao com Plugas', '30', '25', '1', '2016-10-01 23:45:12', null);
COMMIT;

-- ----------------------------
--  Table structure for `app_tipoevento`
-- ----------------------------
DROP TABLE IF EXISTS `app_tipoevento`;
CREATE TABLE `app_tipoevento` (
  `tipoevento_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoevento_nome` varchar(50) DEFAULT NULL,
  `tipoevento_descricao` text,
  `tipoevento_posicao` int(11) DEFAULT NULL,
  `tipoevento_status` tinyint(1) DEFAULT NULL,
  `date_create` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tipoevento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `app_tipoevento`
-- ----------------------------
BEGIN;
INSERT INTO `app_tipoevento` VALUES ('18', 'Cachorro', 'teste obs OK', '1', '1', '2016-10-01 19:12:30', null), ('19', 'Gato', 'Gato teste', '2', '1', '2016-10-01 19:13:31', null);
COMMIT;

-- ----------------------------
--  Table structure for `app_user__perfil`
-- ----------------------------
DROP TABLE IF EXISTS `app_user__perfil`;
CREATE TABLE `app_user__perfil` (
  `user_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`perfil_id`),
  KEY `perfil_id` (`perfil_id`),
  CONSTRAINT `perfil` FOREIGN KEY (`perfil_id`) REFERENCES `app_perfil` (`perfil_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `ws_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ws_siteviews`
-- ----------------------------
DROP TABLE IF EXISTS `ws_siteviews`;
CREATE TABLE `ws_siteviews` (
  `siteviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `siteviews_date` date NOT NULL,
  `siteviews_users` decimal(10,0) NOT NULL,
  `siteviews_views` decimal(10,0) NOT NULL,
  `siteviews_pages` decimal(10,0) NOT NULL,
  PRIMARY KEY (`siteviews_id`),
  KEY `idx_1` (`siteviews_date`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ws_siteviews`
-- ----------------------------
BEGIN;
INSERT INTO `ws_siteviews` VALUES ('17', '2016-10-01', '2', '2', '139'), ('18', '2016-11-28', '2', '2', '2');
COMMIT;

-- ----------------------------
--  Table structure for `ws_siteviews_agent`
-- ----------------------------
DROP TABLE IF EXISTS `ws_siteviews_agent`;
CREATE TABLE `ws_siteviews_agent` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `agent_views` decimal(10,0) NOT NULL,
  `agent_lastview` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`agent_id`),
  KEY `idx_1` (`agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ws_siteviews_online`
-- ----------------------------
DROP TABLE IF EXISTS `ws_siteviews_online`;
CREATE TABLE `ws_siteviews_online` (
  `online_id` int(11) NOT NULL AUTO_INCREMENT,
  `online_session` varchar(255) CHARACTER SET latin1 NOT NULL,
  `online_startview` timestamp NULL DEFAULT NULL,
  `online_endview` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `online_ip` varchar(255) CHARACTER SET latin1 NOT NULL,
  `online_url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `online_agent` varchar(255) CHARACTER SET latin1 NOT NULL,
  `agent_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`online_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ws_siteviews_online`
-- ----------------------------
BEGIN;
INSERT INTO `ws_siteviews_online` VALUES ('24', 'h5grf1a91gcun2b88984ap4hj1', '2016-10-01 16:54:58', '2016-10-01 22:24:18', '192.168.10.1', '/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36', 'Chrome'), ('25', 'h5grf1a91gcun2b88984ap4hj1', '2016-10-01 20:53:50', '2016-10-01 22:24:18', '192.168.10.1', '/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36', 'Chrome'), ('26', 'essp32o2vmmbm5rgqgc5s8vdf2', '2016-11-28 18:08:08', '2016-11-28 18:28:08', '192.168.10.1', '/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36', 'Chrome'), ('27', 'essp32o2vmmbm5rgqgc5s8vdf2', '2016-11-28 19:49:44', '2016-11-28 20:09:44', '192.168.10.1', '/', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36', 'Chrome');
COMMIT;

-- ----------------------------
--  Table structure for `ws_users`
-- ----------------------------
DROP TABLE IF EXISTS `ws_users`;
CREATE TABLE `ws_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_lastname` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_tel` varchar(13) NOT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_registration` timestamp NULL DEFAULT NULL,
  `user_lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ws_users`
-- ----------------------------
BEGIN;
INSERT INTO `ws_users` VALUES ('6', 'Leonardo', 'Zanela', 'leonardozaneladias@gmail.com', '11984909936', '/uploads/images/2016/11/20161128182856.png', 'b5f54a7f5f60662c97f40ee7c92abace', '2016-10-01 14:02:51', '2016-11-28 18:29:36', '3');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
