<?php if(isset($_GET['squadra'])) $squadra = $_GET['squadra']; else $squadra=$_SESSION['idsquadra']; ?>
<div id="operazioni" class="column last">
	<a title="Nascondi" href="#oper" class="toggle operazioni-title column last">Operazioni</a>
		<ul class="operazioni-content">
		<?php if(!$this->timeout): ?>
			<li><a class="imp-formazione column last operazione" href="index.php?p=formazioniAll">Guarda la formazione</a></li>
		<?php else: ?>
			<li><a class="imp-formazione column last operazione" href="index.php?p=formazione">Imposta la formazione</a></li>
		<?php endif; ?>
			<li><a class="new-stampa column last operazione" href="index.php?p=editArticolo&amp;a=new">Rilascia una conferenza</a></li>
			<li><a class="see-transfert column last operazione" href="index.php?p=trasferimenti&amp;squad=<?php echo $squadra; ?>">Guadra i trasferimenti</a></li>
			<li><a class="see-freeplayer column last operazione" href="index.php?p=freeplayer">Guadra i giocatori liberi</a></li>
			<li><a class="see-premi column last operazione" href="index.php?p=premi">Guadra i premi</a></li>
		</ul>
	</div>
	<script type="text/javascript">
			$(document).ready(function() {
				$("a.toggle").click(function() {
					$(".operazioni-content").slideToggle("slow");
				})
			});
		</script>
