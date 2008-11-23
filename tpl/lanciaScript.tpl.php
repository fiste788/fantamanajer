<div class="titolo-pagina">
	<div class="column logo-tit">
		<img align="left" src="<?php echo IMGSURL.'other-big.png'; ?>" alt="Logo Squadre" />
	</div>
	<h2 class="column">Esegui script</h2>
</div>
<div id="creaSquadre" class="main-content">
	<ul>
		<li><a class="script" id="backup" href="#">Backup</a></li>
		<li><a class="script" id="acquistaGioc" href="#">Trasferimenti</a></li>
		<li><a class="script" id="weeklyScript" href="#">WeeklyScript</a></li>
		<li><a class="script" id="sendMail" href="#">Manda mail formazione</a></li>
	</ul>
	<script type="text/javascript">
		var loadingImg = "<?php echo IMGSURL.'lightbox-ico-loading.gif' ?>"; 
		$(".script").click(function () { 
			var Url = this.id + '.html';
			var time = new Date();
			var time_start = time.getTime();
			var script = this.id;
			$.ajax({
				url: Url,
				type: "post",
				beforeSend: function(){
					$(".messaggio div").remove();
					$(".messaggio").removeClass("good");
					$(".messaggio").removeClass("bad");
					$(".messaggio").append('<div><img src="' + loadingImg + '"</div>');
				},
				cache: false,
				username: "administrator",
				password: "banana",
				success: function(html,text){
					$(".messaggio div").fadeOut(function (){
						$(".messaggio").css('display','none');
						$(".messaggio").addClass('good');
						$(".messaggio div").remove();
						var time2 = new Date();
						var time_end = time2.getTime();
						$(".messaggio").append('<div title="Tempo di esecuzione ' + (time_end-time_start) + 'ms Risposta del server: ' + text + '"><img src="<?php echo IMGSURL.'ok-big.png'; ?> "><span>Script ' + script + ' eseguito con successo</span></div>').fadeIn( function() {
							if(jQuery.browser.msie)
								$(".messaggio").removeAttr('style');	
						});
					});
				},
				error:  function(){
					$(".messaggio div").fadeOut(function (){
						$(".messaggio").css('display','none');
						$(".messaggio").addClass('bad');
						$(".messaggio div").remove();
						var time2 = new Date();
						var time_end = time2.getTime();
						$(".messaggio").append('<div title="Tempo di esecuzione ' + (time_end-time_start) + 'ms Risposta del server: ' + text + '"><img src="<?php echo IMGSURL.'attention-bad-big.png'; ?> "><span>Errore nell\'esecuzione dello script ' + script + '</span></div>').fadeIn(function() {
							if(jQuery.browser.msie)
								$(".messaggio").removeAttr('style');
						});
					});
				}
			});
		});
		
		$(".messaggio").bind("click",function () {
			$("div.messaggio").fadeOut("slow");
		});
	</script>
</div>
<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		  <div class="messaggio column last" style="display:none;">
  		</div>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
