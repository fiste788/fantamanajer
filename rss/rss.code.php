<?php
	require_once('../config/config.inc.php');
	require_once('../'.INCDIR.'eventi.inc.php');
	require_once ('../'.INCDIR.'db.inc.php');

	//Creating a new db istance
	$dblink = &new db;
	$dblink->dbConnect();
	$eventiObj = new eventi();
	$eventi = $eventiObj->getEventi();
	
	// Modifico l'intestazione e il tipo di documento da PHP a XML
	header("Content-type: text/xml");

	// Eseguo le operazioni di scrittura sul file
	echo "<rss version=\"2.0\">\n";
	echo "<channel>\n";
	echo "<title>FantaManajer</title>\n";
	echo "<link>http://www.fantamanajer.it/</link>\n";
	echo "<description>Lista degli eventi del FantaManajer</description>\n";
	echo "<copyright>Copyright 2002 FantaManajer.it</copyright>\n";
	echo "<managingEditor>sonzogni.stefano@gmail.com</managingEditor>\n";
	echo "<webMaster>sonzogni.stefano@gmail.com</webMaster>\n";
	echo "<language>IT-it</language>\n";
	foreach($eventi as $key=>$val)
	{
		echo "<item>";
		echo "<title>" . $val['title'] . "</title>\n";
		if(isset($val['link'])) echo "<link>" . $val['link'] . "</link>\n";
		echo "<description>" . $val['content'] . "</description>\n";
		echo "</item>\n";
	}
	echo "</channel></rss>";
?>
