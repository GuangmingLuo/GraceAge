$(document).ready(function(){
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
