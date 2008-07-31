<?php
	require_once('config/config.inc.php');
	require_once(INCDIR.'eventi.inc.php');
	require_once (INCDIR.'db.inc.php');

	//Creating a new db istance
	$dblink = &new db;
	$dblink->dbConnect();
	$eventiObj = new eventi();
	$eventi = $eventiObj->getEventi(NULL,0,10);
	//echo "<pre>".print_r($eventi,1)."</pre>";
	
	// Modifico l'intestazione e il tipo di documento da PHP a XML
	header("Content-type: text/xml;charset=\"utf-8\"");


	// Eseguo le operazioni di scrittura sul file
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
	echo "<channel>\n";
	echo "<title>FantaManajer</title>\n";
	echo "<link>http://www.fantamanajer.it/</link>\n";
	$data = explode(' ',$eventi[0]['data']);
	$giorno = explode('-',$data[0]);
	$tempo = explode(':',$data[1]);
	echo "<lastBuildDate>" . date("r",mktime($tempo[0],$tempo[1],$tempo[2],$giorno[1],$giorno[2],$giorno[0])) . "</lastBuildDate>\n";
	echo "<description>Lista degli eventi del FantaManajer</description>\n";
	echo "<copyright>Copyright 2002 FantaManajer.it</copyright>\n";
	echo "<atom:link href=\"" . FULLURL.'rss.php' . "\" rel=\"self\" type=\"application/rss+xml\" />\n";
	echo "<managingEditor>sonzogni.stefano@gmail.com (Sonzogni Stefano)</managingEditor>\n";
	echo "<webMaster>sonzogni.stefano@gmail.com (Sonzogni Stefano)</webMaster>\n";
	echo "<language>IT-it</language>\n";
	foreach($eventi as $key=>$val)
	{
		echo "<item>\n";
		echo "<title><![CDATA[" . $val['titolo'] . "]]></title>\n";
		if(!empty($val['link'])) 
			echo "<link><![CDATA[" . FULLURL.$val['link'] . "]]></link>\n" . "<guid><![CDATA[" . FULLURL.$val['link'] . "]]></guid>\n";
		else
			echo "<link><![CDATA[" . FULLURL.'index.php?p=viewFeed#evento-'.$val['idEvento'] . "]]></link>\n" . "<guid><![CDATA[" . FULLURL.'index.php?p=viewFeed#evento-'.$val['idEvento'] . "]]></guid>\n";
		echo "<description><![CDATA[" . $val['content'] . "]]></description>\n";
		echo "</item>\n";
	}
	echo "</channel>\n</rss>";
?>