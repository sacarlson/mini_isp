#
# phpCA Classified Ads Mod v0.5.0 for phpBB 2.0.x - MySQL schema & basic
#

CREATE TABLE phpbb_ads_adverts (
	id int(11) NOT NULL AUTO_INCREMENT,
	category varchar(25) NOT NULL DEFAULT '',
	sub_category varchar(25) NOT NULL DEFAULT '',
	ad_type_code smallint(1) NOT NULL DEFAULT '0',
	basic_ad_ind tinyint(1) NOT NULL DEFAULT '0',
	standard_ad_ind tinyint(1) NOT NULL DEFAULT '0',
	photo_ad_ind tinyint(1) NOT NULL DEFAULT '0',
	premium_ad_ind tinyint(1) NOT NULL DEFAULT '0',
	ad_cost decimal(10, 2) NOT NULL default '0.00',
	user_id mediumint(8) NOT NULL DEFAULT '0',
	username varchar(32) DEFAULT NULL,
	user_ip varchar(8) NOT NULL DEFAULT '',
	time int(11) unsigned NOT NULL DEFAULT '0',
	edit_user_id mediumint(8) DEFAULT NULL,
	edit_time int(11) unsigned NOT NULL DEFAULT '0',
	edit_count smallint(5) unsigned NOT NULL DEFAULT '0',
	title varchar(50) NOT NULL DEFAULT '',
	short_desc varchar(125) NOT NULL DEFAULT '',
	price varchar(50) NOT NULL DEFAULT '0',
	views int(11) NOT NULL DEFAULT '0',
	status varchar(7) NOT NULL DEFAULT '',
	expiry_date int(11) unsigned NOT NULL DEFAULT '0',
	trade_ind tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id));

CREATE TABLE phpbb_ads_categories (
	cat_category varchar(40) NOT NULL default '',
	cat_sub_category varchar(40) NOT NULL default '',
	cat_basic_cost smallint(6) NOT NULL default '0',
	cat_standard_cost smallint(6) NOT NULL default '0',
	cat_photo_cost smallint(6) NOT NULL default '0',
	cat_premium_cost smallint(6) NOT NULL default '0',
	cat_field_1_desc varchar(50) NOT NULL default '',
	cat_field_2_desc varchar(50) NOT NULL default '',
	cat_field_3_desc varchar(50) NOT NULL default '',
	cat_field_4_desc varchar(50) NOT NULL default '',
	cat_field_5_desc varchar(50) NOT NULL default '',
	cat_field_6_desc varchar(50) NOT NULL default '',
	cat_field_7_desc varchar(50) NOT NULL default '',
	cat_field_8_desc varchar(50) NOT NULL default '',
	cat_field_9_desc varchar(50) NOT NULL default '',
	cat_field_10_desc varchar(50) NOT NULL default '',
	cat_create_level tinyint(3) NOT NULL default '0',
	cat_edit_level tinyint(3) NOT NULL default '0',
	cat_delete_level tinyint(3) NOT NULL default '0',
	cat_image_level tinyint(3) NOT NULL default '0',
	cat_comment_level tinyint(3) NOT NULL default '0',
	cat_rate_level tinyint(3) NOT NULL default '0',
	PRIMARY KEY (cat_category, cat_sub_category)); 

CREATE TABLE phpbb_ads_chasers (
	id int(11) NOT NULL default '0',
	last_chase_type char(1) NOT NULL default '',
	renewal_password int(9) NOT NULL default '0',
	PRIMARY KEY (id)); 

CREATE TABLE phpbb_ads_comments (
	comment_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	comment_ad_id int(11) unsigned NOT NULL default '0',
	comment_user_id mediumint(8) NOT NULL default '0',
	comment_username varchar(32) default NULL,
	comment_user_ip varchar(8) NOT NULL default '',
	comment_time int(11) unsigned NOT NULL default '0',
	comment_text text,
	comment_edit_time int(11) unsigned default NULL ,
	comment_edit_count smallint(5) unsigned NOT NULL default '0',
	comment_edit_user_id mediumint(8) default NULL,
	PRIMARY KEY (comment_id),
	KEY comment_ad_id (comment_ad_id),
	KEY comment_user_id (comment_user_id),
	KEY comment_user_ip (comment_user_ip),
	KEY comment_time (comment_time)); 

CREATE TABLE phpbb_ads_config (
	config_name varchar(255) NOT NULL,
	config_value varchar(255) NOT NULL,
	PRIMARY KEY (config_name));

CREATE TABLE phpbb_ads_details (
	id int(11) NOT NULL default '0',
	additional_info text NOT NULL ,
	field_1 varchar(100) NOT NULL default '',
	field_2 varchar(100) NOT NULL default '',
	field_3 varchar(100) NOT NULL default '',
	field_4 varchar(100) NOT NULL default '',
	field_5 varchar(100) NOT NULL default '',
	field_6 varchar(100) NOT NULL default '',
	field_7 varchar(100) NOT NULL default '',
	field_8 varchar(100) NOT NULL default '',
	field_9 varchar(100) NOT NULL default '',
	field_10 varchar(100) NOT NULL default '',
	PRIMARY KEY (id)); 

