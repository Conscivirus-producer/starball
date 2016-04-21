ALTER TABLE  `t_item` CHANGE  `isAvailable`  `isAvailable` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '0-没货,不显示;1-有货;2-即将上新.';
update t_item set isAvailable = 0 where itemId not in (select itemId from t_inventory);
update t_item set isAvailable = 0 where itemId in (-39, -42, -45);