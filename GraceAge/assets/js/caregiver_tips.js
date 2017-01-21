
var $tips_list = $('#tips_list');
var localizedText;
$(document).ready(function(){
    $.getJSON("getTipsLocalization",function(data){
        localizedText = data;
    });
});

function register_topic(){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    $.post("get_tips", {topic: chosen_topic}, function(tips){
        $tips_list.empty();
        //alert(JSON.stringify(tips));
        $.each(tips, function (i, tip) {
            if (tip.hasOwnProperty('dutch') && tip.dutch !== null) {

                $stringdutch = "<div class='row'>" + "<div class='col-sm-10'> <li class='tipsstring'  id='" + tip.idtips + "' onClick='tipClick(this.id)'>" + tip.dutch + "</li> </div>"
                        + "<div class='col-xs-2'> <a class='edit fontfamily' id='button" + tip.idtips + "' onClick='tipClick(" + tip.idtips + ")'><i class='fa fa-pencil'></i> " +localizedText.edit+" </a><nobr><a class='delete' id='delete" + tip.idtips + "' onClick='deleteTip(" + tip.idtips + ")' value='delete'><i class='fa fa-trash'></i> delete </a></nobr></div>"
                        + "</div>";

                $tips_list.append($stringdutch); // make new <li> element with id = idtips 
            }
            
            if (tip.hasOwnProperty('english') && tip.english !== null) {
                $stringenglish = "<div class='row'>" + "<div class='col-sm-10'> <li class='tipsstring'  id='" + tip.idtips + "' onClick='tipClick(this.id)'>" + tip.english + "</li> </div>"
                        + "<div class='col-xs-2'> <a class='edit fontfamily' id='button'" + tip.idtips + " onClick='tipClick(" + tip.idtips + ")'><i class='fa fa-pencil'></i> "+localizedText.edit+" </a><nobr><a class='delete' id='delete" + tip.idtips + "' onClick='deleteTip(" + tip.idtips + ")' value='delete'> <i class='fa fa-trash'></i> delete</a></nobr></div>"
                        + "</div>";
                $tips_list.append($stringenglish); //old version : <li id='" + tip.idtips +"' onClick='tipClick(this.id)'>"+ tip.english +"</li>
            }
        });
        
        
    });
}

function add_new_tip() {
    var select = document.getElementById("select_topic");
    var language = document.getElementById("select_language");
    var chosen_topic = select.options[select.selectedIndex].value;
    if (chosen_topic !== "0") {
        var chosen_language = language.options[language.selectedIndex].value;
        //alert(chosen_language);
        var new_tip = $("#new_tip").val();
        if(new_tip !=='') $.post("add_tip", {tip: new_tip, topic: chosen_topic, language: chosen_language}, function () {
            register_topic();
            //alert("yes...");
        });
        else alert(localizedText.write_a_tip);
    } else
        alert(localizedText.choose_a_topic);
}


   function tipClick(id){ // do something when a tip is clicked
            
       
      $(document.getElementById('editform')).remove(); // remove old form if it excists
      
      $("li").show(1000); // show the lines again
      $("[id^='button']").show(); //show all buttons again
      
      $(document.getElementById("button"+id)).hide();  //hide the button belonging to id
      $element = $(document.getElementById(id));
      $element.hide();
      
      var text_value = document.getElementById(id).innerHTML;     
      //$(document.getElementById(id)).after("<form id='editform' ></form>"); // show a form here to update or delete the question
      //$(document.getElementById('editform')).append("<input type='text' id='newtext' value='"+text_value+"'>");
      //$(document.getElementById('editform')).append("<input type='button' onclick='updateTip(" + id+ ")' value='update'>"); // button run updateTip(id) on klick
      //$(document.getElementById('editform')).append("<input type='button' onclick='deleteTip(" + id+ ")' value='delete'>");
      
      $formHTML = "<form id='editform' >" + "<input type='text' id='newtext' value='"+text_value+"'>" 
              + "<input class='btn save'  type='button' onclick='updateTip(" + id+ ")' value='save'>"+ "</form>";
      
      
       $element.after($formHTML);
       $("#editform").hide();
       $("#editform").show(500);
       
};

function updateTip(id){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    var new_tip = $("#newtext").val();
    var language = $("#tip_language_" + id).text();
    var lang = "dutch";
    if(language === "English"){
        alert("inside if!");
        lang = "english";
    }
    alert(lang);
    if(!new_tip) alert(localizedText.write_a_tip);
    else{
        $.post("update_tip", {tip: new_tip, topic: chosen_topic, id: id, language:lang});
        alert("okay");
        register_topic(); //refresh the tips
    }
};

function deleteTip(id){
        // delete the tip
        $.post("delete_tip", {id: id}, function(data){
            if(data){
                register_topic();
            }
            else{
                deleteTip(id);
            }
        });
    
    
    showToast(localizedText.notify_deleted);
    
}



