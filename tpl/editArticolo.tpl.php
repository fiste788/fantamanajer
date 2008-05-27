<?php
if(isset($this->articolo))
	$title=$this->articolo[0]['title'];
if(isset($_POST['title']))
	$title=$_POST['title'];
if(isset($this->articolo))
	$abstract=$this->articolo[0]['abstract'];
if(isset($_POST['abstract']))
	$abstract=$_POST['abstract'];
if(isset($this->articolo))
	$text=$this->articolo[0]['text'];
if(isset($_POST['text']))
	$text=$_POST['text'];
switch($_GET['a'])
{
	case 'cancel': $button = 'Rimuovi'; ?>
	<div class="titolo-pagina">
		<div class="column logo-tit">
			<img align="left" src="<?php echo IMGSURL.'cancel-big.png'; ?>" alt="Logo Squadre" />
		</div>
		<h2 class="column">Cancellazione conferenza</h2>
	</div>
	<?php 	break; endcase;
	case 'edit': $button = 'Modifica'; ?>
	<div class="titolo-pagina">
		<div class="column logo-tit">
			<img align="left" src="<?php echo IMGSURL.'edit-big.png'; ?>" alt="Logo Squadre" />
		</div>
		<h2 class="column">Aggiornamento conferenza</h2>
	</div>
	<?php	break; endcase;
	case 'new': $button = 'Inserisci';?>
	<div class="titolo-pagina">
		<div class="column logo-tit">
			<img align="left" src="<?php echo IMGSURL.'rose-big.png'; ?>" alt="Logo Squadre" />
		</div>
		<h2 class="column">Inserimento conferenza</h2>
	</div>
	<?php break; endcase;
	default: $button = 'Errore';break;
}
?>
<div id="articoloedit" class="main-content">
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
-->
</script>
<form name="editConfStampa" method="post" action="index.php?p=editArticolo&amp;a=<?php echo $_GET['a'] ?><?php if(isset($_GET['id'])): ?>&amp;id=<?php echo $_GET['id']; endif; ?>">
	<fieldset class="no-margin">
			<div class="formbox">
			  <label for="title">Titolo: *</label>
			  <input <?php if($_GET['a'] == 'cancel') echo 'disabled="disabled"'; ?> class="text" type="text" maxlength="30" name="title" id="title" <?php if(isset($title)): ?>value="<?php echo $title ?>"<?php endif; ?>/>
			</div>
			<div class="formbox">
			  <label for="abstract">Sottotitolo:</label>
			  <textarea class="column" <?php if($_GET['a'] == 'cancel') echo 'disabled="disabled"'; ?> rows="3" cols="50" onkeyup="return ismaxlength(this, 75);" name="abstract" id="abstract"><?php if(isset($abstract)) echo $abstract; ?></textarea>
				<input class="column text disabled" id="abstractCont" type="text" disabled="disabled" value="<?php if(isset($abstract)) echo 75 - mb_strlen($abstract,'UTF-8'); else echo '75';  ?>" />
			</div>
			<div class="formbox">
			<?php if($_GET['a'] != 'cancel'): ?>
				<div id="emoticons">
				<?php foreach($this->emoticons as $key=>$val):?>
					<img class="emoticon" src="<?php echo IMGSURL.'emoticons/' . $val['name'] . '.png' ?>" title="<?php echo $val['title'] ?>" alt="<?php echo $val['cod'] ?>" onclick="document.getElementById('text').value +=  '<?php echo $val['cod'] ?>';return ismaxlength(document.getElementById('text'), 500);" />
				<?php endforeach; ?>
				</div>			
			<?php endif;?>
			<label for="text">Testo: *</label>
			<textarea class="column" <?php if($_GET['a'] == 'cancel') echo 'disabled="disabled"'; ?> rows="12" cols="50" onkeyup="return ismaxlength(this, 500);" name="text" id="text"><?php if(isset($text)) echo trim($text); ?></textarea>
			<input class="column text disabled" id="textCont" type="text" disabled="disabled" value="<?php if(isset($text)) echo 500-mb_strlen($text);else echo '500'; ?>" />
			</div>
		</fieldset>
		<fieldset class="column">
			<input class="submit dark" type="submit" name="submit" value="<?php echo $button; ?>" />
			<?php if($_GET['a'] != 'cancel'): ?>
				<input class="submit dark" type="reset" value="Annulla" />
			<?php endif; ?>
			<p>(*) I campi contrassegnati con l'asterisco sono obbligatori</p>
	</fieldset>
</form>
</div>

<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
	<?php if(isset($this->messaggio) && $this->messaggio[0] == 1): ?>
		<div class="messaggio bad column last">
			<img alt="!" src="<?php echo IMGSURL.'attention-bad-big.png'; ?>" />
			<span><?php echo $this->messaggio[1]; ?></span>
		</div>
		<script type="text/javascript">
		$(document).ready(function() {$('.messaggio').show('pulsate',{times: 3 }); });
		$(".messaggio").click(function () {
			$("div.messaggio").fadeOut("slow");
		});
		</script>
	<?php endif; ?>
	<?php if($_SESSION['logged'] == TRUE): ?>
		<?php require (TPLDIR.'operazioni.tpl.php'); ?>
	<?php endif; ?>
	<div id="operazioni-other" class="column last">
		<ul class="operazioni-content">
			<li><a class="undo-punteggi-active column last operazione" href="#" onclick="history.back()">Indietro</a></li>
		</ul>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$("a.toggle").click(function() {
				$("div.operazioni-content").slideToggle("slow");
			})
		});
	</script>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>
