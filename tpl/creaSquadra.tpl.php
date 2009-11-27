<?php $j = 0; ?>
<?php if($this->lega != NULL && isset($this->id) && isset($this->action)): ?>
<form class="column" action="<?php echo $this->linksObj->getLink('creaSquadra',$this->goTo); ?>" method="post">
	<fieldset class="column no-margin">
		<input type="hidden" name="a" value="<?php if(isset($this->action)) echo $this->action; ?>" />
		<input type="hidden" name="id" value="<?php if(isset($this->id)) echo $this->id; ?>" />
		<h3>Informazioni generali</h3>
		<div class="formbox">
			<label for="nomeSquadra">Nome della squadra:</label>
			<input<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> class="text" id="nomeSquadra" name="nome" type="text" maxlength="40" <?php if(isset($this->datiSquadra['nome'])) $nomeSquadra = $this->datiSquadra['nome']; if(isset($_POST['nome'])) $nomeSquadra = $_POST['nome']; if(isset($nomeSquadra)) echo 'value="' . $nomeSquadra . '"'; ?> />
		</div>
		<div class="formbox">
			<label for="username">Username:</label>
			<input<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> class="text" id="username" name="usernamenew" type="text" maxlength="15" <?php if(isset($this->datiSquadra['username'])) $username = $this->datiSquadra['username']; if(isset($_POST['usernamenew'])) $username = $_POST['usernamenew']; if(isset($username)) echo 'value="'. $username .'"'; ?> />
		</div>
		<div class="formbox">
			<label for="email">Email:</label>
			<input<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> class="text" id="mail" name="mail" type="text" maxlength="30" <?php if(isset($this->datiSquadra['mail'])) $mail = $this->datiSquadra['mail']; if(isset($_POST['mail'])) $mail = $_POST['mail']; if(isset($mail))echo 'value="'. $mail .'"'; ?> />
		</div>
		<div class="formbox">
			<label for="amministratore">Amministratore?</label>
			<input<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> class="checkbox" id="amministratore" name="amministratore" type="checkbox" <?php if(isset($this->datiSquadra['amministratore']) && $this->datiSquadra['amministratore'] != 0) $admin = $this->datiSquadra['amministratore']; if(isset($_POST['amministratore'])) $admin = $_POST['amministratore']; if(isset($admin)) echo 'checked="checked"'; ?> />
		</div>
	</fieldset>
	<fieldset id="panchinari">
		<h4 class="bold no-margin">Portieri</h4>
		<hr />
		<?php for($i = 0;$i < 3; $i++): ?>
			<select<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> name="giocatore[]">
				<option></option>
				<?php if(isset($this->giocatori) && !empty($this->giocatori)): ?>
					<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"'; ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome']; ?></option>
				<?php endif; ?>
				<?php foreach($this->portieri as $key => $val): ?>
					<option value="<?php echo $val['idGioc']; ?>"<?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo ' selected="selected"'; ?>><?php echo $val['cognome'] ." ". $val['nome']; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
		<h4 class="bold no-margin">Difensori</h4>
		<hr />
		<?php for($i = 0;$i < 8; $i++): ?>
			<select<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> name="giocatore[]">
				<option></option>
				<?php if(isset($this->giocatori) && !empty($this->giocatori)): ?>
					<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"'; ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome'] . ' ' . $this->giocatori[$j]['nome']; ?></option>
				<?php endif; ?>
				<?php foreach($this->difensori as $key => $val): ?>
					<option<?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo ' selected="selected"'; ?> value="<?php echo $val['idGioc']; ?>"><?php echo $val['cognome'] . ' ' . $val['nome']; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
		<h4 class="bold no-margin">Centrocampisti</h4>
		<hr />
		<?php for($i = 0;$i < 8; $i++): ?>
			<select<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> name="giocatore[]">
				<option></option>
				<?php if(isset($this->giocatori) && !empty($this->giocatori)): ?>
					<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"'; ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome']; ?></option>
				<?php endif; ?>
				<?php foreach($this->centrocampisti as $key => $val): ?>
					<option<?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo ' selected="selected"'; ?> value="<?php echo $val['idGioc']; ?>"><?php echo $val['cognome'] ." ". $val['nome']; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
		<h4 class="bold no-margin">Attaccanti</h4>
		<hr />
		<?php for($i = 0;$i < 6; $i++): ?>
			<select<?php if($this->button == 'Cancella') echo ' disabled="disabled"'; ?> name="giocatore[]">
				<option></option>
				<?php if(isset($this->giocatori) && !empty($this->giocatori)): ?>
					<option<?php if(!isset($_POST['giocatore'][$j])) echo ' selected="selected"'; ?> value="<?php echo $this->giocatori[$j]['idGioc']; ?>"><?php echo $this->giocatori[$j]['cognome']. ' ' . $this->giocatori[$j]['nome']; ?></option>
				<?php endif; ?>
				<?php foreach($this->attaccanti as $key => $val): ?>
					<option<?php if(isset($_POST['giocatore'][$j]) && $_POST['giocatore'][$j] == $val['idGioc']) echo ' selected="selected"'; ?> value="<?php echo $val['idGioc']; ?>"><?php echo $val['cognome'] ." ". $val['nome']; ?></option>
				<?php endforeach; ?>
			</select>
		<?php $j++; endfor; ?>
	</fieldset>
	<div id="dialog" title="Attenzione!" style="display:none;">
	<p>Sei sicuro di voler eliminare la squadra <br />"<?php echo $nomeSquadra; ?>"?</p>
	</div>
	<fieldset class="column no-margin div-submit">
		<?php if($this->button == 'Cancella'): ?>
			<input id="elimina" onclick="return false;" type="submit" name="button2" class="submit dark" value="<?php if(isset($this->button)) echo $this->button; ?>" />
		<?php else: ?>
			<input type="submit" name="button" class="submit dark" value="<?php if(isset($this->button)) echo $this->button; ?>" />
		<?php endif; ?>
			<input class="submit dark" type="reset" value="Annulla" />
		<?php if($this->button == 'Cancella'): ?>
			<script type="text/javascript">
			// <![CDATA[
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
			// ]]>
			</script>
		<?php endif; ?>
	</fieldset>
</form>
<?php else: ?>
<p>Parametri mancanti</p>
<?php endif; ?>
