var fancy_options = {
    padding: 0,
    helpers: {
        title: {
            type: 'over'
        }
    }
};
var $dropzone = $('#dropzone');
$(".fancybox").fancybox(fancy_options);
//if(Modernizr.filereader) {
var uniqueId = (new Date()).getTime();
$('#fileupload').fileupload({
    dataType: 'json',
    dropZone: $dropzone,
    singleFileUploads: true,
    autoUpload: true,
    send: function(e, data) {
        var $well = $dropzone.find(".well");
        if (!$well.length) {
            $dropzone.empty();
            $well = $('<div class="well"></div>');
            $dropzone.append($well);
        } else
            $well.empty();
        $well.append('<p>Caricamento in corso</p>');
        $well.append('<div class="progress progress-striped active"><div aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" style="width:0%;" class="progress-bar progress-bar-success"></div></div>');
    },
    done: function(e, data) {
        var a = $('<a style="display:none" class="fancybox logo" href="' + data.result[0].url + '">');
        var img = $('<img class="img-thumbnail" src="' + data.result[0].thumb_url + '?id=' + uniqueId + '">');
        a.append(img);
        $dropzone.children().fadeOut(function() {
            $dropzone.empty().append(a);
            a.fadeIn();
            a.fancybox(fancy_options);
        });
    },
    progress: function(e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        var bar = $dropzone.find('.progress-bar');
        bar.css('width', progress + '%');
        bar.attr('aria-value-now', progress);
    },
    dragover: function(e, data) {
        var timeout = window.dropZoneTimeout;
        if (!timeout) {
            $dropzone.addClass("hover");
        } else {
            clearTimeout(timeout);
        }
        window.dropZoneTimeout = setTimeout(function() {
            window.dropZoneTimeout = null;
            $dropzone.removeClass('hover');
        }, 100);
    }
});
/*$(document).on('dragover', function (e) {
 var dropZone = $('#dropzone'),
 timeout = window.dropZoneTimeout;
 if (!timeout)
 dropZone.addClass('in');
 else
 clearTimeout(timeout);
 if (e.target === dropZone[0])
 dropZone.addClass('hover');
 else
 dropZone.removeClass('hover');
 window.dropZoneTimeout = setTimeout(function () {
 window.dropZoneTimeout = null;
 dropZone.removeClass('in hover');
 }, 100);
 });*/
/*} else {
 var dropzone = $("#dropzone");
 if(dropzone.find(".well").length) {
 dropzone.find(".well").remove();
 dropzone.append('<img src="' + IMGSURL + 'no-foto.png"/>');
 }
 }*/