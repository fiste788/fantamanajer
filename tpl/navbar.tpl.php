<?php 
$home = array('home');
$laTuaSquadra = array('rosa');
$leSquadre = array('rosa');
$conferenzeStampa = array('conferenzeStampa','modificaConferenza');
$classifica = array('classifica','dettaglioGiornata');
$altro = array('contatti','formazione','altreFormazioni','giocatoriLiberi','premi','trasferimenti','altro','linkUtili','feed','dettaglioGiocatore');
$allpages = array_merge($home,$laTuaSquadra,$leSquadre,$conferenzeStampa,$classifica,$altro);
 ?>
<ul>
	<li<?php if(in_array($this->p,$home)) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('home'); ?>" title="Home">Home</a>
	</li>
	<?php if($_SESSION['logged']): ?>
	<li<?php if(in_array($this->p,$laTuaSquadra) && isset($_GET['squadra']) && $_GET['squadra'] == $_SESSION['idSquadra']) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_SESSION['idSquadra'])); ?>" title="La tua squadra">La tua squadra</a>
	</li>
	<?php endif; ?>
	<li<?php if(in_array($this->p,$leSquadre) && !isset($_GET['squadra']) || (in_array($this->p,$leSquadre) && isset($_GET['squadra']) && $_SESSION['idSquadra'] != $_GET['squadra'])) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('rosa'); ?>" title="Le squadre">Le squadre</a>
	</li>
	<li<?php if(in_array($this->p,$conferenzeStampa)) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('conferenzeStampa'); ?>" title="Conferenze stampa">Conferenze stampa</a>
	</li>
	<li<?php if(in_array($this->p,$classifica)) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('classifica'); ?>" title="Classifica">Classifica</a>
	</li>
	<li<?php if(in_array($this->p,$altro)) echo ' class="selected"'; ?>>
		<a href="<?php echo $this->linksObj->getLink('altro'); ?>" title="Altro...">Altro...</a>
	</li>
</ul>