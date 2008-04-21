<?php
/*
Ci possono essere più pagine e più lingue; associare al file di lingua il titolo della pagina non ha senso perchè 
ogni pagina ha il suo titolo.
Allo stesso modo mettere le variabili che definiscono titolo, keywords e description del file *.code.php è errato poichè
queste dipendono dalla lingua che si utilizza.
Per questo creo il file definitions.lang.php che, per ogni lingua, definisce tre vettori: 
$description['nome_della_pagina'] = Descrizione
$keywords['nome_della_pagina'] = Keywords
$title['nome_della_pagina'] = titolo
*/

$description = array();
$description = 

?>