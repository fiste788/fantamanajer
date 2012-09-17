var activeCountdown = false;
var activeWell = false;
var activeStickpanel = false;
$.isViewport = function(viewportName) {
    return $("html").hasClass("is" + viewportName.charAt(0).toUpperCase() + viewportName.slice(1));
}
syze.sizes(480, 768, 980, 1200).names({
    480:'Phone',
    768:'Tablet',
    980:'Desktop',
    1200:'LargeDesktop'
});
$('.dropdown-toggle').dropdown();
//$('#operation .fix').affix({offset:40})
var messaggio = $("#messaggio");
if(messaggio.length) {
    messaggio.effect("pulsate", {
        times: 2
    }, 1000);
    messaggio.hover(function () {
        $(this).stop().fadeTo("fast",0.2);
    },function () {
        $(this).stop().fadeTo("fast",1);
    });
    messaggio.click(function () {
        $(this).stop().fadeOut("slow");
    });
}
function enableWell() {
    if(!$.isViewport('phone') && !activeWell) {
        activeWell = true;
        var well = $(".well");
        if(well.length) {
            well.hover(function(){
                well.not(this).stop().fadeTo('fast',0.7);
            }, function(){
                well.stop().fadeTo('fast',1);
            });
        }
    }
}
function enableStickpanel() {
    if(!$.isViewport('tablet') && !$.isViewport("phone") && !activeStickpanel) {
        activeStickpanel = true;
        $("#operation .fix").stickyPanel({
            topPadding: 40,
            afterDetachCSSClass: "top",
            savePanelSpace: true
        });
    }
}
function disableStickpanel() {
    if(activeStickpanel) {
        activeStickpanel = false;
        var panel = $("[id^=stickyPanelSpace]");
        var next = panel.siblings();
        panel.remove();
        next.removeClass('top');
        next.removeAttr('style');
        $(window).unbind("scroll.stickyPanel");
    }
}
function enableCountdown() {
    if(!$.isViewport('phone') && typeof d != "undefined" && !activeCountdown) {
        activeCountdown = true;
        var interval = 1000;
        var htmlTemplate = '';
        if(Math.ceil((d.getTime() - (new Date().getTime())) / (1000 * 60 * 60 * 24)) > 0) {
            htmlTemplate = '<span class="number">%{d}</span>gg <span class="number">%{h}</span>:<span class="number">%{m}</span>';
            interval = 1000 * 60;
        } else
            htmlTemplate = '<span class="number">%{h}</span>:<span class="number">%{m}</span>:<span class="number">%{s}</span>';
        $('#countdown div').countdown({
            htmlTemplate: htmlTemplate,
            date: d,
            updatetime: interval,
            onComplete: function(){
                $(this).html("Tempo scaduto");
            },
            leadingZero:true
        });
    }
}
enableStickpanel();
enableWell();
enableCountdown();
$(window).bind("exitViewportTablet exitViewportPhone", enableStickpanel);
$(window).bind("enterViewportDesktop enterViewportLargeDesktop", enableWell);
$(window).bind("exitViewportPhone", enableCountdown);
$(window).bind("exitViewportDesktop exitViewportLargeDesktop", disableStickpanel);
if(!LOCAL)
    $.trackPage("UA-3016148-1");