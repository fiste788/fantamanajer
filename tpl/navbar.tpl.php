<?php 
$home = array('home');
$laTuaSquadra = array('rosa');
$leSquadre = array('rosa');
$conferenzeStampa = array('conferenzeStampa'=>array('modificaConferenza'));
$classifica = array('classifica'=>array('dettaglioGiornata'));
$altro = array('altro'=>array('contatti','formazione','altreFormazioni','giocatoriLiberi','premi','trasferimenti','linkUtili','feed','dettaglioGiocatore','download'));
$areaAmministrativa = array('areaAmministrativa'=>array('inserisciFormazione','nuovoTrasferimento','creaSquadra','lanciaScript','gestioneDatabase','newsletter','penalita','modificaGiocatore','impostazioni','giornate'));
$allpages = array_merge($home,$laTuaSquadra,$leSquadre,$conferenzeStampa,$classifica,$altro);
 ?>
<ul>
	<li<?php if(in_array($this->p,$home)) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('home'); ?>" title="Home">Home</a>
		</div>
	</li>
	<?php if($_SESSION['logged']): ?>
	<li<?php if(in_array($this->p,$laTuaSquadra) && isset($_GET['squadra']) && $_GET['squadra'] == $_SESSION['idSquadra']) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('rosa',array('squadra'=>$_SESSION['idSquadra'])); ?>" title="La tua squadra">La tua squadra</a>
		</div>
	</li>
	<?php endif; ?>
	<li<?php if(in_array($this->p,$leSquadre) && !isset($_GET['squadra']) || (in_array($this->p,$leSquadre) && isset($_GET['squadra']) && $_SESSION['idSquadra'] != $_GET['squadra'])) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('rosa'); ?>" title="Le squadre">Le squadre</a>
		</div>
	</li>
	<li<?php if(isset($conferenzeStampa[$this->p]) || in_array($this->p,$conferenzeStampa['conferenzeStampa'])) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('conferenzeStampa'); ?>" title="Conferenze stampa">Conferenze stampa</a>
			<?php if(in_array($this->p,$conferenzeStampa['conferenzeStampa'])): ?>
				<a class="son"> > </a>
				<a><?php echo $this->pages[$this->p]['title']; ?></a>
			<?php endif; ?>
		</div>
	</li>
	<li<?php if(isset($classifica[$this->p]) || in_array($this->p,$classifica['classifica'])) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('classifica'); ?>" title="Classifica">Classifica</a>
			<?php if(in_array($this->p,$classifica['classifica'])): ?>
				<a class="son"> > </a>
				<a><?php echo $this->pages[$this->p]['title']; ?></a>
			<?php endif; ?>
		</div>
	</li>
	<li<?php if(isset($altro[$this->p]) || in_array($this->p,$altro['altro'])) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('altro'); ?>" title="Altro...">Altro...</a>
			<?php if(in_array($this->p,$altro['altro'])): ?>
				<a class="son"> > </a>
				<a><?php echo $this->pages[$this->p]['title']; ?></a>
			<?php endif; ?>
		</div>
	</li>
	<?php if($_SESSION['usertype'] == 'admin' || $_SESSION['usertype'] == 'superadmin'): ?>
	<li<?php if(isset($areaAmministrativa[$this->p]) || in_array($this->p,$areaAmministrativa['areaAmministrativa'])) echo ' class="selected"'; ?>>
		<div>
			<a href="<?php echo $this->linksObj->getLink('areaAmministrativa'); ?>" title="Area amministrativa">Area amministrativa</a>
			<?php if(in_array($this->p,$areaAmministrativa['areaAmministrativa'])): ?>
				<a class="son"> > </a>
				<a><?php echo $this->pages[$this->p]['title']; ?></a>
			<?php endif; ?>
		</div>
	</li>
	<?php endif; ?>
</ul>
