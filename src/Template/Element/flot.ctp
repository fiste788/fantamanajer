<div id="plot-container" class="hidden-xs">
	<div id="placeholder" style="height:300px"></div>
	<div id="placeholder-overview" style="width:200px;height:100px"></div>
	<p>Seleziona sulla miniatura una parte di grafico per ingrandirla.</p>
	<p id="selection">&nbsp;</p>
	<a id="clear-selection" class="btn btn-danger">Cancella selezione</a>
</div>
<?php $this->Html->script('../components/flot/jquery.flot', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('../components/flot/jquery.flot.selection', ['block' => 'scriptBottom']); ?>
<?php $this->Html->script('graph2', ['block' => 'scriptBottom']); ?>