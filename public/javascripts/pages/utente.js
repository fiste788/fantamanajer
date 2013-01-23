$('#fileupload').fileupload({
    dataType: 'json',
    dropZone: $('#dropzone'),
    singleFileUploads: true,
    autoUpload:true,
    done: function (e, data) {
        $('#dropzone').append('<img src="' + data.result[0].thumb_url + '">');
    }
});
$(document).bind('dragover', function (e) {
    var dropZone = $('#dropzone'),
    timeout = window.dropZoneTimeout;
    if (!timeout) {
        dropZone.addClass('in');
    } else {
        clearTimeout(timeout);
    }
    if (e.target === dropZone[0]) {
        dropZone.addClass('hover');
    } else {
        dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function () {
        window.dropZoneTimeout = null;
        dropZone.removeClass('in hover');
    }, 100);
});