<?php 
$home = array('home');
$laTuaSquadra = array('rosa');
$leSquadre = array('rosa');
$conferenzeStampa = array('confStampa','editArticolo');
$classifica = array('classifica','punteggidettaglio');
$altro = array('contatti','formazione','formazioniAll','freeplayer','premi','trasferimenti,other');
$allpages = array_merge($home,$laTuaSquadra,$leSquadre,$conferenzeStampa,$classifica,$altro);
 ?>
<ul>
	<li <?php if(in_array($this->p,$home)) echo 'class="selected"'; ?>>
		<a href="index.php?p=home" title="Home">Home</a>
	</li>
	<?php if($_SESSION['logged']): ?>
	<li <?php if(in_array($this->p,$laTuaSquadra) && isset($_GET['squadra'])) echo 'class="selected"'; ?>>
		<a href="index.php?p=rosa&amp;squadra=<?php echo $_SESSION['idsquadra']; ?>" title="La tua squadra">La tua squadra</a>
	</li>
	<?php endif; ?>
	<li <?php if(in_array($this->p,$leSquadre) && !isset($_GET['squadra'])) echo 'class="selected"'; ?>>
		<a href="index.php?p=rosa" title="Le squadre">Le squadre</a>
	</li>
	<li <?php if(in_array($this->p,$conferenzeStampa)) echo 'class="selected"'; ?>>
		<a href="index.php?p=confStampa" title="Conferenze stampa">Conferenze stampa</a>
	</li>
	<li <?php if(in_array($this->p,$classifica)) echo 'class="selected"'; ?>>
		<a href="index.php?p=classifica" title="Classifica">Classifica</a>
	</li>
	<li <?php if(in_array($this->p,$altro)) echo 'class="selected"'; ?>>
		<a href="index.php?p=other" title="Altro...">Altro...</a>
	</li>
</ul>
