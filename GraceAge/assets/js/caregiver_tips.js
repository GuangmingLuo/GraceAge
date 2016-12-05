
var $tips_list = $('#tips_list');

function register_topic(){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    $.post("get_tips", {topic: chosen_topic}, function(tips){
        $tips_list.empty();
        
        
        $.each(tips, function(i, tip){
            if(tip.hasOwnProperty('dutch')){
                $tips_list.append("<li id='" + tip.idtips +"' onClick='tipClick(this.id)'>"+ tip.dutch + "</li>"); // make new <li> element with id = idtips 
            }
            if(tip.hasOwnProperty('english')){
                $tips_list.append("<li id='" + tip.idtips +"' onClick='tipClick(this.id)'>"+ tip.english +"</li>");
            }
        });
    });
}

function add_new_tip(){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    var new_tip = $("#new_tip").val();
    $.post("add_tip", {tip: new_tip, topic: chosen_topic}, function(){
        $tips_list.append("<li>"+ new_tip +"</li>");
        alert("yes...");
    });
}


   function tipClick(id){ // do something when a tip is clicked
       
      $(document.getElementById('editform')).remove(); // remove old form if it excists
           
      $(document.getElementById(id)).after("<form id='editform' ></form>"); // show a form here to update or delete the question
      $(document.getElementById('editform')).append("<input type='text' id='newtext'>");
      $(document.getElementById('editform')).append("<input type='button' onclick='updateTip(" + id+ ")' value='update'>"); // button run updateTip(id) on klick
      $(document.getElementById('editform')).append("<input type='button' onclick='deleteTip(" + id+ ")' value='delete'>");
      
     
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

