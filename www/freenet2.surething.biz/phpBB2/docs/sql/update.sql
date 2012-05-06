
INSERT INTO `phpbb_auction_config` VALUES ('auction_pseudo_cron', '0');
INSERT INTO `phpbb_auction_config` VALUES ('auction_pseudo_cron_frequence', 'm');
INSERT INTO `phpbb_auction_config` VALUES ('auction_pseudo_cron_last', '1103460607');
INSERT INTO `phpbb_auction_config` VALUES ('auction_room_pagination', '2');
INSERT INTO `phpbb_auction_config` VALUES ('auction_block_display_last_bids', '1');
INSERT INTO `phpbb_auction_config` VALUES ('auction_config_last_bids_number', '5');
INSERT INTO `phpbb_auction_config` VALUES ('auction_block_display_newest_offers', '1');
INSERT INTO `phpbb_auction_config` VALUES ('auction_config_newest_offers_number', '10');
INSERT INTO `phpbb_auction_config` VALUES ('auction_paymentsystem_activate_debit', '1');
INSERT INTO `phpbb_auction_config` VALUES ('auction_offer_cost_final_percent', '5');

CREATE TABLE `phpbb_auction_store` (
`pk_auction_store_id` MEDIUMINT( 8 ) NOT NULL ,
`fk_user_id` MEDIUMINT( 8 ) NOT NULL ,
`store_name` VARCHAR( 255 ) NOT NULL ,
`store_description` TEXT NOT NULL ,
`store_header` TEXT NOT NULL ,
  `show_block_drop_down` tinyint(1) NOT NULL default '1',
  `show_block_rooms` tinyint(1) NOT NULL default '1',
  `show_block_search` tinyint(1) NOT NULL default '1',
  `show_block_statistics` tinyint(1) NOT NULL default '1',
  `show_block_myauction` tinyint(1) NOT NULL default '1',
  `show_block_specials` tinyint(1) NOT NULL default '1',
  `show_block_priceinfo` tinyint(1) NOT NULL default '1',
  `show_block_calendar` tinyint(1) NOT NULL default '1',
  `show_block_closetoend` tinyint(1) NOT NULL default '1',
  `show_block_ticker` tinyint(1) NOT NULL default '1',
PRIMARY KEY ( `pk_auction_store_id` )
) TYPE=MyISAM AUTO_INCREMENT=2;


CREATE TABLE `phpbb_auction_bid_increase` (
  `PK_bid_increase` mediumint(8) unsigned NOT NULL auto_increment,
  `bid_increase` decimal(15,2) NOT NULL default '0.00',
  PRIMARY KEY  (`PK_bid_increase`)
) TYPE=MyISAM AUTO_INCREMENT=60 ;

INSERT INTO `phpbb_auction_bid_increase` VALUES (49, '0.10');
INSERT INTO `phpbb_auction_bid_increase` VALUES (50, '0.20');
INSERT INTO `phpbb_auction_bid_increase` VALUES (51, '0.50');
INSERT INTO `phpbb_auction_bid_increase` VALUES (52, '1.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (53, '2.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (54, '5.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (55, '10.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (56, '20.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (57, '30.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (58, '50.00');
INSERT INTO `phpbb_auction_bid_increase` VALUES (59, '100.00');
    

CREATE TABLE `phpbb_auction_account` (
`pk_auction_account_id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
`fk_auction_account_creditor_id` MEDIUMINT( 8 ) NOT NULL ,
`fk_auction_account_debitor_id` MEDIUMINT( 8 ) NOT NULL ,
`auction_account_auction_amount` DECIMAL( 15, 2 ) NOT NULL ,
`auction_account_amount_date` INT( 11 ) NOT NULL ,
`auction_account_notified` MEDIUMINT( 8 ) NOT NULL ,
`auction_account_amount_paid` DECIMAL( 15, 2 ) NOT NULL ,
`auction_account_amount_paid_by` MEDIUMINT( 8 ) NOT NULL ,
`fk_auction_offer_id` MEDIUMINT( 8 ) NOT NULL ,
`auction_account_action` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `pk_auction_account_id` )
);

ALTER TABLE `phpbb_auction_offer` ADD `auction_offer_percentage_charged` TINYINT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `phpbb_auction_offer` ADD `auction_offer_sellers_location` varchar(100) NOT NULL default '';
ALTER TABLE `phpbb_auction_offer` ADD `auction_offer_bid_increase` decimal(15,2) NOT NULL default '0.00';
ALTER TABLE `phpbb_auction_offer` ADD `auction_offer_accepted_payments` varchar(100) NOT NULL default '';

UPDATE `phpbb_auction_config` SET auction_version= '1.3m' WHERE auction_version='1.2 m';