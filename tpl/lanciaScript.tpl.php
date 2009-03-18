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
		var loadingImg = "<?php echo IMGSURL.'ajax-loader.gif' ?>"; 
		$(".script").click(function () { 
			var Url = this.id + '.html';
			var time = new Date();
			var time_start = time.getTime();
			var script = this.id;
			$.ajax({
				url: Url,
				type: "post",
				beforeSend: function(){
					$("#messaggio div").remove();
					$("#messaggio").removeClass("good");
					$("#messaggio").removeClass("bad");
					$("#messaggio").css('display','block');
					$("#messaggio").append('<div style="text-align:center;"><img style="float:none;margin:18px 0;" src="' + loadingImg + '" / ></div>');
				},
				cache: false,
				username: "administrator",
				password: "banana",
				dataType: "xml",
				complete: function(xml,text){
					$("#messaggio div").fadeOut(function (){
						$("#messaggio").css('display','none');
						var time2 = new Date();
						var time_end = time2.getTime();
						if(xml.responseText != "")
						{
							risp = $("#return",xml.responseText);
							var classe = $(risp).attr('class');
							$("#messaggio").addClass(classe);
							$("#messaggio div").remove();
							var img = "";
							if(classe == "good")
								img = "ok";
							else
								img = "attention-bad";
							img = '<img src="<?php echo IMGSURL; ?>' + img + '-big.png">';
							$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>' + $(risp).html() + '</span></div>').fadeIn( function() {
								if(jQuery.browser.msie)
									$("#messaggio").removeAttr('style');
							});
						}
						else
						{
							$("#messaggio").addClass("neut");
							$("#messaggio div").remove();
							img = '<img src="<?php echo IMGSURL; ?>attention-big.png">';
							$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>Impossibile recuperare dati sull\'esecuzione</span></div>').fadeIn( function() {
								if(jQuery.browser.msie)
									$("#messaggio").removeAttr('style');
							});
						}
					});
				}
			});
		});
		
		$("#messaggio").bind("click",function () {
			$("div#messaggio").fadeOut("slow");
		});
	</script>
</div>
<div id="squadradett" class="column last">
		<div class="box2-top-sx column last">
		<div class="box2-top-dx column last">
		<div class="box2-bottom-sx column last">
		<div class="box2-bottom-dx column last">
		<div class="box-content column last">
		  <div id="messaggio" class="messaggio column last" style="display:none;">
  		</div>
			<?php require (TPLDIR.'operazioni.tpl.php'); ?>
		</div>
		</div>
		</div>
		</div>
		</div>
	</div>
