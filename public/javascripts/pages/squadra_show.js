var fancy_options = {
    padding : 0,
    helpers : {
        title : {
            type : 'over'
        }
    }
};
$(document).ready(function() {
    $(".fancybox").fancybox(fancy_options);
});
if(Modernizr.filereader) {
    var uniqueId = (new Date()).getTime();
    $('#fileupload').fileupload({
        dataType: 'json',
        dropZone: $('#dropzone'),
        singleFileUploads: true,
        autoUpload:true,
        send: function (e, data) {
            var dropzone = $("#dropzone .well");
            if(!dropzone.length) {
                dropzone = $("#dropzone");
                dropzone.empty();
                dropzone.append('<div class="well"></div>');
                dropzone = $('.well',dropzone);
            } else
                dropzone.empty();
            dropzone.append('<p>Caricamento in corso</p>');
            dropzone.append('<div aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress progress-success progress-striped active"><div style="width:0%;" class="bar"></div></div>');
        },
        done: function (e, data) {
            var a = $('<a class="fancybox logo left" href="' + data.result[0].url + '">');
            var img = $('<img class="img-polaroid" src="' + data.result[0].thumb_url + '?' + uniqueId + '">');
            a.append(img);
            $('#dropzone').empty().append(a);
            a.fancybox(fancy_options);
        }
    });
    $("#fileupload").bind('fileuploadprogress', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        var bar = $('.progress .bar');
        bar.css('width',progress + '%');
    });
    $(document).bind('dragover', function (e) {
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
    });
} else {
    var dropzone = $("#dropzone");
    if(dropzone.find(".well").length) {
        dropzone.find(".well").remove();
        dropzone.append('<img src="' + IMGSURL + 'no-foto.png"/>');
    }
}