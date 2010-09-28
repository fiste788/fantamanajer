$(window).resize(function() {
	var width = $("body").width();
	var element = $("#topRightBar");
	if(element.length > 0)
	{
		if(width < 1172)
			$("#login").css("margin-right",(1172 - width - 90) + ((width - 992) / 2) + "px");
		else
			$("#login").css("margin-right","0px");
	}
});
$(document).ready(function(){
	var width = $("body").width();
	var element = $("#topRightBar");
	if(element.length > 0)
	{
		if(width < 1172)
			$("#login").css("margin-right",(1172 - width - 90) + ((width - 992) / 2) + "px");
		else
			$("#login").css("margin-right","0px");
	}
	$("#menu").css("top","-" + $("#menu").height() + "px");
	$("select, input:text, input:radio, input:checkbox, textarea").uniform();
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
	$("#click-menu").toggle(
	function(event){
		$("#menu").animate({right:'0px',top:'0px'},'slow');
		$("#topRightBar").attr("title","Nascondi menu");
	},
	function(event){
		$("#menu").animate({right:'-' + $("#menu").width() + 'px',top:'-' + $("#menu").height() + 'px'},'slow');
		$("#topRightBar").attr("title","Mostra menu");
	});
});
;
