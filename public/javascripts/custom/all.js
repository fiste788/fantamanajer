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
    var pre = viewportName.substring(0,2);
    viewportName = (pre === 'gt' || pre === 'lt') ? viewportName : ('is' + viewportName)
    return $('html').hasClass(viewportName);
};
syze.sizes(320, 480, 768, 992, 1200).names({
    320:'xxs',
    480:'xs',
    768:'sm',
    992:'md',
    1200:'lg'
});
$('.dropdown-menu').find('form').click(function (e) {
    e.stopPropagation();
});
var operationForm = $("#operation").find("form");
if(operationForm.length && operationForm.attr('method') == 'get') {
    operationForm.find("select").change(function() {
        var option = $(this).find("option:selected");
        var url = option.data('url');
        if(url !== undefined) {
            window.location = url;
        } else {
            window.location = operationForm.attr('action') + option.val();
        }
    });
}

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
    if($.isViewport('gtxs') && !activeWell) {
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
function disableWell() {
    if($.isViewport('ltsm') && activeWell) {
        activeWell = false;
        var $well = $('.well');
        if($well.length) {
            $well.unbind();
        }
    }
}
function enableStickpanel() {
    if($.isViewport('gtxs') && !activeStickpanel) {
        activeStickpanel = true;
        $operation.find('.fix').stickyPanel({
            topPadding: 50,
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
    if($.isViewport('gtxs') && (typeof d !== 'undefined') && !activeCountdown) {
        activeCountdown = true;
        var interval = 1000,
        	htmlTemplate = '<span class="number">%h</span>:<span class="number">%m</span>:<span class="number">%s</span>';
        if(Math.ceil((d.getTime() - (new Date().getTime())) / (1000 * 60 * 60 * 24)) > 0) {
            htmlTemplate = '<span class="number">%d</span>gg <span class="number">%h</span>:<span class="number">%i</span>';
            interval *= 60;
		}
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
function enableTableSorter() {
    var tablesorter = $(".tablesorter");
    if(tablesorter.length) {
        Modernizr.load({
            test: $.tablesorter != undefined,
            nope: JSURL + 'tablesorter/tablesorter.js',
            complete: function() {
                tablesorter.tablesorter();
            }
        });
    }
}
enableTableSorter();
enableStickpanel();
enableWell();
enableCountdown();
$(window).bind('exitViewportXs', enableStickpanel);
$(window).bind('enterViewportXs', disableStickpanel);
$(window).bind('exitViewportXs', enableWell);
$(window).bind('exitViewportXs', enableCountdown);
$(window).bind('enterViewportXs', disableWell);
var $operationBack = $operation.find('.back'),
	$operationNext = $operation.find('.next');
if($operationNext.length || $operationBack.length) {
    $(document).keydown(function(e) {
        if(e.ctrlKey && e.which === 37 && $operationBack.length && $operationBack.is('a'))
			window.location.href = $operationBack.attr('href');
        if(e.ctrlKey && e.which === 39 && $operationNext.length && $operationNext.is('a'))
			window.location.href = $operationNext.attr('href');
    });
}
if(!LOCAL)
    $.trackPage('UA-3016148-1');