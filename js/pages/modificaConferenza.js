$("textarea[maxlength]").keydown(function(e) {
    var $this = $(this);
    var testo = $this.val();
    var maxLength = $this.attr("maxlength");
    if(testo.length > maxLength) {
        alert("Hai raggiunto il massimo di caratteri consentito");
        $this.text(testo.substring(0,maxLength));
    }
    $(".cont",$this.parent()).val(maxLength - testo.length);
});
$("#emoticons img").click(function() {
    var text = $("#text");
    var testo = text.val();
    var subText = testo.substring(0, text.get(0).selectionStart);
    subText += ' ' + $(this).data('value');
    var index = subText.length;
    subText += testo.substring(text.get(0).selectionStart);
    text.val(subText);
    text.get(0).selectionEnd = index;
});