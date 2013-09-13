$("textarea[maxlength]").keydown(function(e) {
    var $this = $(this),
    	testo = $this.val(),
    	maxLength = $this.attr("maxlength");
    if(testo.length > maxLength) {
        alert("Hai raggiunto il massimo di caratteri consentito");
        $this.text(testo.substring(0,maxLength));
    }
    $this.parentsUntil(".form-group").last().parent().find(".cont").val(maxLength - testo.length);
});
$("#emoticons").find("img").click(function() {
    var $text = $("#text"),
    	testo = $text.val();
    	subText = testo.substring(0, $text.get(0).selectionStart);

    subText += ' ' + $(this).data('value');
    var index = subText.length;
    subText += testo.substring($text.get(0).selectionStart);
    $text.val(subText);
    $text.get(0).selectionEnd = index;
});