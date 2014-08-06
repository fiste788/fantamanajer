var $dropdown = $('.dropdown-toggle'),
        $operation = $('#operation'),
        $well = $('.well'),
        $messaggio = $('#messaggio'),
        $tablesorter = $(".tablesorter");
        sizes = {"lg": 1200, "md": 992, "sm": 768, "xs": 480};
if ($well.length > 1) {
    enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
        match: function() {
            $well.hover(function() {
                $well.not(this).stop().fadeTo('fast', 0.7);
            }, function() {
                $well.stop().fadeTo('fast', 1);
            });
        },
        unmatch: function() {
            $well.off();
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
    var operationForm = $operation.find("form");
    if (operationForm.length && operationForm.attr('method') === 'get') {
        operationForm.find("select").change(function() {
            var option = $(this).find("option:selected");
            var url = option.data('url');
            window.location = (url !== undefined) ? url : operationForm.attr('action') + option.val();
        });
    }
    enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
        match: function() {
            $operation.find('.fix').stickyPanel({
                topPadding: 51,
                afterDetachCSSClass: 'top',
                savePanelSpace: true
            });
        },
        unmatch: function() {
            var $panel = $operation.find('.fix');
            if ($panel.length > 0) {
                var data = $panel.data("stickyPanel.state");
                if (data.isDetached) {
                    $('[id^=stickyPanelSpace]').remove();
                    $panel.removeClass('top');
                    $panel.removeAttr('style');
                }
                $(window).off("scroll.stickyPanel_" + data.stickyPanelId);
            }
        }
    }
    );
}
enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
    match: function() {
        var $countdown = $('#countdown'),
            timestamp = $countdown.data('data-fine'),
            d = new Date(timestamp * 1000),
            interval = 1000,
            htmlTemplate = '%h%i%s';
        if ((d.getTime() - new Date().getTime()) > (1000 * 60 * 60 * 24)) {
            htmlTemplate = '%d %h%i%s';
            interval *= 60;
        }
        $countdown.find('div').countdown({
            template: htmlTemplate,
            date: d,
            spaceCharacter: '',
            hourText: '',
            minText: '',
            secText: '',
            dayText: 'dd',
            timeSeparator: ':',
            updatetime: interval,
            complete: function() {
                $(this).html('Tempo scaduto');
            },
            leadingZero: true
        });
        $countdown.removeClass("hidden");
    }
});
if ($dropdown.length) {
    $dropdown.dropdown();
    $('.dropdown-menu').find('form').click(function(e) {
        e.stopPropagation();
    });
}
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
        $(this).off().stop().fadeOut('slow');
    });
}
if ($tablesorter.length) {
    Modernizr.load({
        test: $.tablesorter !== undefined,
        nope: JSURL + 'tablesorter/tablesorter.js',
        complete: function() {
            $tablesorter.tablesorter();
        }
    });
}