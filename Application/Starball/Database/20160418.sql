ALTER TABLE  `t_brand` ADD UNIQUE (
`brandName`
);
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Hucklebones','');
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Aden+anais','');
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Babybites','');
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Bebe De Pino','');
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Jellycat','');
INSERT INTO `t_brand`(`brandName`, `description`) VALUES ('Little Lambo','');

INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('衬衫', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('短裤', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('短裙', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('开襟衫', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('礼品套装', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('连体衣', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('马甲', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('毛绒玩具', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('帽子', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('配饰', 3);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('手袋', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('袜子和连裤袜', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('外套', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('围兜', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('婴儿方巾', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('婴儿襁褓布', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('婴儿睡袋', 1);
INSERT INTO `t_category`(`categoryName`,`type`) VALUES ('长裤', 1);

