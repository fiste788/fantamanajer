$(document).ready(function () {
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
				$("#messaggio").append('<div style="text-align:center;"><img style="float:none;margin:18px 0;" src="' + imgUrl + 'ajax-loader.gif" / ></div>');
			},
			cache: false,
			username: "administrator",
			password: "banana",
			dataType: "xml",
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
						img = '<img src="' + imgUrl + img + '.png">';
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
						img = '<img src="' + imgUrl + 'attention-bad.png">';
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
					img = '<img src="' + imgUrl + 'attention.png">';
					$("#messaggio").append('<div title="Tempo di esecuzione: ' + (time_end-time_start) + 'ms">' + img + '<span>Problemi di connessione</span></div>').fadeIn("slow", function() {
						if(jQuery.browser.msie)
							$("#messaggio").removeAttr('style');
					});
				}
			}
		});
	});
});