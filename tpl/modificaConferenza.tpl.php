<div id="articoloedit" class="main-content">
	<script language="javascript" type="text/javascript">
	<!--
	function ismaxlength(obj,maxLenght)
	{
		var mlength=maxLenght;
		if (obj.getAttribute && obj.value.length>mlength) 
		{
			var cursor = obj.selectionEnd;
			var scroll = obj.scrollTop;
			alert("Hai raggiunto il massimo di caratteri consentito");
			obj.value=obj.value.substring(0,mlength);
			obj.selectionEnd = cursor;
			obj.scrollTop = scroll;
		}
		 document.getElementById(obj.name + 'Cont').value = mlength - obj.value.length
	}
	
	function addEmoticon(insert){
		obj = document.getElementById('text');
		var fullText = obj.value;
		var subText = fullText;
		var scroll = obj.scrollTop;
		var end = obj.selectionEnd;
		subText = subText.substring(0,obj.selectionStart);
		subText += ' '+insert;
		var appo = subText.length;
		subText += fullText.substring(end);
		obj.value = subText;
		obj.selectionEnd = appo;
		obj.scrollTop = scroll;
	}
	-->
	</script>
	<form name="editConfStampa" method="post" action="<?php echo $this->linksObj->getLink('modificaConferenza',$this->goTo); ?>">
		<fieldset class="no-margin">
				<div class="formbox">
					<label for="title">Titolo: *</label>
					<input<?php if($this->action == 'cancel') echo ' disabled="disabled"'; ?> class="text" type="text" maxlength="30" name="title" id="title" <?php if(isset($this->title)): ?>value="<?php echo $this->title ?>"<?php endif; ?>/>
				</div>
				<div class="formbox">
					<label for="abstract">Sottotitolo:</label>
					<textarea class="column"<?php if($this->action == 'cancel') echo ' disabled="disabled"'; ?> rows="3" cols="80" onkeyup="return ismaxlength(this, 75);" name="abstract" id="abstract"><?php if(isset($this->abstract)) echo $this->abstract; ?></textarea>
					<input class="column text disabled" id="abstractCont" type="text" disabled="disabled" value="<?php if(isset($this->abstract)) echo 75 - mb_strlen($this->abstract,'UTF-8'); else echo '75';  ?>" />
				</div>
				<div class="formbox">
				<?php if($this->action != 'cancel'): ?>
					<div id="emoticons">
					<?php foreach($this->emoticons as $key => $val):?>
						<img class="emoticon" src="<?php echo IMGSURL.'emoticons/' . $val['name'] . '.png' ?>" title="<?php echo $val['title'] ?>" alt="<?php echo $val['cod'] ?>" onclick="addEmoticon('<?php echo addslashes(stripslashes($val['cod'])) ?>');return ismaxlength(document.getElementById('text'), 1000);" />
					<?php endforeach; ?>
					</div>			
				<?php endif;?>
				<label for="text">Testo: *</label>
				<textarea class="column"<?php if($this->action == 'cancel') echo ' disabled="disabled"'; ?> rows="12" cols="80" onkeyup="return ismaxlength(this, 1000);" name="text" id="text"><?php if(isset($this->text)) echo trim($this->text); ?></textarea>
				<input class="column text disabled" id="textCont" type="text" disabled="disabled" value="<?php if(isset($this->text)) echo 1000-mb_strlen($this->text);else echo '1000'; ?>" />
				</div>
			</fieldset>
			<fieldset class="column">
				<input class="submit dark" type="submit" name="submit" value="<?php echo $this->button; ?>" />
				<?php if($this->action != 'cancel'): ?>
					<input class="submit dark" type="reset" value="Annulla" />
				<?php endif; ?>
				<p>(*) I campi contrassegnati con l'asterisco sono obbligatori</p>
		</fieldset>
	</form>
</div>
