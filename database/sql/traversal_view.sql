INSERT INTO `nodes` (`lft`,`rgt`,`parentID`) VALUES (0, 1, 0);

CREATE VIEW `vw_lftrgt` AS select `nodes`.`lft` AS `lft` from `nodes` union select `nodes`.`rgt` AS `rgt` from `nodes`;
