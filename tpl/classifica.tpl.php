<?php $i=1; ?>
<div class="titolo-pagina">
<div class="column logo-tit">
	<img alt="->" src="<?php echo IMGSURL. 'classifica-big.png'; ?>" />
</div>
<h2 class="column">Classifica</h2>
</div>
<div id="classifica-content" class="main-content">
	<table cellpadding="0" cellspacing="0" class="column last" style="width:290px;">
		<tbody>
			<tr>
				<th style="width:20px">P.</th>
				<th style="width:180px">Nome</th>
				<th style="width:70px">Punti tot</th>
			</tr>
			<?php foreach($this->classificaDett as $key=>$val): ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $this->squadre[$key-1][1]; ?></td>
				<td><?php echo array_sum($val); ?></td>
			 </tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	<div id="tab_classifica" class="column last">
	<?php $i = 1; ?>
	<table class="column last" cellpadding="0" cellspacing="0" style="width:<?php echo count($this->classificaDett[$i])*50; ?>px;margin:0;">
		<tbody>
			<tr>
				<?php foreach($this->classificaDett[$i] as $key=>$val): ?>
					<th style="width:35px"><?php echo $key ?></th>
				<?php endforeach; ?>
			</tr>
			<?php foreach($this->classificaDett as $key=>$val): ?>
			<tr>
			<?php foreach($val as $secondKey=>$secondVal): ?>
				<td>
					<a href="index.php?p=punteggidettaglio&amp;giorn=<?php echo $secondKey; ?>&amp;squad=<?php echo $this->squadre[$key-1][0]; ?>"><?php echo $val[$secondKey]; ?></a>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php $i++; endforeach; ?>
		</tbody>
	</table>
	</div>
	<div id="placeholder" class="column last" style="width:600px;height:300px;clear:both;">&nbsp;</div>
	<div id="overview" class="column last" style="width:200px;height:100px;clear:both;">&nbsp;</div>
	<div id="legendcontainer" class="column last">&nbsp;</div>
	<div id="option" class="column">
		<div id="choices"><p>Mostra:</p></div>
		<input class="checkbox" id="all" type="checkbox" <?php if(!$_SESSION['logged']) echo 'checked="checked"';  ?> /><label>De/Seleziona tutti</label>
		<script id="source" type="text/javascript">
		<!-- 
		$(function () {
    		var datasets = {
				<?php $i=0; foreach($this->classificaDett as $key=>$val): $i++?>"<?php echo $this->squadre[$key-1][1]; ?>": {
					label: "<?php echo $this->squadre[$key-1][1]; ?>",
					data: [<?php foreach($val as $secondKey=>$secondVal): ?><?php echo '['.$secondKey.','.$val[$secondKey].']'; if(count($secondVal)-$secondKey != $secondKey-1) echo ','; endforeach; ?>]
				},
				
					<?php endforeach; ?>
				}
				
				var medie = {
					<?php foreach($this->classificaDett as $key=>$val): ?>
					<?php $media = array_sum($this->classificaDett[$key])/count($this->classificaDett[$key]) ?>
					"<?php echo $this->squadre[$key-1][1] ?>" : {label: "Media <?php echo $this->squadre[$key-1][1] ?>",data: [[1,<?php echo $media; ?>],[<?php echo count($this->classificaDett[$key]) ?>,<?php echo $media ?>]]}<?php if(count($this->classificaDett != $key)) echo ",\n"; ?>
					<?php endforeach; ?>
					}
				var options = {
					lines: { show: true },
					points: { show: true },
					legend: { noColumns: 2, container: $("#legendcontainer") },
					xaxis: { tickDecimals: 0 },
					yaxis: { min: 0 },
					shadowSize:1,
					selection: { mode: null }
				};

				// hard-code color indices to prevent them from shifting as
				// countries are turned on/off
				var i = 0;
				$.each(datasets, function(key, val) {
				val.color = i;
				++i;
				});

				// insert checkboxes
				var choiceContainer = $("#choices");
				$.each(datasets, function(key, val) {
				choiceContainer.append('<div class="formbox"><input class="checkall checkbox" type="checkbox" name="' + key +
				'" checked="checked" /><label>' + val.label + '</label></div>');
				});
				
				<?php if($_SESSION['logged'] == TRUE): ?>
					choiceContainer.find("input[name!='<?php echo $this->squadre[$_SESSION['idsquadra']-1][1]; ?>']").attr ('checked','');
				<?php endif; ?>
					choiceContainer.find("input").click(plotAccordingToChoices);

				var placeholder = $("#placeholder");
				function plotAccordingToChoices() {
					var data = [];
					$("#legendcontainer").empty();
					var j = null;
					var k = 0;
					choiceContainer.find("input:checked").each(function () {
						var key = $(this).attr("name");
						if (key && datasets[key]) {
							data.push(datasets[key]);
							j = key;
							k++;}
					});
					//
					if (k == 1)
						data.push(medie[j]);
						
					plot = $.plot($("#placeholder"), data, options);
					
					var overview = $.plot($("#overview"), data, {
 lines: { show: true, lineWidth: 1 },
 shadowSize: 0,
 xaxis: { ticks: 4 },
yaxis: { min: 0},
selection: { mode: "x" },
legend: { show:false }
});

// now connect the two
var internalSelection = false;

$("#placeholder").bind("selected", function (event, area) {
$("#legendcontainer").empty();
// do the zooming
plot = $.plot($("#placeholder"), data,
$.extend(true, {}, options, {
xaxis: { min: area.x1, max: area.x2 }
}));

if (internalSelection)
return; // prevent eternal loop
internalSelection = true;
overview.setSelection(area);
internalSelection = false;
});

$("#overview").bind("selected", function (event, area) {
$("#legendcontainer").empty();
if (internalSelection)
return;
internalSelection = true;
plot.setSelection(area);
internalSelection = false;
});

				}

				$("#all").click(function()
				{
					var checked_status = this.checked;
					$("input.checkall").each(function()
					{
						this.checked = checked_status;
					});
					plotAccordingToChoices();
				});
  
				plotAccordingToChoices();
 /*$("#clearSelection").click(function () {
 plot.clearSelection();
 });*/
});
-->
		</script>
		</div>
</div>
<div id="squadradett" class="column last">
	<div class="box2-top-sx column last">
	<div class="box2-top-dx column last">
	<div class="box2-bottom-sx column last">
	<div class="box2-bottom-dx column last">
	<div class="box-content column last">
		<?php if($_SESSION['logged'] == TRUE): ?>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		<?php endif; ?>
		<form class="column last" name="classifica_giornata" action="index.php?p=classifica" method="post">
			<fieldset class="no-margin fieldset  max-large">
				<h3 class="no-margin">Guarda la classifica alla giornata</h3>
					<select name="giorn" onchange="document.classifica_giornata.submit();">
						<?php for($j = $this->giornate ; $j  > 0 ; $j--): ?>
							<option <?php if($this->getGiornata == $j) echo "selected=\"selected\"" ?>><?php echo $j; ?></option>
						<?php endfor; ?>
				</select>
			</fieldset>
		</form>
	</div>
	</div>
	</div>
	</div>
	</div>
</div>

