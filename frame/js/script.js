
$(window).on("load", function() {
    if ($("#mask").length) {
        $("#mask").hide();
    };
});

function launch_toast(e) {
    $.growl.error({
        title: "<i class='fa fa-exclamation-circle'></i> Error",
        message: e
    });
}

function CopyToClipboard() {

	var copyText = document.getElementById("myInput");
	copyText.select();
	copyText.setSelectionRange(0, 99999)
	document.execCommand("copy");

	$.growl.error({
		title: "<i class='fa fa-exclamation-circle'></i> Atención",
		message: 'Url copiada en el portapapeles'
	});
	
}
