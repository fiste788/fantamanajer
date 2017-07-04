var $dropdown = $('.dropdown-toggle'),
        $operation = $('#operation'),
        $well = $('.well'),
        $messaggio = $('#messaggio'),
        $tablesorter = $(".tablesorter");
sizes = {"lg": 1200, "md": 992, "sm": 768, "xs": 480};
if ($well.length > 1) {

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
}
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
            $tablesorter.tablesorter();
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
                    section.addClass('is-active');
                    section.empty();
                    section.append(data);

                    componentHandler.upgradeAllRegistered();
                }
            });
        }
    }
});
$(document).ready(function () {
    if (!selectTab()) {
        selectTab($(".mdl-layout__tab-bar").children().first());
    }
});
$(window).bind('hashchange', selectTab);
function selectTab($tab) {
    if ($tab == undefined || $tab.length == 0) {
        var hash = window.location.hash;
        if (hash != "") {
            $tab = $(".mdl-layout__tab-bar").find("a[href='" + hash + "']");
        }
    }
    if ($tab && $tab.length) {
        $tab.click();
        $(".mdl-layout__tab-panel").removeClass("is-active");
        $(".mdl-layout__tab").removeClass("is-active");
        $tab.addClass("is-active");
        $("section" + hash).addClass("is-active");
    }
}
$(window).enllax();