function send_msg() {
    var message = document.getElementById("btn-input").value;
    $.post("send_message", {message : message});
    document.getElementById("updatebox").innerHTML = "Send!";
    $('#updatebox').show();
    setTimeout(function () {
        $('#updatebox').fadeOut();
    }, 1500); // <-- time in milliseconds
}
;
