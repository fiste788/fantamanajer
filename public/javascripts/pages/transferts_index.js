$(document).ready(function () {
    var $form = $("#tab_transfert").find("form");
    $("#btn-canc").click(function(ev) {
        submit($form.attr("action"),"submit=" + $(ev.target).val())
    });
    $form.submit(function (ev) {
        ev.preventDefault();
        submit($form.attr("action"),$form.serialize());
    });
});
function submit(url,data) {
    $.post(url, data, function (data) {
            console.log(data.message);
            var notification = document.querySelector('.mdl-js-snackbar');
            notification.MaterialSnackbar.showSnackbar({message: data.message});
            //createToast(data.message);
        });
}