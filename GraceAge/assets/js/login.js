var $password = $("#password");
var $username = $("#username");

function login(){
    var pass = $password.val();
    var user = $username.val();
    $.post('login_valid', {'password':pass, 'username':user}, function(data){
        if(data.valid_user && data.correct_password){
            window.location.href = "loginPost";
        }
    });
}


