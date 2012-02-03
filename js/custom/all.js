	
	$('#topbar').dropdown();
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
