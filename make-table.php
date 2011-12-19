<?
require_once ('../mysql_connect.php');  

$sql = "CREATE TABLE IF NOT EXISTS `pliki` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazwa` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;";

$result = @mysql_query($sql);

echo "wykonano";

?>