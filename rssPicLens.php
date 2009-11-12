<?php
	require_once('config/config.inc.php');
	require_once(INCDIR . 'utente.db.inc.php');
	require_once(INCDIR . 'db.inc.php');

	//Creating a new db istance
	$dblink = new db;
	$dblink->dbConnect();
	$utenteObj = new utente();
	$squadre = $utenteObj->getAllSquadre();
	//echo "<pre>".print_r($squadre,1)."</pre>";
	
	// Modifico l'intestazione e il tipo di documento da PHP a XML
	header("Content-type: text/xml;charset=\"utf-8\"");


	// Eseguo le operazioni di scrittura sul file
	echo "<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n";
	echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:media=\"http://search.yahoo.com/mrss\">\n";
	echo "<channel>\n";
	echo "<atom:icon>" . IMGSURL . "header.png</atom:icon>\n";
	foreach($squadre as $key=>$val)
	{
		if(file_exists(UPLOADDIR . $val['idUtente'] . '-original.jpg'))
		{
		echo "<item>\n";
		echo "<title><![CDATA[" . $val['nome'] . "]]></title>\n";
		echo "<link><![CDATA[" . UPLOADDIR . $val['idUtente'] . "-original.jpg]]></link>\n";
		echo "<media:thumbnail url=\"" . FULLURL . UPLOADDIR . $val['idUtente'] . ".jpg\" />\n";
		echo "<media:content url=\"" . FULLURL . UPLOADDIR . $val['idUtente'] . "-original.jpg\" />\n";
		echo "</item>\n";
		}
	}
	echo "</channel>\n</rss>";
?>
