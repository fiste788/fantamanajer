var $dropdown = $('.dropdown-toggle'),
        $operation = $('#operation'),
        sizes = {"lg": 1200, "md": 992, "sm": 768, "xs": 480};
enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
    match: function() {
        var $well = $('.well');
        if ($well.length > 1) {
            $well.hover(function() {
                $well.not(this).stop().fadeTo('fast', 0.7);
            }, function() {
                $well.stop().fadeTo('fast', 1);
            });
        }
    },
    unmatch: function() {
        var $well = $('.well');
        if ($well.length) {
            $well.off();
        }
    }
});
enquire.register("screen and (min-width:" + (sizes.sm) + "px)", [
    {
        match: function() {
            $operation.find('.fix').stickyPanel({
                topPadding: 51,
                afterDetachCSSClass: 'top',
                savePanelSpace: true
            });
        },
        unmatch: function() {
            var $panel = $('[id^=stickyPanelSpace]'),
                    $next = $panel.siblings();
            data = $next.data("stickyPanel.state");
            $panel.remove();
            $next.removeClass('top');
            $next.removeAttr('style');
            $(window).off("scroll.stickPanel_" + data.id);
        }
    }, {
        match: function() {
            var $countdown = $('#countdown'),
                    timestamp = $countdown.data('data-fine'),
                    d = new Date(timestamp * 1000),
                    interval = 1000,
                    htmlTemplate = '<span class="number">%h</span>:<span class="number">%m</span>:<span class="number">%s</span>';
            if (Math.ceil((d.getTime() - (new Date().getTime())) / (1000 * 60 * 60 * 24)) > 0) {
                htmlTemplate = '<span class="number">%d</span>gg <span class="number">%h</span>:<span class="number">%i</span>';
                interval *= 60;
            }
            $countdown.find('div').countdown({
                htmlTemplate: htmlTemplate,
                date: d,
                updatetime: interval,
                onComplete: function() {
                    $(this).html('Tempo scaduto');
                },
                leadingZero: true
            });
            $countdown.removeClass("hidden");
        }

    }]);
if ($dropdown.length) {
    $dropdown.dropdown();
    $('.dropdown-menu').find('form').click(function(e) {
        e.stopPropagation();
    });
}
var operationForm = $operation.find("form");
if (operationForm.length && operationForm.attr('method') === 'get') {
    operationForm.find("select").change(function() {
        var option = $(this).find("option:selected");
        var url = option.data('url');
        window.location = (url !== undefined) ? url : operationForm.attr('action') + option.val();
    });
}

var $messaggio = $('#messaggio');
if ($messaggio.length) {
    $messaggio.effect("pulsate", {
        times: 2
    }, 1000);
    $messaggio.hover(function() {
        $(this).stop().fadeTo('fast', 0.2);
    }, function() {
        $(this).stop().fadeTo('fast', 1);
    });
    $messaggio.click(function() {
        $(this).unbind().stop().fadeOut('slow');
    });
}

var tablesorter = $(".tablesorter");
if (tablesorter.length) {
    Modernizr.load({
        test: $.tablesorter !== undefined,
        nope: JSURL + 'tablesorter/tablesorter.js',
        complete: function() {
            tablesorter.tablesorter();
        }
    });
}
if ($operation.length) {
    var $operationBack = $operation.find('.back'),
            $operationNext = $operation.find('.next');
    if ($operationNext.length || $operationBack.length) {
        $(document).keydown(function(e) {
            if (e.ctrlKey && e.which === 37 && $operationBack.length && $operationBack.is('a'))
                window.location.href = $operationBack.attr('href');
            if (e.ctrlKey && e.which === 39 && $operationNext.length && $operationNext.is('a'))
                window.location.href = $operationNext.attr('href');
        });
    }
}
if (!LOCAL)
    $.trackPage('UA-3016148-1');