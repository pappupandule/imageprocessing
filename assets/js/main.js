$(document).on('keyup', '.numberOnly', function () {
    if (/\D/g.test(this.value)) {
    // Filter non-digits from input value.
    this.value = this.value.replace(/\D/g, '');
    }
});

function validateUploadImageData() {
    $("#message").html("");
    var path = $("#path").val();
    var wid = $("#width").val();
    var hei = $("#height").val();
    
    if (!path || !wid || !hei) {
        $("#message").html("<font color='red'>Please enter mendatory params!</font>");
        return false;
    }

    if (!path.match(/(?:gif|jpg|png|bmp)$/)) {
        // inputted file path is not an image of one of the above types
        $("#message").html("<font color='red'>inputted file path is not an image!</font>");
        return false;
    }

    $("#upload").submit();
}

function validateUser() {
    $("#message").html("");
    var name = $("#username").val();
    var pwd = $("#password").val();

    if (!name || !pwd) {
        $("#message").html("<font color='red'>Please enter username and/or password!</font>");
        return false;
    }

    $("#login").submit();
}