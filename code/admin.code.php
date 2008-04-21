<?php
$img = array('book','user','autor','loan','librarian','configure');
$title = array('libri','utenti','autori','prestiti','bibliotecari','preferenze');
$a = array(	array('add','cancel','edit','find','list'),
			array('add','cancel','edit','find','list'),
			array('cancel','edit','find','list'),
			array('add','ok','edit','find','list','attention'),
			array('add','cancel','edit','find','list'),
			array('backup','optimize'));
$b = array(	array('Inserisci libro','Cancella libro','Modifica libro','Cerca libro','Lista libri'),
			array('Inserisci utente','Cancella utente','Modifica utente','Cerca utente','Lista utenti'),
			array('Cancella autore','Modifica autore','Cerca autore','Lista autori'),
			array('Inserisci prestito','Libro restituito','Modifica prestito','Cerca prestito','Lista prestiti','Libri non restituiti'),
			array('Inserisci bibliotec.','Cancella bibliotec.','Modifica bibliotec.','Cerca bibliotecario','Lista bibliotecari'),
			array('Backup DB','Ottimizza DB'));
			
echo "<p class=\"title\">Centro amministrazione</p>
<table class=\"main\" cellpadding=\"0\" cellspacing=\"0\">";
for($i=0;$i<2;$i++)
{
	echo "<tr><td></td>";
	for($j=0;$j<3;$j++)
	{
		echo "<td class=\"cont\">
		<table cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"top\"></td></tr>
		<tr><td class=\"center\" height=\"198px\">
		<div class=\"titletable\">Database ",$title[($i*3)+$j],"</div>	
			<table cellpadding=\"0\" cellspacing=\"0\" width=\"329px\">
				<tr>
					<td class=\"img\">
						<img title=\"",ucfirst($title[($i*3)+$j]),"\" align=\"right\" alt=\"",ucfirst($title[($i*3)+$j]),"\" src=\"img/",$img[($i*3)+$j],".png\" />
					</td>
					<td class=\"operationcontainer\">
						<table cellpadding=\"0\" cellspacing=\"0\">";
						for($k=0 ; $k < count($a[($i*3)+$j]);$k++)
						{
							echo "<tr><td class=\"operation\"><a href=\"./code/",$a[($i*3)+$j][$k],".code.php?cat=", $img[($i*3)+$j] , "\"><img align=\"left\" title=\"" , $b[($i*3)+$j][$k] , "\" alt=\"",$b[($i*3)+$j][$k],"\" src=\"img/",$a[($i*3)+$j][$k],".png\" /><span class=\"titleoperation\">",$b[($i*3)+$j][$k],"</span></a></td></tr>";
						}
						echo "</table>
					</td>
				</tr>
			</table>
			</td>
		</tr><tr><td class=\"bottom\"></td></tr></table>
		</td>";
		if((($i*3)+$j != 5) && (($i*3)+$j != 2))
		{
			echo "<td></td>";
		}
	}
	echo "<td></td></tr>";
}
echo "</table>";
?>
