function determineBrowserForQR() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf('safari') !== -1) {
        if (ua.indexOf('chrome') > -1) {
            document.getElementById('safariQRfunctionaity').style.display = 'none'; // Chrome
        } else {
            document.getElementById('reader').style.display = 'none'; // Safari
        }
    }
}

$(function () {
    determineBrowserForQR();
});

function handleFiles(file) {
    var reader = new FileReader();
    reader.onload = (function (theFile) {
        return function (e) {
            qrcode.decode(e.target.result);
        }
    })(file);
    reader.readAsDataURL(file);
}

$('#photo').change(function () {
    //console.log(this.files[0]);
    $('div#processing').show();
    handleFiles(this.files[0]);
});
var qrError;
$.get("getQrError", function(data){
    qrError = data;
});

$('#reader').html5_qrcode(function (data) {
    //where data will get the decoded information
    // do something when code is read
    $('#read').html(data); //put data on screen, debug feature
    // alert(data);
    var foo = JSON.parse(data); //the qr code is a string in json format: {"username":"....","password":"..."}
    // input the values in a form :
    document.getElementById("username").value = foo.username; // name is id of "username field
    document.getElementById("password").value = foo.password; // password is id of "password" field
    //document.getElementById("loginForm").submit();
    login();
    },
    function (error) {
        if(error == "Couldn't find enough finder patterns"){
            error = qrError;
        }
        $('#read_error').html(error);
    }, 
    function (videoError) {
        $('#vid_error').html(videoError);
    }
);