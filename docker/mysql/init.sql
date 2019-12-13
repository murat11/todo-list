
DROP TABLE IF EXISTS `todo_list`;
CREATE TABLE `todo_list` (
  `id` char(36) NOT NULL PRIMARY KEY,
  `name` char(64) DEFAULT NULL,
  `participants` JSON NOT NULL,
  `items` JSON NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

