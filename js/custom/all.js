$(document).ready(function(){
	$("select, input:text, input:radio, input:checkbox").uniform();
	$("#messaggioContainer").effect("pulsate", { times: 2 }, 1000, function(){
		$(".messaggio").hover(function () {
			$(this).fadeTo("fast",0.2);
		},function () {
			$(this).fadeTo("fast",1);
		});
	});
	$(".messaggio").click(function () {
		$(this).fadeOut("slow");
	});
	$("#debugShow").click(function(){
		$("#debug").slideToggle();
	});
	$("#click-menu").toggle(function(event){
		$("#menu").animate({right:'0px'},'slow');
		$("#click-menu").attr("title","Nascondi menu");
	},
	function(event){
		$("#menu").animate({right:'-300px'},'slow');
		$("#click-menu").attr("title","Mostra menu");
	});
});
