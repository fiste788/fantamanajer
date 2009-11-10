<div id="newsletter" class="main-content">
	<?php if(isset($this->lega)): ?>
	<form class="column last" name="newsletter" action="<?php echo $this->linksObj->getLink('newsletter'); ?>" method="post">
		<fieldset class="column last">
			<input type="hidden" name="lega" value="<?php echo $this->lega; ?>">
			<div class="formbox">
				<label for="oggetto">Oggetto:</label>
				<input class="text" id="oggetto" type="text" name="object" maxlength="30"<?php if(isset($_POST['object'])) echo ' value="' . $_POST['object'] .'"'; ?> />
			</div>
			<?php if($this->lega == 0): ?>
			<div class="formbox">
				<label for="selezione">Leghe:</label>
				<select id="selezione" name="selezione[]" multiple="multiple" size="6" class="column newsletterBox">
					<?php foreach($this->elencoLeghe as $key => $val): ?>
								<option<?php if(isset($_POST['selezione']) && array_search($val['idLega'],$_POST['selezione']) !== FALSE) echo ' selected="selected"'; ?> value="<?php echo $val['idLega'] ?>"><?php echo $val['nomeLega'] ?></option>
					<?php endforeach; ?>
				</select>
				<div class="selectAll column">
					<a href="#" onclick="setSelectOptions(true)">Seleziona tutto</a> /
					<a href="#" onclick="setSelectOptions(false)">Deseleziona tutto</a>
				</div>
			</div>
			<?php else: ?>
			<div class="formbox">
				<label for="selezione">Squadre:</label>
				<?php if($this->elencoSquadre == FALSE): ?>
				<select disabled="disabled" id="selezione" multiple="multiple" size="6" class="column newsletterBox">
					<option value="NULL">Nessuna squadra presente</option>
				<?php else: ?>
				<select id="selezione" name="selezione[]" multiple="multiple" size="6" class="column newsletterBox">
					<?php foreach($this->elencoSquadre as $key => $val): ?>
						<option<?php if(isset($_POST['selezione']) && array_search($val['idUtente'],$_POST['selezione']) !== FALSE) echo ' selected="selected"'; ?> value="<?php echo $val['idUtente'] ?>"><?php echo $val['nome'] ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
				</select>
				<?php if($this->elencoSquadre != FALSE): ?>
				<div class="selectAll column">
					<a href="#" onclick="setSelectOptions(true)">Seleziona tutto</a> /
					<a href="#" onclick="setSelectOptions(false)">Deseleziona tutto</a>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<div class="formbox">
				<label for="testo">Testo:</label>
				<textarea class="column" id="testo" rows="15" cols="50" name="text" onkeyup="return ismaxlength(this, 1000);"><?php if(isset($_POST['text'])) echo $_POST['text']; ?></textarea>
				<input class="column text disabled" id="textCont" type="text" disabled="disabled" value="<?php if(isset($_POST['text'])) echo 1000-mb_strlen($_POST['text']);else echo '1000'; ?>" />
			</div>
			<div class="formbox">
				<label>Tipologia:</label>
				<input class="column radio" type="radio" value="C" name="type"<?php if(isset($_POST['type']) && $_POST['type'] == 'C') echo 'checked="checked"'; ?>><label>Comunicazione</label>
				<input class="column radio" type="radio" value="N" name="type"<?php if(isset($_POST['type']) && $_POST['type'] == 'N') echo 'checked="checked"'; ?>><label>Newsletter</label>
			</div>
			<div class="formbox">
				<label>Crea conferenza:</label>
				<input class="column checkbox" type="checkbox" name="conferenza"<?php if(isset($_POST['conferenza'])) echo 'checked="checked"'; ?>>
			</div>
		</fieldset>
		<fieldset class="column last">
			<input type="submit" name="button" class="column submit dark" value="Invia" />
		</fieldset>
		<script language="javascript" type="text/javascript">
			<!--
				function ismaxlength(obj,maxLenght){
				var mlength=maxLenght;
				if (obj.getAttribute && obj.value.length>mlength) {
					var cursor = obj.selectionEnd;
					var scroll = obj.scrollTop;
					alert("Hai raggiunto il massimo di caratteri consentito")
					obj.value=obj.value.substring(0,mlength);
					obj.selectionEnd = cursor;
					obj.scrollTop = scroll;
				}
				 document.getElementById(obj.name + 'Cont').value = mlength - obj.value.length
			}
			function setSelectOptions(do_check)
			{
				var selectObject = document.forms['newsletter'].elements['selezione'];
				var selectCount = selectObject.length;
				for (var i = 0; i < selectCount; i++) {
					selectObject.options[i].selected = do_check;
				}
			return true;
			}
			-->
		</script>
	</form>
	<?php endif; ?>
</div>
<div id="squadradett" class="column last">
		<?php if($_SESSION['roles'] == '2'): ?>
		<form class="right last" name="newsletterData" action="<?php echo $this->linksObj->getLink('newsletter'); ?>" method="post">
			<fieldset class="no-margin fieldset">
				<h3 class="no-margin">Seleziona la lega</h3>
					<select name="lega" onchange="document.newsletterData.submit();">
					<?php if(!isset($this->lega)): ?><option></option><?php endif; ?>
						<option<?php if(isset($this->lega) && $this->lega == 0) echo ' selected="selected"'; ?> value="0">Tutte le leghe</option>
					<?php foreach($this->elencoLeghe as $key => $val): ?>
						<option <?php if($this->lega == $val['idLega']) echo ' selected="selected"'; ?> value="<?php echo $val['idLega']?>"><?php echo $val['nomeLega']?></option>
					<?php endforeach;?>
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