CREATE TABLE phpbb_ads_images (
	id int(11) NOT NULL default '0',
	img_seq_no int(11) NOT NULL AUTO_INCREMENT,
	img_description text NOT NULL,
	img_deleted_ind tinyint(1) NOT NULL default '0',
	PRIMARY KEY (id, img_seq_no)); 

CREATE TABLE phpbb_ads_paid_ads_config (
	config_name varchar(255) NOT NULL default '',
	config_value varchar(255) NOT NULL default '',
	PRIMARY KEY (config_name)); 

CREATE TABLE phpbb_ads_rate (
	rate_ad_id int(11) unsigned NOT NULL default '0',
	rate_user_id mediumint(8) NOT NULL default '0',
	rate_user_ip char(8) NOT NULL default '',
	rate_point tinyint(3) unsigned NOT NULL default '0',
	KEY rate_ad_id (rate_ad_id),
	KEY rate_user_id (rate_user_id),
	KEY rate_user_ip (rate_user_ip),
	KEY rate_point (rate_point)); 

CREATE TABLE phpbb_ads_users (
	users_user_id mediumint(8) NOT NULL default '0',
	users_time int(11) NOT NULL default '0',
	users_edit_time int(11) NOT NULL default '0',
	users_balance smallint(6) NOT NULL default '0',
	PRIMARY KEY (users_user_id)); 

CREATE TABLE phpbb_ads_paypal_payments (
	invoice INT UNSIGNED AUTO_INCREMENT,
	receiver_email varchar(60),
	item_name varchar(100),
	item_number varchar(10),
	quantity varchar(6),
	payment_status varchar(10),
	pending_reason varchar(10),
	payment_date varchar(20),
	mc_gross varchar(20),
	mc_fee varchar(20),
	tax varchar(20),
	mc_currency varchar(3),
	txn_id varchar(20),
	txn_type varchar(10),
	first_name varchar(30),
	last_name varchar(40),
	address_street varchar(50),
	address_city varchar(30),
	address_state varchar(30),
	address_zip varchar(20),
	address_country varchar(30),
	address_status varchar(10),
	payer_email varchar(60),
	payer_status varchar(10),
	payment_type varchar(10),
	notify_version varchar(10),
	verify_sign varchar(10),
	custom mediumint(8), 
	PRIMARY KEY (invoice));

INSERT INTO phpbb_ads_config VALUES ('max_pics', '1024');
INSERT INTO phpbb_ads_config VALUES ('ads_per_page', '5'); 
INSERT INTO phpbb_ads_config VALUES ('ad_duration_months', '3'); 
INSERT INTO phpbb_ads_config VALUES ('board_disable', '0'); 
INSERT INTO phpbb_ads_config VALUES ('config_id', '1'); 
INSERT INTO phpbb_ads_config VALUES ('default_style', '1'); 
INSERT INTO phpbb_ads_config VALUES ('first_chase_days', '28'); 
INSERT INTO phpbb_ads_config VALUES ('large_img_height', '480'); 
INSERT INTO phpbb_ads_config VALUES ('large_img_width', '640'); 
INSERT INTO phpbb_ads_config VALUES ('max_ads_per_user', '100'); 
INSERT INTO phpbb_ads_config VALUES ('max_images_per_ad', '5'); 
INSERT INTO phpbb_ads_config VALUES ('medium_img_height', '200'); 
INSERT INTO phpbb_ads_config VALUES ('medium_img_width', '200'); 
INSERT INTO phpbb_ads_config VALUES ('second_chase_days', '14'); 
INSERT INTO phpbb_ads_config VALUES ('thumb_img_height', '100'); 
INSERT INTO phpbb_ads_config VALUES ('thumb_img_width', '100'); 
INSERT INTO phpbb_ads_config VALUES ('rate_scale', '10'); 
INSERT INTO phpbb_ads_config VALUES ('comment', '1'); 
INSERT INTO phpbb_ads_config VALUES ('rate', '1'); 
INSERT INTO phpbb_ads_config VALUES ('images', '1'); 
INSERT INTO phpbb_ads_config VALUES ('paid_ads', '0'); 
INSERT INTO phpbb_ads_config VALUES ('renewals', '1'); 
INSERT INTO phpbb_ads_config VALUES ('view_level', '-1'); 
INSERT INTO phpbb_ads_config VALUES ('move_level', '2'); 
INSERT INTO phpbb_ads_config VALUES ('search_level', '0'); 
INSERT INTO phpbb_ads_config VALUES ('private_trade_ind', '0'); 
INSERT INTO phpbb_ads_config VALUES ('version', '0.5.5'); 

INSERT INTO phpbb_ads_paid_ads_config VALUES ('currency_code', 'GBP'); 
INSERT INTO phpbb_ads_paid_ads_config VALUES ('language_code', 'GB'); 
INSERT INTO phpbb_ads_paid_ads_config VALUES ('business_email', 'websiteowner@hotmail.com');  
INSERT INTO phpbb_ads_paid_ads_config VALUES ('sandbox', '1');  
INSERT INTO phpbb_ads_paid_ads_config VALUES ('basic', '1');  
INSERT INTO phpbb_ads_paid_ads_config VALUES ('standard', '1');  
INSERT INTO phpbb_ads_paid_ads_config VALUES ('photo', '1');  
INSERT INTO phpbb_ads_paid_ads_config VALUES ('premium', '1');  
