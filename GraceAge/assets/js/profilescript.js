/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var $errorbox = $("#errorbox");
var $message = "string_init";

function postForm() {
    var select = document.getElementById("language");
    var lang = select.options[select.selectedIndex].value;
    $.post("change_language", {language: lang}, function(status){
        //alert("lang_data = " + status.err_msg);
        $message = status.err_msg;
        var old = $("#old").val();
        var newpass = $("#new").val();
        var conf = $("#conf").val();
        $.post("change_password", {old_password: old, new_password: newpass, conf_password: conf}, function(data){
            //alert("pass_data = " + data.err_msg);
            $message += data.err_msg;
            //alert("message = " + $message);
            $errorbox.html($message);
            $errorbox.removeClass('inactive');
        });
    });
};


