<?php $j = 0; ?>
<div class="titolo-pagina">
<?php if(isset($_GET['a'])):
switch($_GET['a']):
case 'new': $button = 'Crea';?>
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Crea squadra</h2>
<?php 	break; endcase;
	case 'edit': $button = 'Modifica'; ?>
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Modifica squadra</h2>
<?php 	break; endcase;
	case 'cancel': $button = 'Cancella'; ?>
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Cancella squadra</h2>
<?php break; endcase;
	default: $button = 'Errore'; break; 
endswitch;else:
?>
<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Crea squadra</h2>
<?php endif; ?>
</div>
<div id="creaSquadre" class="main-content">
	<?php if($this->lega != NULL && isset($_GET['a']) && isset($_GET['id'])): ?>
	<form id="creaSq" class="column" name="creaSquadra" action="<?php echo $this->linksObj->getLink('creaSquadra',$this->goTo); ?>" method="post">
		<fieldset class="column no-margin">
			<input type="hidden" name="a" value="<?php if(isset($_GET['a'])) echo $_GET['a']; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" />
			<h3>Informazioni generali</h3>
			<div class="formbox">
				<label for="nomeSquadra">Nome della squadra:</label>
				<input<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> class="text" id="nomeSquadra" name="nome" type="text" maxlength="40" <?php if(isset($this->datiSquadra['nome'])) $nomeSquadra = $this->datiSquadra['nome']; if(isset($_POST['nome'])) $nomeSquadra = $_POST['nome']; if(isset($nomeSquadra)) echo 'value="' . $nomeSquadra . '"'; ?> />
			</div>
			<div class="formbox">
				<label for="username">Username:</label>
				<input<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> class="text" id="username" name="usernamenew" type="text" maxlength="15" <?php if(isset($this->datiSquadra['username'])) $username = $this->datiSquadra['username']; if(isset($_POST['usernamenew'])) $username = $_POST['usernamenew']; if(isset($username)) echo 'value="'. $username .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="email">Email:</label>
				<input<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> class="text" id="mail" name="mail" type="text" maxlength="30" <?php if(isset($this->datiSquadra['mail'])) $mail = $this->datiSquadra['mail']; if(isset($_POST['mail'])) $mail = $_POST['mail']; if(isset($mail))echo 'value="'. $mail .'"'; ?> />
			</div>
			<div class="formbox">
				<label for="amministratore">Amministratore?</label>
				<input<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> class="checkbox" id="amministratore" name="amministratore" type="checkbox" <?php if(isset($this->datiSquadra['amministratore']) && $this->datiSquadra['amministratore'] != 0) $admin = $this->datiSquadra['amministratore']; if(isset($_POST['amministratore'])) $admin = $_POST['amministratore']; if(isset($admin)) echo 'checked="checked"'; ?> />
			</div>
		</fieldset>
		<fieldset id="panchinari">
			<h4 class="bold no-margin">Portieri</h4>
			<hr />
			<?php for($i = 0;$i < 3; $i++): ?>
				<select<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> name="giocatore[]">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->portieri as $key => $val): ?>
						<option value="<?php echo $val['idGioc'] ?>"<?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo ' selected="selected"'; ?>><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Difensori</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> name="giocatore[]">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->difensori as $key => $val): ?>
						<option <?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Centrocampisti</h4>
			<hr />
			<?php for($i = 0;$i < 8; $i++): ?>
				<select<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> name="giocatore[]">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->centrocampisti as $key => $val): ?>
						<option <?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
			<h4 class="bold no-margin">Attaccanti</h4>
			<hr />
			<?php for($i = 0;$i < 6; $i++): ?>
				<select<?php if($button == 'Cancella') echo ' disabled="disabled"' ?> name="giocatore[]">
					<option></option>
					<?php if(isset($this->giocatori)): ?>
						<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"' ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome'] ?></option>
					<?php endif; ?>
					<?php foreach($this->attaccanti as $key => $val): ?>
						<option <?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo 'selected="selected"'; ?> value="<?php echo $val['idGioc'] ?>"><?php echo $val['cognome'] ." ". $val['nome'] ?></option>
					<?php endforeach; ?>
				</select>
			<?php $j++; endfor; ?>
		</fieldset>
		<div id="dialog" title="Attenzione!" style="display:none;">
		<p>Sei sicuro di voler eliminare la squadra <br />"<?php echo $nomeSquadra ?>"?</p>
		</div>
		<fieldset class="column no-margin div-submit">
			<?php if($_GET['a'] == 'cancel'): ?>
				<input id="elimina" onclick="return false;" type="submit" name="button2" class="submit dark" value="<?php if(isset($button)) echo $button; ?>" />
			<?php else: ?>
				<input type="submit" name="button" class="submit dark" value="<?php if(isset($button)) echo $button; ?>" />
			<?php endif; ?>
				<input class="submit dark" type="reset" value="Annulla" />
			<?php if($_GET['a'] == 'cancel'): ?>
				<script type="text/javascript">
					$("#elimina").click(function () {
						$("#dialog").dialog({
							resizable: false,
							height:140,
							modal: true,
							overlay: {
								backgroundColor: '#000',
								opacity: 0.5
							},
							buttons: {
								'Elimina squadra': function() {
									$(".div-submit").append('<input style="display:none;" id="eliminaConf" type="hidden" name="button" class="submit dark" value="<?php if(isset($button)) echo $button; ?>" />');
									$("#creaSq").submit();
									$(this).dialog('close');
								},
								Annulla: function() {
									$(this).dialog('close');
								}
							}	
						});
					});
				</script>
			<?php endif; ?>
		</fieldset>
		<?php if($this->elencosquadre != FALSE): ?>
		<div class="column last">
			<div class="box2-top-sx column last">
			<div class="box2-top-dx column last">
			<div class="box2-bottom-sx column last">
			<div class="box2-bottom-dx column last">
			<div class="box-content column last">
			<?php if($_GET['a'] != 'new'): ?><h4><a href="<?php $this->linksObj->getLink('creaSquadra',array('a'=>'new','id'=>'0','lega'=>$this->lega)); ?>">Crea una squadra</a></h4><?php endif; ?>
			<h3>Elenco squadre</h3>
			<?php foreach($this->elencosquadre as $key => $val): ?>
				<div class="elencoSquadre column last">
					<p class="column last"><?php echo $val['nome']; ?></p>
					<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'cancel','id'=>$val['idUtente'],'lega'=>$this->lega)); ?>">
						<img src="<?php echo IMGSURL.'cancel.png'; ?>" alt="e" title="Cancella" />
					</a>
					<a class="right last" href="<?php echo $this->linksObj->getLink('creaSquadra',array('a'=>'edit','id'=>$val['idUtente'],'lega'=>$this->lega)); ?>">
						<img src="<?php echo IMGSURL.'edit.png'; ?>" alt="m" title="Modifica" />
					</a>
				</div>
			<?php endforeach; ?>
			</div>
			</div>
			</div>
			</div>
			</div>
		</div>
		<?php endif; ?>
	</form>
	<?php else: ?>
	<p>Parametri mancanti</p>
	<?php endif; ?>
