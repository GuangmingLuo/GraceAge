/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var $errorbox = $("#errorbox");

function postForm() {
    var select = document.getElementById("language");
    var lang = select.options[select.selectedIndex].value;
    $.post("change_language", {language: lang});
    
    var old = $("#old").val();
    var newpass = $("#new").val();
    var conf = $("#conf").val();
    $.post("change_password", {old_password: old, new_password: newpass, conf_password: conf}, function(data){
        if(data.success){
            $errorbox.removeClass('alert-warning');
            $errorbox.addClass('alert-success');
        }
        else{
            $errorbox.removeClass('alert-success');
            $errorbox.addClass('alert-warning');
        }
        $errorbox.html(data.err_msg);
        $errorbox.removeClass('inactive');
    });
};


