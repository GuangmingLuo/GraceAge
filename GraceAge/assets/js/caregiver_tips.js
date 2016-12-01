
var $tips_list = $('#tips_list');

function register_topic(){
    var select = document.getElementById("select_topic");
    var chosen_topic = select.options[select.selectedIndex].value;
    $.post("get_tips", {topic: chosen_topic}, function(tips){
        $tips_list.empty();
        $.each(tips, function(i, tip){
            if(tip.hasOwnProperty('dutch')){
                $tips_list.append("<li>"+ tip.dutch +"</li>");
            }
            if(tip.hasOwnProperty('english')){
                $tips_list.append("<li>"+ tip.english +"</li>");
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