</div>
	<?php if($_SESSION['logged'] == TRUE): ?>
	<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		<?php if(isset($_SESSION['message']) && $_SESSION['message'][0] == 0): ?>
		<div id="messaggio" class="messaggio good column last">
			<img alt="OK" src="<?php echo IMGSURL.'ok-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php elseif(isset($_SESSION['message']) && $_SESSION['message'][0] == 1): ?>
		<div id="messaggio" class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php elseif(isset($_SESSION['message']) && $_SESSION['message'][0] == 2): ?>
		<div id="messaggio" class="messaggio neut column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-big.png'; ?>" />
			<span><?php echo $_SESSION['message'][1]; ?></span>
		</div>
		<?php endif; ?>
			<?php if(isset($_SESSION['message'])): ?>
			<script type="text/javascript">
			window.onload = (function(){
 			$("#messaggio").effect("pulsate", { times: 3 }, 1000);
				$("#messaggio").click(function () {
					$("div#messaggio").fadeOut("slow");
				});
	 		});
			</script>
			<?php unset($_SESSION['message']); ?>
			<?php endif; ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
			<?php if($_SESSION['usertype'] == 'superadmin'): ?>
			<form class="column last" name="selezionaLega" action="<?php echo $this->linksObj->getLink('creaSquadra'); ?>" method="get">
				<input type="hidden" name="p" value="creaSquadra" />
				<input type="hidden" name="a" value="new" />
				<input type="hidden" name="id" value="0" />
				<fieldset class="no-margin fieldset max-large">
					<h3>Seleziona la lega</h3>
					<select name="lega" onchange="document.selezionaLega.submit();">
						<?php if(!isset($this->lega)): ?><option></option><?php endif; ?>
						<?php foreach($this->elencoLeghe as $key => $val): ?>
							<option<?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']; ?>"><?php echo $val['nomeLega']; ?></option> 
						<?php endforeach; ?>
					</select>
				</fieldset>
			</form>
			<?php endif; ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php endif; ?>
