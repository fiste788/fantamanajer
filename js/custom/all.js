	var boxNotifiche = $('.boxNotifiche');
	$('#notifiche').click(function (event) {
		if(!boxNotifiche.is(':visible')) {
			boxNotifiche.show();
			$(this).addClass('hover');
		}
		else {
			boxNotifiche.hide();
			$(this).removeClass('hover');
		}
	});
	$("a.level",$("nav .subnav").parent()).click(function(e) { //When trigger is clicked...
		e.preventDefault();
		var subnav = $(this).parent().find("ul.subnav");
        if(!subnav.is(":visible")) {
			$(".subnav").hide();
			$(".hover").removeClass("hover");
			subnav.show();
			$(this).addClass("hover");
		} else {
			subnav.hide();
			$(this).removeClass('hover');
		}
    });
	$('body').click(function(event) {
		if (!$(event.target).next('.subnav').length) {
			$(".subnav").hide();
			$("li .hover").removeClass('hover');
		}
		if (!$(event.target).parent().next('.boxNotifiche').length) {
			boxNotifiche.hide();
			$("#notifiche").removeClass('hover');
		};
	});
	var stickyPanelOptions = {
		topPadding: 32,
		afterDetachCSSClass: "top",
		savePanelSpace: true
	};
	$("#operation .fix").stickyPanel(stickyPanelOptions);
	var messaggio = $("#messaggio");
	messaggio.effect("pulsate", { times: 2 }, 1000, function(){
		messaggio.hover(function () {
			$(this).fadeTo("fast",0.2);
		},function () {
			$(this).fadeTo("fast",1);
		});
	});
	messaggio.click(function () {
		$(this).fadeOut("slow");
	});
	$("#debugShow").click(function(){
		$("#debug").slideToggle();
	});
var interval = 1000;
var htmlTemplate = '';
if(Math.ceil((d.getTime() - (new Date().getTime())) / (1000 * 60 * 60 * 24)) > 0) {
	htmlTemplate = '<span class="number">%{d}</span>gg <span class="number">%{h}</span>:<span class="number">%{m}</span>';
	interval = 1000 * 60;
} else {
	htmlTemplate = '<span class="number">%{h}</span>:<span class="number">%{m}</span>:<span class="number">%{s}</span>';
}
$('#countdown div').countdown({
	htmlTemplate: htmlTemplate,
	date: d,
	updatetime: interval,
	onComplete: function(){
        $(this).html("Tempo scaduto");
    },
	leadingZero:true
});
