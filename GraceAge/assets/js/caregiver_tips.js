
var $tips_list = $('#tips_list');

function register_topic(){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    $.post("get_tips", {topic: chosen_topic}, function(tips){
        $tips_list.empty();
        
        
        $.each(tips, function (i, tip) {
            if (tip.hasOwnProperty('dutch') && tip.dutch !== null) {



                $stringdutch = "<div class='col-sm-10'> <li class='fontfamily'  id='" + tip.idtips + "' onClick='tipClick(this.id)'>" + tip.dutch + "</li> </div>"
                        + "<div class='col-sm-2'> <button class='btn btn-default fontfamily' id='button" + tip.idtips + "' onClick='tipClick(" + tip.idtips + ")'> edit </button></div>";

                $tips_list.append($stringdutch); // make new <li> element with id = idtips 
            }
            if (tip.hasOwnProperty('english') && tip.english !== null) {
                $stringenglish = "<div class='col-sm-10'> <li class='fontfamily'  id='" + tip.idtips + "' onClick='tipClick(this.id)'>" + tip.english + "</li> </div>"
                        + "<div class='col-sm-2'> <button class='btn btn-default fontfamily' id='button'" + tip.idtips + " onClick='tipClick(" + tip.idtips + ")'> edit </button></div>";
                $tips_list.append($stringenglish); //old version : <li id='" + tip.idtips +"' onClick='tipClick(this.id)'>"+ tip.english +"</li>
            }
        });
        
        
    });
}

function add_new_tip(){
    var select = document.getElementById("select_topic");
    var language = document.getElementById("select_language");
    var chosen_topic = select.options[select.selectedIndex].value;
    
    var chosen_language = language.options[language.selectedIndex].value;
    alert(chosen_language);
    var new_tip = $("#new_tip").val();
    $.post("add_tip", {tip: new_tip, topic: chosen_topic, language: chosen_language}, function(){
        register_topic();
        alert("yes...");
    });
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
              + "<div class='col-sm-1'><input class='btn'  type='button' onclick='updateTip(" + id+ ")' value='save'></div>" + "<div class='col-sm-1'><input class='btn'  type='button' onclick='deleteTip(" + id+ ")' value='delete'></div>"+ "</form>";
      
      
       $element.after($formHTML);
       $("#editform").hide();
       $("#editform").show(500);
       
};

function updateTip(id){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    var new_tip = $("#newtext").val();
    if(!new_tip) alert("write a tip");
    else{
        $.post("update_tip", {tip: new_tip, topic: chosen_topic, id: id});
        register_topic(); //refresh the tips
    }
};

function deleteTip(id){
    var x;
    if(confirm("are you sure?") == true){
        // delete the tip
        $.post("delete_tip", {id: id});
        register_topic();
    }
    
}

