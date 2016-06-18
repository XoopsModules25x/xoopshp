# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------
CREATE TABLE XHP_quiz (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content longtext NOT NULL,
  posted datetime NOT NULL default '0000-00-00 00:00:00',
  poster int(11) NOT NULL default '0',
  results_to varchar(60) NOT NULL default '',
  counter int(11) NOT NULL default '0',
  display tinyint NOT NULL default '1',
  expire datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (artid),
  KEY idxxhp_quizsecid (secid),
  KEY idxxhp_quizcounterdesc (counter)
) ENGINE = MYISAM;
# --------------------------------------------------------
CREATE TABLE XHP_sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(40) NOT NULL default '',
  secdesc varchar(255) NOT NULL default '',
  secgroup int(11) NOT NULL default '0',
  display tinyint NOT NULL default '1',
  expire datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (secid),
  KEY idxsectionssecname (secname)
) ENGINE = MYISAM;
# --------------------------------------------------------
CREATE TABLE XHP_results (
  id bigint(14) unsigned NOT NULL auto_increment,
  quiz_id bigint(14) NOT NULL default '0',
  uid bigint(14) unsigned NOT NULL default '0',
  score bigint(14) NOT NULL default '0',
  start_time varchar(255) NOT NULL default '',
  end_time varchar(255) NOT NULL default '',
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  host varchar(255) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  comment text NOT NULL,
  PRIMARY KEY  (id),
  FULLTEXT KEY host (host,ip,comment)
) ENGINE = MYISAM;
# --------------------------------------------------------
CREATE TABLE XHP_config (
  teacher_email varchar(25) NOT NULL default '',
  multibyte int(11) NOT NULL default '0'
)  ENGINE = MYISAM;
