$(document).ready(function(){
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
    
	$('#reader').html5_qrcode(function(data){
            
            //where data will get the decoded information
            // do something when code is read
			$('#read').html(data); //put data on screen, debug feature
                       // alert(data);
                       var foo = JSON.parse(data); //the qr code is a string in json format: {"username":"....","password":"..."}
                       
                        // input the values in a form :
                        document.getElementById("username").value = foo.username; // name is id of "username field
                        document.getElementById("password").value = foo.password; // password is id of "password" field
                        
                        document.getElementById("loginForm").submit();
                        
		},
		function(error){
			$('#read_error').html(error);
		}, function(videoError){
			$('#vid_error').html(videoError);
		}
	);
});
