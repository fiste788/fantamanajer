<div id="lanciaScript" class="main-content">
	<ul class="column last">
		<li><a class="script" id="backup" href="#"><img class="column" alt="->" src="<?php echo IMGSURL . 'backup.png' ?>"><h3 class="column">Backup</h3></a></li>
		<li><a class="script" id="acquistaGioc" href="#"><img class="column" alt="->" src="<?php echo IMGSURL . 'transfert-big.png' ?>"><h3 class="column">Trasferimenti</h3></a></li>
		<li><a class="script" id="weeklyScript" href="#"><img class="column" alt="->" src="<?php echo IMGSURL . 'other-big.png' ?>"><h3 class="column">WeeklyScript</h3></a></li>
		<li><a class="script" id="uploadFtp" href="#"><img class="column" alt="->" src="<?php echo IMGSURL . 'other-big.png' ?>"><h3 class="column">Aggiorna versione</h3></a></li>
		<li><a class="script" id="sendMail" href="#"><img class="column" alt="->" src="<?php echo IMGSURL . 'contatti-big.png' ?>"><h3 class="column">Manda mail formazione</h3></a></li>
	</ul>
	<script type="text/javascript">
		var loadingImg = "<?php echo IMGSURL.'ajax-loader.gif' ?>"; 
		$(".script").click(function () { 
			var Url = 'index.php?p='+this.id ;
			var time = new Date();
			var time_start = time.getTime();
			var script = this.id;
			$.ajax({
				url: Url,
				type: "GET",
				beforeSend: function(){
					$("#messaggio div").remove();
					$("#messaggio").removeClass("good");
					$("#messaggio").removeClass("bad");
					$("#messaggio").removeClass("neut");
					$("#messaggio").css('display','block');
					$("#messaggio").append('<div style="text-align:center;"><img style="float:none;margin:18px 0;" src="' + loadingImg + '" / ></div>');
				},
				cache: false,
				username: "administrator",
				password: "banana",
				dataType: "html",
				complete: function(xml,text){
					var time2 = new Date();
					var time_end = time2.getTime();
					if(xml.responseText != "")
					{
						risp = $("#return",xml.responseText);
						var classe = $(risp).attr('class');
						$("#messaggio div").remove();
						$("#messaggio").css('display','none');
						if(typeof(classe) != "undefined")
						{	
							$("#messaggio").addClass(classe);
							var img = "";
							if(classe == "good")
								img = "ok";
							else
								img = "attention-bad";
							img = '<img src="<?php echo IMGSURL; ?>' + img + '.png">';
							$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>' + $(risp).html() + '</span></div>').fadeIn("slow", function() {
								if(jQuery.browser.msie)
									$("#messaggio").removeAttr('style');
							});
						}
						else
						{
							$("#messaggio div").remove();
							$("#messaggio").css('display','none');
							$("#messaggio").addClass("bad");
							img = '<img src="<?php echo IMGSURL; ?>attention-bad.png">';
							$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>Errore scononsciuto</span></div>').fadeIn("slow", function() {
								if(jQuery.browser.msie)
									$("#messaggio").removeAttr('style');
							});
						}
					}
					else
					{
						$("#messaggio div").remove();
						$("#messaggio").css('display','none');
						$("#messaggio").addClass("neut");
						img = '<img src="<?php echo IMGSURL; ?>attention.png">';
						$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>Problemi di connessione</span></div>').fadeIn("slow", function() {
							if(jQuery.browser.msie)
								$("#messaggio").removeAttr('style');
						});
					}
				}
			});
		});
		
	</script>
</div>
<div id="messaggio" class="messaggio column last" style="display:none;"></div>