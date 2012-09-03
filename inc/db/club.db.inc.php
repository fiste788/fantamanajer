<?php
require_once(TABLEDIR . 'Club.table.db.inc.php');

class Club extends ClubTable {
	
	public static function getClubByIdWithStats($idClub) {
		$q = "SELECT *
				FROM clubstatistiche
				WHERE idClub = '" . $idClub . "'";
		$exe = mysql_query($q) or self::sqlError($q);
		FirePHP::getInstance()->log($q);
		return mysql_fetch_object($exe,__CLASS__);
	}
}
?>
