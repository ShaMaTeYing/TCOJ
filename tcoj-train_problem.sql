/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : tcoj

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2017-05-01 14:09:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for train_problem
-- ----------------------------
DROP TABLE IF EXISTS `train_problem`;
CREATE TABLE `train_problem` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `level_msg_id` int(11) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `time_limit` int(7) NOT NULL DEFAULT '1000',
  `memory_limit` int(7) NOT NULL DEFAULT '32768',
  `submissions` int(7) NOT NULL DEFAULT '0',
  `accepted` int(7) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL,
  `sample_input` text NOT NULL,
  `sample_output` text NOT NULL,
  `author` text NOT NULL,
  `source` text NOT NULL,
  `status` int(9) NOT NULL DEFAULT '1',
  `output_limit` int(9) DEFAULT '0',
  `case_number` int(11) NOT NULL DEFAULT '10',
  `difficulty` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of train_problem
-- ----------------------------
INSERT INTO `train_problem` VALUES ('1', '1', '交换', '1000', '32768', '0', '0', '输入两个整数, 把他们交换后输出.', '两个整数a, b(-2^31<=a, b<2^31).', '交换后输出a, b.', '1 5\r\n', '5 1\r\n', '吴迎', 'TCOJ', '1', '256', '10', '0');
INSERT INTO `train_problem` VALUES ('2', '1', 'a+b(2)', '1000', '32768', '0', '0', '计算两个double类型数的和', '一行内两个double类型的数a, b(-1e9<=a, b<=1e9).', 'a+b的值，保留两位小数.', '5.5 4.5', '10.00', 'TCOJ', 'kongroo', '1', '0', '10', '0');
INSERT INTO `train_problem` VALUES ('3', '1', 'a+b+c', '1000', '32768', '0', '0', '计算三个整数a, b, c的和.', 'a, b, c(-1e9<=a, b, c<=1e9).', 'a+b+c的值.', '1 2 3', '6', 'TCOJ', 'kongroo', '1', '0', '10', '0');
INSERT INTO `train_problem` VALUES ('4', '1', '翻倍', '1000', '32768', '0', '0', '将一个整数翻倍后输出.', '一个整数x(-2^31<=x<2^31).', 'x翻倍后的结果.', '5', '10', 'TCOJ', 'stupidchen', '1', '0', '10', '0');
INSERT INTO `train_problem` VALUES ('5', '1', '平均数', '1000', '32768', '0', '0', '给定7个数, 求它们的平均数, 并保留整数.', '一行7个整数, 题目保证求和运算不会超过int范围.', '一个整数, 表示7个数的平均数.', '1 3 5 7 9 8 4', '5', 'TCOJ', 'xkchen', '1', '0', '10', '0');
INSERT INTO `train_problem` VALUES ('6', '1', '鸡兔同笼', '1000', '32768', '0', '0', '有若干只鸡兔同在一个笼子里，从上面数，有a个头，从下面数，有b只脚。问笼中各有多少只鸡和兔？', '两个整数a,b(-1e9<=a,b<=1e9)。输入数据保证有解。', '输出鸡和兔的个数。', '35 94', '23 12', 'TCOJ', 'wy', '1', '0', '10', '0');
