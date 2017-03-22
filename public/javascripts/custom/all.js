var $dropdown = $('.dropdown-toggle'),
        $operation = $('#operation'),
        $well = $('.well'),
        $messaggio = $('#messaggio'),
        $tablesorter = $(".table-sorter");
sizes = {"lg": 1200, "md": 992, "sm": 768, "xs": 480};
if ($well.length > 1) {
    enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
        match: function () {
            $well.hover(function () {
                $well.not(this).stop().fadeTo('fast', 0.7);
            }, function () {
                $well.stop().fadeTo('fast', 1);
            });
        },
        unmatch: function () {
            $well.off();
        }
    });
}
if ($operation.length) {
    var $operationBack = $operation.find('.back'),
            $operationNext = $operation.find('.next');
    if ($operationNext.length || $operationBack.length) {
        $(document).keydown(function (e) {
            if (e.ctrlKey && e.which === 37 && $operationBack.length && $operationBack.is('a'))
                window.location.href = $operationBack.attr('href');
            if (e.ctrlKey && e.which === 39 && $operationNext.length && $operationNext.is('a'))
                window.location.href = $operationNext.attr('href');
        });
    }
    var operationForm = $operation.find("form");
    if (operationForm.length && operationForm.attr('method') === 'get') {
        operationForm.find("select").change(function () {
            var option = $(this).find("option:selected");
            var url = option.data('url');
            window.location = (url !== undefined) ? url : operationForm.attr('action') + option.val();
        });
    }
    enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
        match: function () {
            $operation.find('.fix').stickyPanel({
                topPadding: 51,
                afterDetachCSSClass: 'top',
                savePanelSpace: true
            });
        },
        unmatch: function () {
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
/*
 enquire.register("screen and (min-width:" + (sizes.sm) + "px)", {
 match: function () {
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
 complete: function () {
 $(this).html('Tempo scaduto');
 },
 leadingZero: true
 });
 $countdown.removeClass("hidden");
 }
 });
 
 if ($dropdown.length) {
 $dropdown.dropdown();
 $('.dropdown-menu').find('form').click(function (e) {
 e.stopPropagation();
 });
 }*/
if ($messaggio.length) {
    $messaggio.effect("pulsate", {
        times: 2
    }, 1000);
    $messaggio.hover(function () {
        $(this).stop().fadeTo('fast', 0.2);
    }, function () {
        $(this).stop().fadeTo('fast', 1);
    });
    $messaggio.click(function () {
        $(this).off().stop().fadeOut('slow');
    });
}
if ($tablesorter.length) {
    Modernizr.load({
        test: $.tablesorter !== undefined,
        nope: JSURL + 'tablesorter/tablesorter.js',
        complete: function () {
            $tablesorter.tablesorter({
                headers: {
                    0: {
                        sorter: false
                    }
                }
            });
        }
    });
}
$(function () {
    $('.mdl-collapse__content').each(function () {
        var content = $(this);
        content.css('margin-top', -content.height());
    });

    $(document.body).on('click', '.mdl-collapse__button', function () {
        $(this).parent('.mdl-collapse').toggleClass('mdl-collapse--opened');
    });
});
(function () {
    var VISIBLE_CLASS = 'is-showing-options',
            fab_btn = document.getElementById('fab_btn'),
            fab_ctn = document.getElementById('fab_ctn'),
            showOpts = function (e) {
                var processClick = function (evt) {
                    if (e !== evt) {
                        fab_ctn.classList.remove(VISIBLE_CLASS);
                        fab_ctn.IS_SHOWING = false;
                        document.removeEventListener('click', processClick);
                    }
                };
                if (!fab_ctn.IS_SHOWING) {
                    fab_ctn.IS_SHOWING = true;
                    fab_ctn.classList.add(VISIBLE_CLASS);
                    document.addEventListener('click', processClick);
                }
            };
    fab_btn.addEventListener('click', showOpts);
}.call(this));
function onSignIn(googleUser) {
    var id_token = googleUser.getAuthResponse().id_token;
    document.getElementById('google-token').value = id_token;
    document.getElementById('login').submit();
}
$(".mdl-layout__tab").click(function () {
    var href = $(this).attr("href");
    if (href.startsWith('#')) {
        var section = $("section" + href);
        var url = section.data('remote');
        section.data('remote', null);

        if (url) {
            $.ajax({
                url: url,
                success: function (data) {
                    section.empty();
                    section.append(data);

                    componentHandler.upgradeAllRegistered();
                }
            });
        }
    }
});
$(document).ready(selectTab);
$(window).bind('hashchange',selectTab);
function selectTab() {
    var hash = window.location.hash;
    if(hash != "") {
        var $tab = $(".mdl-layout__tab-bar").find("a[href='" + hash + "']");
        if($tab.length) {
            $tab.click();
            $(".mdl-layout__tab-panel").removeClass("is-active");
            $(".mdl-layout__tab").removeClass("is-active");
            $tab.addClass("is-active");
            $("section" + hash).addClass("is-active");
        }
    }
}