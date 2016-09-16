-- Дамп структуры базы данных test_task
CREATE DATABASE IF NOT EXISTS `test_task` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test_task`;


-- Дамп структуры для таблица test_task.post
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `text` text NOT NULL,
  `image` char(255) DEFAULT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
