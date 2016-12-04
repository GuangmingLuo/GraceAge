/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function postForm() {


    var select = document.getElementById("language");
    var lang = select.options[select.selectedIndex].value;
    var old = document.getElementById("old").value;
    
    var newpass = $("#new").val();
    var conf = $("#conf").val();
    
    if(old == false || newpass == false || conf ==false){
        alert("fields empty");
    }
    else if(newpass=== conf){
        
        $.post("change_language", {language: lang}, null);
        $.post("change_password",{old_password: old, new_password: newpass, conf_password: conf}, null);
        alert("changes saved")
    }
    else alert("the new pasword does not match");
    

};

