var timestamp = $('#countdown').data('data-fine'),
	d = new Date(timestamp * 1000),
	activeCountdown = false,
	activeWell = false,
	activeStickpanel = false,
	$dropdown = $('.dropdown-toggle'),
	$operation = $('#operation');
if($dropdown.length)
	$dropdown.dropdown();
$.isViewport = function(viewportName) {
    return $('html').hasClass('is' + viewportName);
}
syze.sizes(320,480, 768, 980).names({
    320:'small-phone',
    480:'phone',
    768:'tablet',
    980:'desktop'
});
$('.dropdown-menu').find('form').click(function (e) {
    e.stopPropagation();
});

//$('#operation .fix').affix({offset:40})
var $messaggio = $('#messaggio');
if($messaggio.length) {
    $messaggio.effect("pulsate", {
        times: 2
    }, 1000);
    $messaggio.hover(function () {
        $(this).stop().fadeTo('fast',0.2);
    },function () {
        $(this).stop().fadeTo('fast',1);
    });
    $messaggio.click(function () {
        $(this).unbind().stop().fadeOut('slow');
    });
}
function enableWell() {
    if($.isViewport('desktop') && !activeWell) {
        activeWell = true;
        var $well = $('.well');
        if($well.length) {
            $well.hover(function(){
                $well.not(this).stop().fadeTo('fast',0.7);
            }, function(){
                $well.stop().fadeTo('fast',1);
            });
        }
    }
}
function enableStickpanel() {
    if($.isViewport('desktop') && !activeStickpanel) {
        activeStickpanel = true;
        $operation.find('.fix').stickyPanel({
            topPadding: 41,
            afterDetachCSSClass: 'top',
            savePanelSpace: true
        });
    }
}
function disableStickpanel() {
    if(activeStickpanel) {
        activeStickpanel = false;
        var $panel = $('[id^=stickyPanelSpace]'),
        	$next = $panel.siblings();
        $panel.remove();
        $next.removeClass('top');
        $next.removeAttr('style');
        $(window).unbind("scroll.stickyPanel");
    }
}
function enableCountdown() {
    if(!$.isViewport('phone') && (typeof d != 'undefined') && !activeCountdown) {
        activeCountdown = true;
        var interval = 1000,
        	htmlTemplate = '';
        if(Math.ceil((d.getTime() - (new Date().getTime())) / (1000 * 60 * 60 * 24)) > 0) {
            htmlTemplate = '<span class="number">%{d}</span>gg <span class="number">%{h}</span>:<span class="number">%{m}</span>';
            interval = 1000 * 60;
        } else
            htmlTemplate = '<span class="number">%{h}</span>:<span class="number">%{m}</span>:<span class="number">%{s}</span>';
        $('#countdown').find('div').countdown({
            htmlTemplate: htmlTemplate,
            date: d,
            updatetime: interval,
            onComplete: function(){
                $(this).html('Tempo scaduto');
            },
            leadingZero:true
        });
    }
}
enableStickpanel();
enableWell();
enableCountdown();
$(window).bind('enterViewportDesktop', enableStickpanel);
$(window).bind('exitViewportDesktop', disableStickpanel);
$(window).bind('enterViewportDesktop', enableWell);
$(window).bind('exitViewportPhone', enableCountdown);
var $operationBack = $operation.find('.back'),
	$operationNext = $operation.find('.next');
if($operationNext.length || $operationBack.length) {
    $(document).keydown(function(e) {
        if(e.ctrlKey && e.which == 37 && $operationBack.length)
			window.location.href = $operationBack.attr('href');
        if(e.ctrlKey && e.which == 39 && $operationNext.length)
			window.location.href = $operationNext.attr('href');
    });
}
if(!LOCAL)
    $.trackPage('UA-3016148-1');